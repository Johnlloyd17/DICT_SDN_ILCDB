<?php

namespace App\Http\Controllers;

use App\Models\DtcCenterInventory;
use App\Models\DtcHub;
use App\Models\DtcVisitorLog;
use Illuminate\Http\Request;

class SdnPdiController extends Controller
{
    public function index(Request $request)
    {
        $hubs = DtcHub::where('status', 'Active')->orderBy('name')->get();

        // SDN: Municipality breakdown
        $municipalities = DtcHub::select('municipality')
            ->distinct()->orderBy('municipality')->pluck('municipality');

        $municipalityStats = [];
        foreach ($municipalities as $muni) {
            $hubIds = DtcHub::where('municipality', $muni)->pluck('id');
            $logs = DtcVisitorLog::whereIn('dtc_hub_id', $hubIds);
            $municipalityStats[$muni] = [
                'hub_count' => $hubIds->count(),
                'visitor_count' => $logs->count(),
                'unique_citizens' => (clone $logs)->distinct('visitor_name')->count('visitor_name'),
                'hub_names' => DtcHub::where('municipality', $muni)->pluck('name')->implode(', '),
            ];
        }

        // SDN: Legislative district breakdown (District 1 & 2)
        $districtMunicipalities = [
            'District 1' => ['Burgos', 'Dapa', 'Del Carmen', 'General Luna', 'Pilar', 'San Benito', 'San Isidro', 'Santa Monica', 'Socorro'],
            'District 2' => ['Surigao City', 'Alegria', 'Bacuag', 'Claver', 'Gigaquit', 'Mainit', 'Malimono', 'Placer', 'San Francisco (Anao-aon)', 'Sison', 'Tagana-an', 'Tubod'],
        ];
        $districtDescriptions = [
            'District 1' => 'Island Municipalities (Siargao & Bucas Grande)',
            'District 2' => 'Mainland Component',
        ];

        $districtStats = [];
        $centerDistrictStats = DtcCenterInventory::query()
            ->selectRaw('congressional_district, count(*) as center_count, count(distinct municipality_city) as muni_count')
            ->whereIn('congressional_district', ['1st', '2nd'])
            ->groupBy('congressional_district')
            ->get()
            ->keyBy('congressional_district');

        foreach ($districtMunicipalities as $district => $munis) {
            $label = $district === 'District 1' ? '1st' : '2nd';
            $row = $centerDistrictStats[$label] ?? null;
            $districtStats[$district] = [
                'center_count' => $row->center_count ?? 0,
                'municipality_count' => $row->muni_count ?? 0,
                'description' => $districtDescriptions[$district],
                'municipalities' => $munis,
            ];
        }

        $selectedMuni = $request->input('muni', 'ALL');
        $selectedDistrict = $request->input('district', 'ALL');
        $tab = $request->input('tab', 'dashboard');
        $activeTab = in_array($tab, ['dashboard', 'pdi'], true) ? $tab : 'dashboard';
        $sdnView = (bool)$request->input('sdn_view', false);
        $sdnQuery = DtcVisitorLog::with('dtcHub');
        if ($selectedMuni !== 'ALL') {
            $hubIds = DtcHub::where('municipality', $selectedMuni)->pluck('id');
            $sdnQuery->whereIn('dtc_hub_id', $hubIds);
        }
        $sdnVisitors = $sdnQuery->orderByDesc('visit_date')->paginate(10, ['*'], 'sdn_page')->withQueryString();

        // Center Inventory
        $sdnCenters = DtcCenterInventory::query();
        if ($selectedMuni !== 'ALL') {
            $sdnCenters->where('municipality_city', $selectedMuni);
        }
        if ($selectedDistrict !== 'ALL') {
            $label = $selectedDistrict === 'District 1' ? '1st' : '2nd';
            $sdnCenters->where('congressional_district', $label);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $sdnCenters->where(function ($q) use ($s) {
                $q->where('center_name', 'like', "%{$s}%")
                  ->orWhere('municipality_city', 'like', "%{$s}%")
                  ->orWhere('barangay', 'like', "%{$s}%");
            });
        }
        if ($request->filled('operational') && $request->operational !== 'ALL') {
            $sdnCenters->where('operational_status', $request->operational);
        }
        if ($request->filled('connectivity') && $request->connectivity !== 'ALL') {
            $sdnCenters->where('connectivity_status', $request->connectivity);
        }
        $allowedPerPage = [5, 10, 20, 30, 40, 50, 100, 150, 200];
        $centerPerPage = in_array((int)$request->get('per_page'), $allowedPerPage, true) ? (int)$request->get('per_page') : 10;
        $sdnCenters = $sdnCenters->orderBy('municipality_city')->orderBy('barangay')->paginate($centerPerPage, ['*'], 'center_page')->withQueryString();
        $totalCenterCount = DtcCenterInventory::count();
        $operationalCenters = DtcCenterInventory::where('operational_status', 'Operational')->count();
        $withConnectivity = DtcCenterInventory::where(function ($q) {
            $q->where('connectivity_status', 'Online')->orWhere('connectivity_status', 'Connected');
        })->count();
        $centerMunicipalities = DtcCenterInventory::distinct()->count('municipality_city');

        // Dashboard: Tech4ED centers established per municipality by province
        $sdnMuniCenters = DtcCenterInventory::where('province', 'Surigao del Norte')
            ->selectRaw('municipality_city, count(*) as total')
            ->groupBy('municipality_city')->orderBy('municipality_city')->get();

        $dinagatMuniCenters = DtcCenterInventory::where('province', 'Dinagat Islands')
            ->selectRaw('municipality_city, count(*) as total')
            ->groupBy('municipality_city')->orderBy('municipality_city')->get();

        // Dashboard: operational status per province
        $operationalByProvince = [];
        DtcCenterInventory::selectRaw('province, operational_status, count(*) as total')
            ->groupBy('province', 'operational_status')->orderBy('province')->get()
            ->each(function ($row) use (&$operationalByProvince) {
                $operationalByProvince[$row->province][$row->operational_status] = $row->total;
            });

        // Dashboard: connectivity status per province
        $connectivityByProvince = [];
        DtcCenterInventory::selectRaw('province, connectivity_status, count(*) as total')
            ->groupBy('province', 'connectivity_status')->orderBy('province')->get()
            ->each(function ($row) use (&$connectivityByProvince) {
                $connectivityByProvince[$row->province][$row->connectivity_status] = $row->total;
            });

        // Dashboard: Tech4ED DTC centers established per province by center host type
        $hostTypeLabels = [
            'DICT Provincial Training Center' => 'DICT Owned',
            'LGU' => 'LGU',
            'Public School' => 'DepEd Schools',
            'Private School' => 'Private Schools',
            'SUC' => 'SUC',
            'NGA' => 'NGA',
        ];
        $hostTypeLabelMap = array_flip($hostTypeLabels);
        $centersByHostProvince = [];
        DtcCenterInventory::selectRaw('province, type_of_center_host, count(*) as total')
            ->whereNotNull('type_of_center_host')
            ->groupBy('province', 'type_of_center_host')->orderBy('province')->get()
            ->each(function ($row) use (&$centersByHostProvince, $hostTypeLabelMap) {
                $label = $hostTypeLabelMap[$row->type_of_center_host] ?? $row->type_of_center_host;
                $centersByHostProvince[$row->province][$label] = $row->total;
            });

        // Order provinces so Surigao del Norte renders before Dinagat Islands
        $provinceOrder = ['Surigao del Norte' => 0, 'Dinagat Islands' => 1];
        $sortProvinces = function (array &$data) use ($provinceOrder) {
            uksort($data, fn ($a, $b) => ($provinceOrder[$a] ?? 99) <=> ($provinceOrder[$b] ?? 99));
        };
        $sortProvinces($operationalByProvince);
        $sortProvinces($connectivityByProvince);
        $sortProvinces($centersByHostProvince);

        return view('sdn-pdi.index', compact(
            'hubs', 'municipalities', 'municipalityStats', 'districtStats', 'districtMunicipalities', 'districtDescriptions', 'selectedMuni', 'selectedDistrict', 'activeTab', 'sdnView',
            'sdnVisitors', 'sdnCenters', 'totalCenterCount', 'operationalCenters', 'withConnectivity', 'centerMunicipalities',
            'sdnMuniCenters', 'dinagatMuniCenters', 'operationalByProvince', 'connectivityByProvince',
            'hostTypeLabels', 'centersByHostProvince'
        ));
    }
}
