<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DtcService;
use App\Models\Visit;
use Illuminate\Http\Request;

class DtcVisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with('visitor', 'services')
            ->select('visits.id', 'visits.visit_code', 'visits.visitor_id', 'visits.dtc_hub_id', 'visits.check_in_time');

        if ($request->filled('year')) {
            $query->whereYear('check_in_time', $request->year);
        }

        return response()->json($query->get()->map(function (Visit $visit) {
            return [
                'id' => $visit->id,
                'visit_code' => $visit->visit_code,
                'visitor_name' => $visit->visitor->name ?? '',
                'demographic_sector' => $visit->visitor->demographic_sector ?? '',
                'services_ailed' => $visit->services->pluck('service_name')->values()->all(),
                'visit_date' => $visit->check_in_time ? $visit->check_in_time->toDateTimeString() : null,
                'dtc_hub_id' => $visit->dtc_hub_id,
            ];
        }));
    }

    public function traffic(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[$m] = Visit::whereYear('check_in_time', $year)
                ->whereMonth('check_in_time', $m)
                ->count();
        }

        return response()->json($data);
    }

    public function services(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $counts = DtcService::where('is_active', true)
            ->withCount(['visitServices as session_count' => function ($query) use ($year) {
                $query->whereHas('visit', function ($v) use ($year) {
                    $v->whereYear('check_in_time', $year);
                });
            }])
            ->orderByDesc('session_count')
            ->get()
            ->pluck('session_count', 'service_name');

        return response()->json($counts->all());
    }
}