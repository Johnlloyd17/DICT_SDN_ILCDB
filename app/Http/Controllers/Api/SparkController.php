<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SparkTrainee;
use App\Models\SparkTraining;

class SparkController extends Controller
{
    public function trainings()
    {
        return response()->json(
            SparkTraining::select('track_id', 'specialization', 'enrolled_count', 'budget_allocated', 'status')
                ->get()
                ->toArray()
        );
    }

    public function demographics()
    {
        $trainees = SparkTrainee::select('employment_status', 'municipality', 'monthly_earnings')->get();

        $employmentCounts = $trainees->groupBy('employment_status')
            ->map(fn($items) => $items->count())
            ->toArray();

        $municipalityCounts = $trainees->groupBy('municipality')
            ->map(fn($items) => $items->count())
            ->toArray();

        return response()->json([
            'employment' => $employmentCounts,
            'municipalities' => $municipalityCounts,
        ]);
    }

    public function financials()
    {
        $trainings = SparkTraining::select('track_id', 'specialization', 'budget_allocated', 'status')->get();

        $budgetByStatus = $trainings->groupBy('status')
            ->map(fn($items) => $items->sum('budget_allocated'))
            ->toArray();

        $enrolledByStatus = $trainings->groupBy('status')
            ->map(fn($items) => $items->sum('enrolled_count'))
            ->toArray();

        return response()->json([
            'budget_by_status' => $budgetByStatus,
            'enrolled_by_status' => $enrolledByStatus,
            'total_budget' => $trainings->sum('budget_allocated'),
            'total_enrolled' => $trainings->sum('enrolled_count'),
        ]);
    }
}
