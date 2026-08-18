<?php

namespace App\Http\Controllers\Dtc;

use App\Http\Controllers\Controller;
use App\Models\DtcCenterInventory;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CenterInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = DtcCenterInventory::query();

        if ($request->filled('municipality') && $request->municipality !== 'ALL') {
            $query->where('municipality_city', $request->municipality);
        }

        if ($request->filled('operational') && $request->operational !== 'ALL') {
            $query->where('operational_status', $request->operational);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('center_name', 'like', "%{$s}%")
                  ->orWhere('municipality_city', 'like', "%{$s}%")
                  ->orWhere('barangay', 'like', "%{$s}%");
            });
        }

        $allowedPerPage = [5, 10, 15, 20, 30, 40, 50, 100, 150, 200];
        $perPage = in_array((int)$request->get('per_page'), $allowedPerPage, true) ? (int)$request->get('per_page') : 15;

        $centers = $query->orderBy('municipality_city')->orderBy('barangay')->paginate($perPage)->withQueryString();

        $municipalities = DtcCenterInventory::distinct()->orderBy('municipality_city')->pluck('municipality_city');
        $totalCenters = DtcCenterInventory::count();
        $operationalCenters = DtcCenterInventory::where('operational_status', 'Operational')->count();
        $withConnectivity = DtcCenterInventory::where('connectivity_status', 'Connected')->count();

        return view('dtc.center-inventories.index', compact(
            'centers', 'municipalities', 'totalCenters', 'operationalCenters', 'withConnectivity'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'municipality_city' => 'required|string|max:100',
            'center_name' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'congressional_district' => 'nullable|string|max:100',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'verified' => 'nullable|boolean',
            'moa_date_of_signing' => 'nullable|date',
            'date_of_launching' => 'nullable|date',
            'date_of_platform_registration' => 'nullable|date',
            'tcms_status' => 'nullable|string|max:50',
            'tcms_key' => 'nullable|string|max:100',
            'tcms_identifier' => 'nullable|string|max:100',
            'tcms_verification_status' => 'nullable|string|max:50',
            'odk_status' => 'nullable|string|max:50',
            'connectivity_status' => 'nullable|string|max:50',
            'type_of_center_host' => 'nullable|string|max:100',
            'operational_status' => 'nullable|string|max:50',
        ]);

        $center = DtcCenterInventory::create($request->all());

        if ($request->wantsJson()) {
            return response()->json(['center' => $center], 201);
        }
        return redirect()->back(302, [], route('dtc.centers.index'))
            ->with('success', 'Center added successfully.');
    }

    public function update(Request $request, DtcCenterInventory $center)
    {
        $request->validate([
            'municipality_city' => 'required|string|max:100',
            'center_name' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'congressional_district' => 'nullable|string|max:100',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'verified' => 'nullable|boolean',
            'moa_date_of_signing' => 'nullable|date',
            'date_of_launching' => 'nullable|date',
            'date_of_platform_registration' => 'nullable|date',
            'tcms_status' => 'nullable|string|max:50',
            'tcms_key' => 'nullable|string|max:100',
            'tcms_identifier' => 'nullable|string|max:100',
            'tcms_verification_status' => 'nullable|string|max:50',
            'odk_status' => 'nullable|string|max:50',
            'connectivity_status' => 'nullable|string|max:50',
            'type_of_center_host' => 'nullable|string|max:100',
            'operational_status' => 'nullable|string|max:50',
        ]);

        $center->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['center' => $center->fresh()]);
        }
        return redirect()->back(302, [], route('dtc.centers.index'))
            ->with('success', 'Center updated successfully.');
    }

    public function destroy(DtcCenterInventory $center)
    {
        $center->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Center removed.']);
        }
        return redirect()->back(302, [], route('dtc.centers.index'))
            ->with('success', 'Center removed.');
    }

    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:dtc_center_inventories,id',
        ]);

        $count = DtcCenterInventory::whereIn('id', $request->ids)->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => "Successfully deleted {$count} center(s)."]);
        }
        return redirect()->back(302, [], route('dtc.centers.index'))
            ->with('success', "Successfully deleted {$count} center(s).");
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
                return redirect()->back(302, [], route('dtc.centers.index'))
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
            return redirect()->back(302, [], route('dtc.centers.index'))
                ->with('error', 'The uploaded file is empty.');
        }

        // Sanitizer helper for matching header keys
        $sanitize = function ($str) {
            if ($str === null) return '';
            // Remove UTF-8 BOM and non-breaking spaces
            $str = preg_replace('/\x{FEFF}/u', '', (string)$str);
            $str = str_replace("\xc2\xa0", ' ', $str);
            $str = mb_strtolower($str);
            // Remove all non-alphanumeric characters for fuzzy key matching
            return preg_replace('/[^a-z0-9]/', '', $str);
        };

        // Alias mapping for each database field
        $aliases = [
            'municipality_city' => [
                'municipalitycity', 'municipality/city', 'municipality', 'city',
                'citymunicipality', 'lgu', 'location', 'town', 'cityormunicipality'
            ],
            'center_name' => [
                'centername', 'center', 'nameofcenter', 'dtccentername', 'dtccenter',
                'name', 'hubname', 'dtcname', 'facilityname'
            ],
            'congressional_district' => [
                'congressionaldistrict', 'congressional', 'district'
            ],
            'province' => ['province'],
            'barangay' => ['barangay', 'brgy'],
            'longitude' => ['longitude', 'long', 'lng'],
            'latitude' => ['latitude', 'lat'],
            'verified' => ['verified', 'isverified'],
            'moa_date_of_signing' => [
                'moadateofsigning', 'moadate', 'dateofmoasigning', 'moasigningdate'
            ],
            'date_of_launching' => [
                'dateoflaunching', 'launchingdate', 'launchdate', 'datelaunched'
            ],
            'date_of_platform_registration' => [
                'dateofplatformregistration', 'platformregistrationdate', 'registrationdate'
            ],
            'tcms_status' => ['tcmsstatus'],
            'tcms_key' => ['tcmskey', 'key'],
            'tcms_identifier' => ['tcmsidentifier', 'identifier'],
            'tcms_verification_status' => ['tcmsverificationstatus'],
            'odk_status' => ['odkstatus'],
            'connectivity_status' => ['connectivitystatus', 'connectivity'],
            'type_of_center_host' => ['typeofcenterhost', 'centerhosttype', 'centerhost', 'hosttype'],
            'operational_status' => ['operationalstatus', 'operational'],
        ];

        // Helper to match a sanitized alias against a list of sanitized headers
        $matchAlias = function ($sanitizedAlias, $sanitizedRow) {
            if ($sanitizedAlias === '') return false;
            foreach ($sanitizedRow as $idx => $sanitizedHeader) {
                if ($sanitizedHeader !== '' && str_contains($sanitizedHeader, $sanitizedAlias)) {
                    return $idx;
                }
            }
            return false;
        };

        // Search for header row in top 15 rows
        $headerRowIdx = null;
        $map = [];

        for ($r = 0; $r < min(15, count($allRows)); $r++) {
            $rowCandidate = $allRows[$r];
            if (!is_array($rowCandidate) || empty(array_filter($rowCandidate))) continue;

            $candidateMap = [];
            $sanitizedRow = array_map($sanitize, $rowCandidate);

            foreach ($aliases as $field => $fieldAliases) {
                foreach ($fieldAliases as $alias) {
                    $sanitizedAlias = $sanitize($alias);
                    $foundIdx = $matchAlias($sanitizedAlias, $sanitizedRow);
                    if ($foundIdx !== false) {
                        $candidateMap[$field] = $foundIdx;
                        break;
                    }
                }
            }

            // Check if both required fields (municipality_city & center_name) are mapped
            if (isset($candidateMap['municipality_city'], $candidateMap['center_name'])) {
                $headerRowIdx = $r;
                $map = $candidateMap;
                break;
            }
        }

        // Scan all header rows (0-14) for any additional unmapped columns
        // (handles multi-row headers like merged cells in exported spreadsheets)
        if ($headerRowIdx !== null) {
            for ($r = 0; $r < min(15, count($allRows)); $r++) {
                if ($r === $headerRowIdx) continue;
                $rowCandidate = $allRows[$r];
                if (!is_array($rowCandidate) || empty(array_filter($rowCandidate))) continue;

                $sanitizedRow = array_map($sanitize, $rowCandidate);
                foreach ($aliases as $field => $fieldAliases) {
                    if (isset($map[$field])) continue;
                    foreach ($fieldAliases as $alias) {
                        $sanitizedAlias = $sanitize($alias);
                        $foundIdx = $matchAlias($sanitizedAlias, $sanitizedRow);
                        if ($foundIdx !== false) {
                            $map[$field] = $foundIdx;
                            break;
                        }
                    }
                }
            }
        }

        // Date Established group: MOA Date of Signing, Date of Launching,
        // Date of Platform Registration, Status (tcms_status)
        if (!isset($map['tcms_status'])) {
            foreach (['date_of_platform_registration', 'date_of_launching', 'moa_date_of_signin'] as $field) {
                if (isset($map[$field])) {
                    $candidate = $map[$field] + 1;
                    if (!in_array($candidate, $map)) {
                        $map['tcms_status'] = $candidate;
                        break;
                    }
                }
            }
        }

        // TCMS group: Key, Identifier, Status (tcms_verification_status)
        if (!isset($map['tcms_verification_status'])) {
            if (isset($map['tcms_identifier'])) {
                $candidate = $map['tcms_identifier'] + 1;
                if (!in_array($candidate, $map)) {
                    $map['tcms_verification_status'] = $candidate;
                }
            } elseif (isset($map['tcms_key'])) {
                $candidate = $map['tcms_key'] + 2;
                if (!in_array($candidate, $map)) {
                    $map['tcms_verification_status'] = $candidate;
                }
            }
        }

        $expected = ['municipality_city', 'center_name'];
        if ($headerRowIdx === null) {
            $rawHeaders = isset($allRows[0]) && is_array($allRows[0]) ? array_filter(array_map('trim', $allRows[0])) : [];
            $foundColumnsStr = !empty($rawHeaders) ? implode(', ', array_slice($rawHeaders, 0, 10)) : 'None';

            $missing = [];
            foreach ($expected as $reqKey) {
                if (!isset($map[$reqKey])) {
                    $missing[] = $reqKey === 'municipality_city' ? 'Municipality/City' : 'Center Name';
                }
            }
            $missingStr = implode(' and ', $missing);

            return redirect()->back(302, [], route('dtc.centers.index'))
                ->with('error', "Import failed: Missing required column(s) ({$missingStr}) in file. Detected headers: [{$foundColumnsStr}]. Please check your header row or download the template.");
        }

        $imported = 0;
        $errors = [];

        // Determine the maximum column index from the header map
        $maxColIdx = max($map);

        $dataRows = array_slice($allRows, $headerRowIdx + 1);

        foreach ($dataRows as $i => $row) {
            if (!is_array($row)) continue;
            // Trim nulls and empty strings only — preserve '0' values
            $filtered = array_filter($row, fn($v) => $v !== null && $v !== '');
            if (empty($filtered)) continue;

            // Pad row to match maximum expected column index (handles missing trailing columns)
            $row = array_pad($row, $maxColIdx + 1, '');

            $data = [];
            foreach ($map as $field => $idx) {
                $val = isset($row[$idx]) ? trim((string)$row[$idx]) : '';

                if ($val === '') {
                    $data[$field] = null;
                    continue;
                }

                if ($field === 'verified') {
                    $data[$field] = in_array(strtolower($val), ['yes', 'true', '1', '✓', 'y']) ? 1 : 0;
                } elseif (in_array($field, ['moa_date_of_signing', 'date_of_launching', 'date_of_platform_registration'])) {
                    if (is_numeric($val) && in_array($ext, ['xlsx', 'xls'])) {
                        try {
                            $data[$field] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$val)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $data[$field] = null;
                        }
                    } else {
                        $ts = strtotime($val);
                        $data[$field] = $ts !== false ? date('Y-m-d', $ts) : null;
                    }
                } elseif (in_array($field, ['longitude', 'latitude'])) {
                    $data[$field] = is_numeric($val) ? (float)$val : null;
                } else {
                    $data[$field] = $val;
                }
            }

            // Ensure required fields are not empty
            if (empty($data['municipality_city']) || empty($data['center_name'])) {
                $rowNum = $headerRowIdx + $i + 2;
                $errors[] = "Row {$rowNum}: missing Center Name or Municipality/City";
                continue;
            }

            // Debug: dump first 3 rows as JSON
            if ($i < 3) {
                \Log::debug('IMPORT ROW ' . ($i + 1) . ': ' . json_encode($data, JSON_PRETTY_PRINT));
            }

            try {
                DtcCenterInventory::create($data);
                $imported++;
            } catch (\Exception $e) {
                $rowNum = $headerRowIdx + $i + 2;
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        $message = "Imported {$imported} centers successfully.";
        if (!empty($errors)) {
            $message .= ' Warnings/Errors: ' . implode('; ', array_slice($errors, 0, 5));
        }

        return redirect()->back(302, [], route('dtc.centers.index'))
            ->with($imported > 0 ? 'success' : 'error', $message);
    }
}
