<?php

namespace App\Http\Controllers;

use App\Models\FundingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundingController extends Controller
{
    public function index(Request $request)
    {
        $query = FundingRecord::query();

        if ($request->filled('project') && $request->project !== 'ALL') {
            $query->where('project', $request->project);
        }

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('voucher_ref', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('expense_category', 'like', "%{$search}%");
            });
        }

        $records = $query->orderByDesc('transaction_date')->paginate(15)->withQueryString();

        $totalAllocated = FundingRecord::sum('allocated');
        $totalObligated = FundingRecord::sum('obligated');
        $totalDisbursed = FundingRecord::sum('disbursed');
        $obligationRate = $totalAllocated > 0 ? round($totalObligated / $totalAllocated * 100) : 0;
        $disbursementRate = $totalAllocated > 0 ? round($totalDisbursed / $totalAllocated * 100) : 0;
        $remainingUnobligated = $totalAllocated - $totalObligated;

        $projectFunding = FundingRecord::select('project')
            ->selectRaw('SUM(allocated) as total_allocated')
            ->selectRaw('SUM(obligated) as total_obligated')
            ->selectRaw('SUM(disbursed) as total_disbursed')
            ->groupBy('project')
            ->get();

        $categories = FundingRecord::select('expense_category')
            ->selectRaw('SUM(allocated) as total')
            ->groupBy('expense_category')
            ->orderByDesc('total')
            ->get();

        $historical = FundingRecord::select(
            DB::raw('YEAR(transaction_date) as year'),
            DB::raw('SUM(allocated) as total_allocated'),
            DB::raw('SUM(obligated) as total_obligated'),
            DB::raw('SUM(disbursed) as total_disbursed'),
        )
            ->groupBy(DB::raw('YEAR(transaction_date)'))
            ->orderBy('year')
            ->get();

        $years = range(2022, 2026);
        $historicalMap = $historical->keyBy('year');

        $historicalData = [];
        $prevDisbursed = null;
        foreach ($years as $year) {
            $row = $historicalMap->get($year);
            $disbursed = $row ? (float) $row->total_disbursed : 0;
            $allocated = $row ? (float) $row->total_allocated : 0;
            $obligated = $row ? (float) $row->total_obligated : 0;
            $growth = $prevDisbursed && $prevDisbursed > 0
                ? round(($disbursed - $prevDisbursed) / $prevDisbursed * 100, 1)
                : 0;
            $historicalData[] = [
                'year' => $year,
                'allocated' => $allocated,
                'obligated' => $obligated,
                'disbursed' => $disbursed,
                'growth' => $growth,
            ];
            if ($disbursed > 0) {
                $prevDisbursed = $disbursed;
            }
        }

        return view('funding.index', compact(
            'records',
            'totalAllocated', 'totalObligated', 'totalDisbursed',
            'obligationRate', 'disbursementRate', 'remainingUnobligated',
            'projectFunding', 'categories', 'historicalData'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project' => 'required|in:DWIA-TMD,DTC HUB,SPARK,PROJECT CLICK',
            'description' => 'required|string|max:500',
            'expense_category' => 'required|string|max:100',
            'voucher_ref' => 'required|string|max:50|unique:funding_records,voucher_ref',
            'allocated' => 'required|numeric|min:0',
            'obligated' => 'required|numeric|min:0',
            'disbursed' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:Disbursed,Obligated,Pending',
        ]);

        $record = FundingRecord::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['record' => $record], 201);
        }
        return redirect()->route('funding.index')->with('success', 'Funding record added successfully.');
    }

    public function update(Request $request, FundingRecord $funding)
    {
        $request->validate([
            'project' => 'required|in:DWIA-TMD,DTC HUB,SPARK,PROJECT CLICK',
            'description' => 'required|string|max:500',
            'expense_category' => 'required|string|max:100',
            'voucher_ref' => 'required|string|max:50|unique:funding_records,voucher_ref,' . $funding->id,
            'allocated' => 'required|numeric|min:0',
            'obligated' => 'required|numeric|min:0',
            'disbursed' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:Disbursed,Obligated,Pending',
        ]);

        $funding->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['record' => $funding->fresh()]);
        }
        return redirect()->route('funding.index')->with('success', 'Funding record updated.');
    }

    public function destroy(FundingRecord $funding)
    {
        $funding->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Funding record deleted.']);
        }
        return redirect()->route('funding.index')->with('success', 'Funding record deleted.');
    }
}
