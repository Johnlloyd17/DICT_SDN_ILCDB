<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FundingRecord;
use Illuminate\Support\Facades\DB;

class FundingController extends Controller
{
    public function summary()
    {
        $projectFunding = FundingRecord::select('project')
            ->selectRaw('SUM(allocated) as total_allocated')
            ->selectRaw('SUM(obligated) as total_obligated')
            ->selectRaw('SUM(disbursed) as total_disbursed')
            ->groupBy('project')
            ->get();

        $totalAllocated = FundingRecord::sum('allocated');
        $totalDisbursed = FundingRecord::sum('disbursed');

        return response()->json([
            'projects' => $projectFunding,
            'totals' => [
                'allocated' => (float) $totalAllocated,
                'disbursed' => (float) $totalDisbursed,
            ],
        ]);
    }

    public function categories()
    {
        $categories = FundingRecord::select('expense_category')
            ->selectRaw('SUM(allocated) as total')
            ->groupBy('expense_category')
            ->orderByDesc('total')
            ->get();

        return response()->json($categories);
    }

    public function historical()
    {
        $historical = FundingRecord::select(
            DB::raw('YEAR(transaction_date) as year'),
            DB::raw('SUM(allocated) as total_allocated'),
            DB::raw('SUM(obligated) as total_obligated'),
            DB::raw('SUM(disbursed) as total_disbursed'),
        )
            ->groupBy(DB::raw('YEAR(transaction_date)'))
            ->orderBy('year')
            ->get();

        return response()->json($historical);
    }
}
