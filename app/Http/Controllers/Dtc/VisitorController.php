<?php

namespace App\Http\Controllers\Dtc;

use App\Http\Controllers\Controller;
use App\Models\DtcCenterInventory;
use App\Models\DtcHub;
use App\Models\DtcVisitorLog;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $allowedViews = ['dashboard', 'centers', 'services'];
        $view = in_array($request->get('view'), $allowedViews, true) ? $request->get('view') : 'dashboard';

        $tab = $request->input('tab', 'dashboard');
        $activeTab = in_array($tab, ['dashboard', 'pdi'], true) ? $tab : 'dashboard';
        $sdnView = (bool)$request->input('sdn_view', false);

        $hubs = DtcHub::where('status', 'Active')->orderBy('name')->get();

        // ==================== VISITOR LOGS (services tab) ====================
        $visitorQuery = DtcVisitorLog::with('dtcHub');

        if ($request->filled('hub') && $request->hub !== 'ALL') {
            $hubIds = DtcHub::where('name', $request->hub)->pluck('id');
            $visitorQuery->whereIn('dtc_hub_id', $hubIds);
        }

        if ($request->filled('demo') && $request->demo !== 'ALL') {
            $visitorQuery->where('demographic_sector', $request->demo);
        }

        if ($request->filled('service') && $request->service !== 'ALL') {
            $visitorQuery->whereJsonContains('services_ailed', $request->service);
        }

        if ($request->filled('v_search')) {
            $s = $request->v_search;
            $visitorQuery->where(function ($q) use ($s) {
                $q->where('visitor_name', 'like', "%{$s}%")
                  ->orWhere('log_code', 'like', "%{$s}%")
                  ->orWhere('demographic_sector', 'like', "%{$s}%");
            });
        }

        $visitors = $visitorQuery->orderByDesc('visit_date')->paginate(15, ['*'], 'v_page')->withQueryString();

        $totalTraffic = DtcVisitorLog::count();
        $uniqueCitizens = DtcVisitorLog::distinct('visitor_name')->count('visitor_name');

        $servicesCount = [];
        DtcVisitorLog::pluck('services_ailed')->each(function ($svc) use (&$servicesCount) {
            $decoded = is_array($svc) ? $svc : (json_decode($svc, true) ?? []);
            foreach ($decoded as $s) {
                $servicesCount[$s] = ($servicesCount[$s] ?? 0) + 1;
            }
        });
        arsort($servicesCount);
        $topService = array_key_first($servicesCount) ?? '—';

        $firstVisit = DtcVisitorLog::min('visit_date');
        $lastVisit = DtcVisitorLog::max('visit_date');
        if ($firstVisit && $lastVisit) {
            $days = max(1, (int)ceil((strtotime($lastVisit) - strtotime($firstVisit)) / 86400) + 1);
        } else {
            $days = max(1, now()->diffInDays(now()->subMonth()) ?: 1);
        }
        $avgDaily = $totalTraffic > 0 ? round($totalTraffic / $days, 1) : 0;

        $activeHubs = DtcHub::where('status', 'Active')->count();

        // ==================== CENTER INVENTORY (centers tab) ====================
        $centerQuery = DtcCenterInventory::query();

        if ($request->filled('municipality') && $request->municipality !== 'ALL') {
            $centerQuery->where('municipality_city', $request->municipality);
        }

        if ($request->filled('c_operational') && $request->c_operational !== 'ALL') {
            $centerQuery->where('operational_status', $request->c_operational);
        }

        if ($request->filled('c_search')) {
            $s = $request->c_search;
            $centerQuery->where(function ($q) use ($s) {
                $q->where('center_name', 'like', "%{$s}%")
                  ->orWhere('municipality_city', 'like', "%{$s}%")
                  ->orWhere('barangay', 'like', "%{$s}%");
            });
        }

        $allowedPerPage = [5, 10, 15, 20, 30, 40, 50, 100, 150, 200];
        $centerPerPage = in_array((int)$request->get('c_per_page'), $allowedPerPage, true) ? (int)$request->get('c_per_page') : 15;
        $centers = $centerQuery->orderBy('municipality_city')->orderBy('barangay')->paginate($centerPerPage, ['*'], 'c_page')->withQueryString();

        $municipalities = DtcCenterInventory::distinct()->orderBy('municipality_city')->pluck('municipality_city');
        $totalCenters = DtcCenterInventory::count();
        $operationalCenters = DtcCenterInventory::where('operational_status', 'Operational')->count();
        $withConnectivity = DtcCenterInventory::where(function ($q) {
            $q->where('connectivity_status', 'Online')->orWhere('connectivity_status', 'Connected');
        })->count();

        // ==================== SDN / PDI DASHBOARD (dashboard tab) ====================
        $selectedMuni = $request->input('muni', 'ALL');
        $selectedDistrict = $request->input('district', 'ALL');

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

        $hubMunicipalities = DtcHub::select('municipality')->distinct()->orderBy('municipality')->pluck('municipality');

        $sdnCenters = DtcCenterInventory::query();
        if ($selectedMuni !== 'ALL') {
            $sdnCenters->where('municipality_city', $selectedMuni);
        }
        if ($selectedDistrict !== 'ALL') {
            $label = $selectedDistrict === 'District 1' ? '1st' : '2nd';
            $sdnCenters->where('congressional_district', $label);
        }
        if ($request->filled('s_search')) {
            $s = $request->s_search;
            $sdnCenters->where(function ($q) use ($s) {
                $q->where('center_name', 'like', "%{$s}%")
                  ->orWhere('municipality_city', 'like', "%{$s}%")
                  ->orWhere('barangay', 'like', "%{$s}%");
            });
        }
        if ($request->filled('s_operational') && $request->s_operational !== 'ALL') {
            $sdnCenters->where('operational_status', $request->s_operational);
        }
        if ($request->filled('connectivity') && $request->connectivity !== 'ALL') {
            $sdnCenters->where('connectivity_status', $request->connectivity);
        }
        $sdnPerPage = in_array((int)$request->get('s_per_page'), $allowedPerPage, true) ? (int)$request->get('s_per_page') : 10;
        $sdnCenters = $sdnCenters->orderBy('municipality_city')->orderBy('barangay')->paginate($sdnPerPage, ['*'], 'sdn_page')->withQueryString();

        $totalCenterCount = DtcCenterInventory::count();
        $servicesOperationalCenters = DtcCenterInventory::where('operational_status', 'Operational')->count();
        $servicesWithConnectivity = DtcCenterInventory::where(function ($q) {
            $q->where('connectivity_status', 'Online')->orWhere('connectivity_status', 'Connected');
        })->count();
        $centerMunicipalities = DtcCenterInventory::distinct()->count('municipality_city');

        $sdnMuniCenters = DtcCenterInventory::where('province', 'Surigao del Norte')
            ->selectRaw('municipality_city, count(*) as total')
            ->groupBy('municipality_city')->orderBy('municipality_city')->get();

        $dinagatMuniCenters = DtcCenterInventory::where('province', 'Dinagat Islands')
            ->selectRaw('municipality_city, count(*) as total')
            ->groupBy('municipality_city')->orderBy('municipality_city')->get();

        $operationalByProvince = [];
        DtcCenterInventory::selectRaw('province, operational_status, count(*) as total')
            ->groupBy('province', 'operational_status')->orderBy('province')->get()
            ->each(function ($row) use (&$operationalByProvince) {
                $operationalByProvince[$row->province][$row->operational_status] = $row->total;
            });

        $connectivityByProvince = [];
        DtcCenterInventory::selectRaw('province, connectivity_status, count(*) as total')
            ->groupBy('province', 'connectivity_status')->orderBy('province')->get()
            ->each(function ($row) use (&$connectivityByProvince) {
                $connectivityByProvince[$row->province][$row->connectivity_status] = $row->total;
            });

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

        $provinceOrder = ['Surigao del Norte' => 0, 'Dinagat Islands' => 1];
        $sortProvinces = function (array &$data) use ($provinceOrder) {
            uksort($data, fn ($a, $b) => ($provinceOrder[$a] ?? 99) <=> ($provinceOrder[$b] ?? 99));
        };
        $sortProvinces($operationalByProvince);
        $sortProvinces($connectivityByProvince);
        $sortProvinces($centersByHostProvince);

        return view('dtc.visitors.index', compact(
            'view', 'activeTab', 'sdnView', 'hubs',
            'visitors', 'totalTraffic', 'uniqueCitizens', 'servicesCount', 'topService', 'avgDaily', 'activeHubs',
            'centers', 'municipalities', 'totalCenters', 'operationalCenters', 'withConnectivity',
            'districtStats', 'selectedMuni', 'selectedDistrict', 'hubMunicipalities', 'sdnCenters',
            'totalCenterCount', 'servicesOperationalCenters', 'servicesWithConnectivity', 'centerMunicipalities',
            'sdnMuniCenters', 'dinagatMuniCenters', 'operationalByProvince', 'connectivityByProvince',
            'hostTypeLabels', 'centersByHostProvince'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'age' => 'required|integer|min:10|max:99',
            'demographic_sector' => 'required|string|max:100',
            'dtc_hub_id' => 'required|exists:dtc_hubs,id',
            'services' => 'required|array',
            'services.*' => 'string|max:100',
            'session_duration' => 'required|string|max:50',
            'visit_date' => 'nullable|date',
        ]);

        $lastId = DtcVisitorLog::max('id') ?? 0;
        $code = 'DTC-' . date('Y') . '-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        DtcVisitorLog::create([
            'log_code' => $code,
            'visitor_name' => $request->visitor_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'demographic_sector' => $request->demographic_sector,
            'dtc_hub_id' => $request->dtc_hub_id,
            'services_ailed' => $request->services,
            'session_duration' => $request->session_duration,
            'visit_date' => $request->visit_date ?: now(),
        ]);

        return redirect()->back(302, [], route('dtc.visitors.index'))
            ->with('success', 'Visitor session recorded successfully.');
    }

    public function update(Request $request, DtcVisitorLog $visitor)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'age' => 'required|integer|min:10|max:99',
            'demographic_sector' => 'required|string|max:100',
            'dtc_hub_id' => 'required|exists:dtc_hubs,id',
            'services' => 'required|array',
            'services.*' => 'string|max:100',
            'session_duration' => 'required|string|max:50',
            'visit_date' => 'nullable|date',
        ]);

        $visitor->update([
            'visitor_name' => $request->visitor_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'demographic_sector' => $request->demographic_sector,
            'dtc_hub_id' => $request->dtc_hub_id,
            'services_ailed' => $request->services,
            'session_duration' => $request->session_duration,
            'visit_date' => $request->visit_date ?: $visitor->visit_date,
        ]);

        return redirect()->back(302, [], route('dtc.visitors.index'))
            ->with('success', 'Visitor log updated successfully.');
    }

    public function destroy(DtcVisitorLog $visitor)
    {
        $visitor->delete();

        return redirect()->back(302, [], route('dtc.visitors.index'))
            ->with('success', 'Visitor log removed.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $allRows = [];

        if (in_array($ext, ['xlsx', 'xls'])) {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $allRows = $spreadsheet->getActiveSheet()->toArray();
            } catch (\Exception $e) {
                return redirect()->back(302, [], route('dtc.visitors.index'))
                    ->with('error', 'Failed to read Excel file: ' . $e->getMessage());
            }
        } else {
            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $allRows[] = $row;
                }
                fclose($handle);
            }
        }

        if (empty($allRows)) {
            return redirect()->back(302, [], route('dtc.visitors.index'))
                ->with('error', 'The uploaded file is empty.');
        }

        $sanitize = function ($str) {
            if ($str === null) return '';
            $str = preg_replace('/\x{FEFF}/u', '', (string)$str);
            $str = str_replace("\xc2\xa0", ' ', $str);
            $str = mb_strtolower($str);
            return preg_replace('/[^a-z0-9]/', '', $str);
        };

        $aliases = [
            'visitor_name' => ['visitorname', 'fullname', 'name', 'nameofvisitor', 'citizen', 'username'],
            'gender' => ['gender', 'sex'],
            'age' => ['age', 'ageinyears'],
            'demographic_sector' => ['demographicsector', 'demographic', 'sector', 'citizentype', 'classification'],
            'dtc_hub_id' => ['dtchubid', 'dtchub', 'hub', 'center', 'dtccenter', 'hublocation', 'hubname'],
            'services_ailed' => ['servicesailed', 'services', 'availedservices', 'service'],
            'session_duration' => ['sessionduration', 'duration', 'hours', 'time'],
            'visit_date' => ['visitdate', 'dateofvisit', 'date', 'logdate'],
        ];

        $matchAlias = function ($sanitizedAlias, $sanitizedRow) {
            if ($sanitizedAlias === '') return false;
            foreach ($sanitizedRow as $idx => $sanitizedHeader) {
                if ($sanitizedHeader !== '' && str_contains($sanitizedHeader, $sanitizedAlias)) {
                    return $idx;
                }
            }
            return false;
        };

        $headerRowIdx = null;
        $map = [];

        for ($r = 0; $r < min(15, count($allRows)); $r++) {
            $rowCandidate = $allRows[$r];
            if (!is_array($rowCandidate) || empty(array_filter($rowCandidate))) continue;

            $candidateMap = [];
            $sanitizedRow = array_map($sanitize, $rowCandidate);

            foreach ($aliases as $field => $fieldAliases) {
                foreach ($fieldAliases as $alias) {
                    $foundIdx = $matchAlias($sanitize($alias), $sanitizedRow);
                    if ($foundIdx !== false) {
                        $candidateMap[$field] = $foundIdx;
                        break;
                    }
                }
            }

            if (isset($candidateMap['visitor_name'], $candidateMap['dtc_hub_id'])) {
                $headerRowIdx = $r;
                $map = $candidateMap;
                break;
            }
        }

        if ($headerRowIdx === null) {
            $rawHeaders = isset($allRows[0]) && is_array($allRows[0]) ? array_filter(array_map('trim', $allRows[0])) : [];
            $foundColumnsStr = !empty($rawHeaders) ? implode(', ', array_slice($rawHeaders, 0, 10)) : 'None';

            return redirect()->back(302, [], route('dtc.visitors.index'))
                ->with('error', "Import failed: Missing required column(s) (Visitor Name and DTC Hub) in file. Detected headers: [{$foundColumnsStr}]. Please check your header row or download the template.");
        }

        $hubsById = DtcHub::pluck('name', 'id')->toArray();

        $resolveHub = function ($val) use ($hubsById) {
            if (is_numeric($val)) {
                return isset($hubsById[(int)$val]) ? (int)$val : null;
            }
            $needle = mb_strtolower(trim($val));
            foreach ($hubsById as $id => $name) {
                if (mb_strtolower($name) === $needle || str_contains(mb_strtolower($name), $needle)) {
                    return (int)$id;
                }
            }
            return null;
        };

        $maxColIdx = max($map);
        $dataRows = array_slice($allRows, $headerRowIdx + 1);

        $imported = 0;
        $errors = [];

        foreach ($dataRows as $i => $row) {
            if (!is_array($row)) continue;
            $filtered = array_filter($row, fn($v) => $v !== null && $v !== '');
            if (empty($filtered)) continue;

            $row = array_pad($row, $maxColIdx + 1, '');

            $data = [];
            foreach ($map as $field => $idx) {
                $val = isset($row[$idx]) ? trim((string)$row[$idx]) : '';
                if ($val === '') {
                    $data[$field] = null;
                    continue;
                }

                if ($field === 'age') {
                    $data[$field] = is_numeric($val) ? (int)$val : null;
                } elseif ($field === 'dtc_hub_id') {
                    $data[$field] = $resolveHub($val);
                } elseif ($field === 'services_ailed') {
                    $data[$field] = preg_split('/[;,\r\n]+/', $val, -1, PREG_SPLIT_NO_EMPTY);
                } elseif ($field === 'visit_date') {
                    $ts = strtotime($val);
                    $data[$field] = $ts !== false ? date('Y-m-d H:i:s', $ts) : null;
                } else {
                    $data[$field] = $val;
                }
            }

            if (empty($data['visitor_name']) || empty($data['dtc_hub_id'])) {
                $rowNum = $headerRowIdx + $i + 2;
                $errors[] = "Row {$rowNum}: missing Visitor Name or valid DTC Hub";
                continue;
            }

            $data['gender'] = in_array($data['gender'], ['Male', 'Female'], true) ? $data['gender'] : 'Male';
            $data['age'] = $data['age'] ?: 0;
            $data['demographic_sector'] = $data['demographic_sector'] ?: 'Unclassified';
            $data['services_ailed'] = $data['services_ailed'] ?? ['Free High-Speed Internet'];
            $data['session_duration'] = $data['session_duration'] ?: '—';
            $data['visit_date'] = $data['visit_date'] ?: now();
            $data['log_code'] = 'DTC-' . date('Y') . '-' . str_pad((DtcVisitorLog::max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);

            try {
                DtcVisitorLog::create($data);
                $imported++;
            } catch (\Exception $e) {
                $rowNum = $headerRowIdx + $i + 2;
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        $message = "Imported {$imported} visitor log(s) successfully.";
        if (!empty($errors)) {
            $message .= ' Warnings/Errors: ' . implode('; ', array_slice($errors, 0, 5));
        }

        return redirect()->back(302, [], route('dtc.visitors.index'))
            ->with($imported > 0 ? 'success' : 'error', $message);
    }
}
