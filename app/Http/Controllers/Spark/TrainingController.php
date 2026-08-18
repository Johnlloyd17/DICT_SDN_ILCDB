<?php

namespace App\Http\Controllers\Spark;

use App\Http\Controllers\Controller;
use App\Models\SparkTraining;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = SparkTraining::query();

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('track_id', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('master_trainer', 'like', "%{$search}%")
                  ->orWhere('industry_partner', 'like', "%{$search}%");
            });
        }

        $trainings = $query->orderByDesc('id')->paginate(10)->withQueryString();

        $totalBudget = SparkTraining::sum('budget_allocated');
        $totalEnrolled = SparkTraining::sum('enrolled_count');
        $activeBatches = SparkTraining::where('status', 'Ongoing')->count();
        $completedBatches = SparkTraining::where('status', 'Completed')->count();
        $upcomingBatches = SparkTraining::where('status', 'Upcoming')->count();

        return view('spark.trainings.index', compact(
            'trainings', 'totalBudget', 'totalEnrolled', 'activeBatches',
            'completedBatches', 'upcomingBatches'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'track_id' => 'required|string|max:50|unique:spark_trainings,track_id',
            'specialization' => 'required|string|max:255',
            'master_trainer' => 'required|string|max:255',
            'enrolled_count' => 'required|integer|min:0',
            'budget_allocated' => 'required|numeric|min:0',
            'industry_partner' => 'required|string|max:255',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
        ]);

        $training = SparkTraining::create($request->only([
            'track_id', 'specialization', 'master_trainer',
            'enrolled_count', 'budget_allocated', 'industry_partner', 'status',
        ]));

        if ($request->wantsJson()) {
            return response()->json(['training' => $training], 201);
        }
        return redirect()->route('spark.trainings.index')->with('success', 'SPARK training added successfully.');
    }

    public function update(Request $request, SparkTraining $training)
    {
        $request->validate([
            'specialization' => 'required|string|max:255',
            'master_trainer' => 'required|string|max:255',
            'enrolled_count' => 'required|integer|min:0',
            'budget_allocated' => 'required|numeric|min:0',
            'industry_partner' => 'required|string|max:255',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
        ]);

        $training->update($request->only([
            'specialization', 'master_trainer', 'enrolled_count',
            'budget_allocated', 'industry_partner', 'status',
        ]));

        if ($request->wantsJson()) {
            return response()->json(['training' => $training->fresh()]);
        }
        return redirect()->route('spark.trainings.index')->with('success', 'Training updated.');
    }

    public function destroy(SparkTraining $training)
    {
        $training->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Training removed.']);
        }
        return redirect()->route('spark.trainings.index')->with('success', 'Training removed.');
    }
}
