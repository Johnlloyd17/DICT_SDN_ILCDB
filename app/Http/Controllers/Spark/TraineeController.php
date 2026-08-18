<?php

namespace App\Http\Controllers\Spark;

use App\Http\Controllers\Controller;
use App\Models\SparkTrainee;
use Illuminate\Http\Request;

class TraineeController extends Controller
{
    public function index(Request $request)
    {
        $query = SparkTrainee::query();

        if ($request->filled('employment') && $request->employment !== 'ALL') {
            $query->where('employment_status', $request->employment);
        }

        if ($request->filled('municipality') && $request->municipality !== 'ALL') {
            $query->where('municipality', $request->municipality);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('trainee_code', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }

        $trainees = $query->orderByDesc('id')->paginate(10)->withQueryString();

        $totalTrainees = SparkTrainee::count();
        $totalEarnings = SparkTrainee::sum('monthly_earnings');
        $avgEarnings = $totalTrainees > 0 ? round($totalEarnings / $totalTrainees) : 0;
        $employed = SparkTrainee::where('employment_status', 'Employed')->count();
        $freelancers = SparkTrainee::whereIn('employment_status', ['Full-Time Freelancer', 'Part-Time Freelancer'])->count();
        $selfEmployed = SparkTrainee::where('employment_status', 'Self-Employed')->count();

        $municipalities = SparkTrainee::distinct()->pluck('municipality')->sort()->values();

        return view('spark.trainees.index', compact(
            'trainees', 'totalTrainees', 'totalEarnings', 'avgEarnings',
            'employed', 'freelancers', 'selfEmployed', 'municipalities'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'municipality' => 'required|string|max:100',
            'employment_status' => 'required|string|max:100',
            'monthly_earnings' => 'nullable|numeric|min:0',
        ]);

        $lastId = SparkTrainee::max('id') ?? 0;
        $code = 'SPK-' . date('Y') . '-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $trainee = SparkTrainee::create([
            'trainee_code' => $code,
            'full_name' => $request->full_name,
            'specialty' => $request->specialty,
            'course' => $request->course,
            'municipality' => $request->municipality,
            'employment_status' => $request->employment_status,
            'monthly_earnings' => $request->monthly_earnings,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['trainee' => $trainee], 201);
        }
        return redirect()->route('spark.trainees.index')->with('success', 'Trainee added successfully.');
    }

    public function update(Request $request, SparkTrainee $trainee)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'municipality' => 'required|string|max:100',
            'employment_status' => 'required|string|max:100',
            'monthly_earnings' => 'nullable|numeric|min:0',
        ]);

        $trainee->update($request->only([
            'full_name', 'specialty', 'course', 'municipality',
            'employment_status', 'monthly_earnings',
        ]));

        if ($request->wantsJson()) {
            return response()->json(['trainee' => $trainee->fresh()]);
        }
        return redirect()->route('spark.trainees.index')->with('success', 'Trainee updated.');
    }

    public function destroy(SparkTrainee $trainee)
    {
        $trainee->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Trainee removed.']);
        }
        return redirect()->route('spark.trainees.index')->with('success', 'Trainee removed.');
    }
}
