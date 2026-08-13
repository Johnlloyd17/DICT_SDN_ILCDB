<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DtcVisitorLog;
use Illuminate\Http\Request;

class DtcVisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = DtcVisitorLog::select('demographic_sector', 'services_ailed', 'visit_date', 'dtc_hub_id');

        if ($request->filled('year')) {
            $query->whereYear('visit_date', $request->year);
        }

        return response()->json($query->get()->toArray());
    }

    public function traffic(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $monthly = DtcVisitorLog::whereYear('visit_date', $year)
            ->selectRaw('MONTH(visit_date) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(visit_date)')
            ->pluck('count', 'month')
            ->toArray();

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[$m] = $monthly[$m] ?? 0;
        }

        return response()->json($data);
    }

    public function services(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $logs = DtcVisitorLog::whereYear('visit_date', $year)->pluck('services_ailed');

        $counts = [];
        $logs->each(function ($svc) use (&$counts) {
            $decoded = is_array($svc) ? $svc : (json_decode($svc, true) ?? []);
            foreach ($decoded as $s) {
                $counts[$s] = ($counts[$s] ?? 0) + 1;
            }
        });

        arsort($counts);
        return response()->json($counts);
    }
}
