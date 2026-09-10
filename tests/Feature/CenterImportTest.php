<?php

namespace Tests\Feature;

use App\Models\DtcCenterInventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CenterImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_centers_with_standard_headers(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $csvContent = "Congressional District,Province,Municipality/City,Barangay,Center Name,Verified\n"
                    . "District 1,Surigao del Norte,Surigao City,Washington,DTC Center 1,Yes\n";

        $file = UploadedFile::fake()->createWithContent('centers.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'municipality_city' => 'Surigao City',
            'center_name' => 'DTC Center 1',
            'verified' => true,
        ]);
    }

    public function test_can_import_centers_with_bom_and_header_variations(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // UTF-8 BOM + header variations like "Municipality / City" and "Center_Name"
        $bom = "\xEF\xBB\xBF";
        $csvContent = $bom . "Municipality / City,Center_Name,Barangay\n"
                    . "Mainit,Mainit Tech Hub,Poblacion\n";

        $file = UploadedFile::fake()->createWithContent('centers_bom.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'municipality_city' => 'Mainit',
            'center_name' => 'Mainit Tech Hub',
            'barangay' => 'Poblacion',
        ]);
    }

    public function test_can_import_centers_with_alternative_header_names(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // "Municipality" instead of "Municipality/City", "Name" instead of "Center Name"
        $csvContent = "Municipality,Name,Status\n"
                    . "Dapa,Siargao DTC,Operational\n";

        $file = UploadedFile::fake()->createWithContent('centers_alt.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'municipality_city' => 'Dapa',
            'center_name' => 'Siargao DTC',
            'operational_status' => 'Operational',
        ]);
    }

    public function test_can_import_centers_when_header_is_on_row_2(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // Title banner on line 1, header on line 2
        $csvContent = "DICT SURIGAO DEL NORTE DTC LIST 2026,,\n"
                    . "Municipality/City,Center Name,Barangay\n"
                    . "Claver,Claver Tech Hub,Tayaga\n";

        $file = UploadedFile::fake()->createWithContent('centers_row2.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'municipality_city' => 'Claver',
            'center_name' => 'Claver Tech Hub',
            'barangay' => 'Tayaga',
        ]);
    }

    public function test_returns_helpful_error_when_required_columns_are_missing(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $csvContent = "Random Column A,Random Column B\n"
                    . "Value 1,Value 2\n";

        $file = UploadedFile::fake()->createWithContent('bad_headers.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('error');
    }

    public function test_can_import_centers_with_two_row_merged_xlsx_header(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Row 1: merged group headers
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'No.');
        $sheet->mergeCells('B1:F1');
        $sheet->setCellValue('B1', 'CENTER DETAILS');
        $sheet->mergeCells('G1:I1');
        $sheet->setCellValue('G1', 'GPS Coordinates');
        $sheet->mergeCells('J1:M1');
        $sheet->setCellValue('J1', 'Date Established');
        $sheet->mergeCells('N1:P1');
        $sheet->setCellValue('N1', 'TCMS');
        $sheet->mergeCells('Q1:Q2');
        $sheet->setCellValue('Q1', 'ODK Status');
        $sheet->mergeCells('R1:R2');
        $sheet->setCellValue('R1', 'Connectivity Status');
        $sheet->mergeCells('S1:S2');
        $sheet->setCellValue('S1', 'TYPE OF CENTER HOST');
        $sheet->mergeCells('T1:T2');
        $sheet->setCellValue('T1', 'Operational Status');

        // Row 2: field-name headers
        $sheet->setCellValue('B2', 'Congressional District');
        $sheet->setCellValue('C2', 'Province');
        $sheet->setCellValue('D2', 'Municipality/City');
        $sheet->setCellValue('E2', 'Barangay');
        $sheet->setCellValue('F2', 'Center Name');
        $sheet->setCellValue('G2', 'Longitude');
        $sheet->setCellValue('H2', 'Latitude');
        $sheet->setCellValue('I2', 'Verified');
        $sheet->setCellValue('J2', 'MOA Date of Signing');
        $sheet->setCellValue('K2', 'Date of Launching');
        $sheet->setCellValue('L2', 'Date of Platform Registration');
        $sheet->setCellValue('M2', 'Status');
        $sheet->setCellValue('N2', 'Key');
        $sheet->setCellValue('O2', 'Identifier');
        $sheet->setCellValue('P2', 'Status');

        // Row 3: data row
        $cells = [
            'A3' => 1,                          // No.
            'B3' => 'District 1',              // Congressional District
            'C3' => 'Surigao del Norte',       // Province
            'D3' => 'Surigao City',            // Municipality/City
            'E3' => 'Washington',              // Barangay
            'F3' => 'DTC Washington Center',   // Center Name
            'G3' => 120.605522,                // Longitude
            'H3' => 16.575633,                 // Latitude
            'I3' => 'TRUE',                    // Verified
            'J3' => '2023-01-15',              // MOA Date of Signing
            'K3' => '2023-06-20',              // Date of Launching
            'L3' => '2023-09-01',              // Date of Platform Registration
            'M3' => 'Established',             // Status (tcms_status)
            'N3' => 'KEY-001',                 // Key (tcms_key)
            'O3' => 'ID-001',                  // Identifier (tcms_identifier)
            'P3' => 'For Scheduling',          // Status (tcms_verification_status)
            'Q3' => 'TRUE',                    // ODK Status
            'R3' => 'Online',                  // Connectivity Status
            'S3' => 'LGU',                     // Type of Center Host
            'T3' => 'Operational',             // Operational Status
        ];
        foreach ($cells as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Save to temp file
        $tempPath = tempnam(sys_get_temp_dir(), 'center_import_') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempPath);

        $file = new \Illuminate\Http\UploadedFile($tempPath, 'centers_2row.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        if (session('error')) {
            $this->fail('Import error: ' . session('error'));
        }
        $response->assertSessionHas('success');

        // Verify all 19 DB columns have correct values
        $this->assertDatabaseHas('dtc_center_inventories', [
            'congressional_district' => 'District 1',
            'province' => 'Surigao del Norte',
            'municipality_city' => 'Surigao City',
            'barangay' => 'Washington',
            'center_name' => 'DTC Washington Center',
            'longitude' => 120.605522,
            'latitude' => 16.575633,
            'verified' => true,
            'moa_date_of_signing' => '2023-01-15 00:00:00',
            'date_of_launching' => '2023-06-20 00:00:00',
            'date_of_platform_registration' => '2023-09-01 00:00:00',
            'tcms_status' => 'Established',
            'tcms_key' => 'KEY-001',
            'tcms_identifier' => 'ID-001',
            'tcms_verification_status' => 'For Scheduling',
            'odk_status' => 'TRUE',
            'connectivity_status' => 'Online',
            'type_of_center_host' => 'LGU',
            'operational_status' => 'Operational',
        ]);

        // Verify center_name is NOT a number (the bug symptom)
        $center = \App\Models\DtcCenterInventory::first();
        $this->assertIsString($center->center_name);
        $this->assertNotEmpty($center->center_name);
        $this->assertMatchesRegularExpression('/[a-zA-Z]/', $center->center_name, 'Center Name should contain letters, not just numbers');

        // Verify municipality is NOT a number
        $this->assertMatchesRegularExpression('/[a-zA-Z]/', $center->municipality_city, 'Municipality should contain letters, not just numbers');

        // Verify no header/label text leaked into data
        $this->assertNotEquals('OPERATIONAL', $center->municipality_city);
        $this->assertNotEquals('ONLINE', $center->municipality_city);

        // Clean up temp file
        @unlink($tempPath);
    }

    public function test_import_does_not_leak_status_values_into_identity_columns(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Row 1: merged group headers (identical to the real complaint file)
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'No.');
        $sheet->mergeCells('B1:F1');
        $sheet->setCellValue('B1', 'CENTER DETAILS');
        $sheet->mergeCells('G1:I1');
        $sheet->setCellValue('G1', 'GPS Coordinates');
        $sheet->mergeCells('J1:M1');
        $sheet->setCellValue('J1', 'Date Established');
        $sheet->mergeCells('N1:P1');
        $sheet->setCellValue('N1', 'TCMS');
        $sheet->mergeCells('Q1:Q2');
        $sheet->setCellValue('Q1', 'ODK Status');
        $sheet->mergeCells('R1:R2');
        $sheet->setCellValue('R1', 'Connectivity Status');
        $sheet->mergeCells('S1:S2');
        $sheet->setCellValue('S1', 'TYPE OF CENTER HOST');
        $sheet->mergeCells('T1:T2');
        $sheet->setCellValue('T1', 'Operational Status');

        // Row 2: field-name headers — note columns M ("Status") and P ("Status")
        // share the exact same header text.
        $sheet->setCellValue('B2', 'Congressional District');
        $sheet->setCellValue('C2', 'Province');
        $sheet->setCellValue('D2', 'Municipality/City');
        $sheet->setCellValue('E2', 'Barangay');
        $sheet->setCellValue('F2', 'Center Name');
        $sheet->setCellValue('G2', 'Longitude');
        $sheet->setCellValue('H2', 'Latitude');
        $sheet->setCellValue('I2', 'Verified');
        $sheet->setCellValue('J2', 'MOA Date of Signing');
        $sheet->setCellValue('K2', 'Date of Launching');
        $sheet->setCellValue('L2', 'Date of Platform Registration');
        $sheet->setCellValue('M2', 'Status');
        $sheet->setCellValue('N2', 'Key');
        $sheet->setCellValue('O2', 'Identifier');
        $sheet->setCellValue('P2', 'Status');
        $sheet->setCellValue('Q2', 'ODK Status');
        $sheet->setCellValue('R2', 'Connectivity Status');
        $sheet->setCellValue('S2', 'TYPE OF CENTER HOST');
        $sheet->setCellValue('T2', 'Operational Status');

        // Row 3+: data rows with status-like values that must NOT leak
        // into province/municipality/center_name. The "No." column holds
        // row-position numbers that must not leak either.
        $dataRows = [
            ['1', '1st District', 'Surigao del Norte', 'Surigao City', 'Barangay Uno', 'Surigao Tech Hub', 120.605522, 16.575633, 'YES', '2023-01-15', '2023-06-20', '2023-09-01', 'Established', 'KEY-1', 'ID-1', 'For Scheduling', 'TRUE', 'ONLINE', 'LGU', 'OPERATIONAL'],
            ['2', '1st District', 'Surigao del Norte', 'Mainit', 'Poblacion', 'Mainit Tech Hub', 125.600000, 9.332500, 'NO', '2023-02-01', '2023-07-10', '2023-10-01', 'Established', 'KEY-2', 'ID-2', 'Live', 'FALSE', 'OFFLINE', 'DICT', 'NON-OPERATIONAL'],
        ];
        foreach ($dataRows as $ri => $row) {
            $excelRow = $ri + 3;
            foreach ($row as $ci => $val) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
                $sheet->setCellValue("{$colLetter}{$excelRow}", $val);
            }
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'center_regression_') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempPath);

        $file = new \Illuminate\Http\UploadedFile($tempPath, 'centers_regression.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        if (session('error')) {
            $this->fail('Import error: ' . session('error'));
        }
        $response->assertSessionHas('success');

        $this->assertSame(2, DtcCenterInventory::count());

        $centers = DtcCenterInventory::orderBy('id')->get();

        foreach ($centers as $c) {
            // No-numbers or status header text may never land in identity columns
            $this->assertMatchesRegularExpression('/[a-zA-Z]/', $c->center_name, 'Center Name must be a real name, not a row number');
            $this->assertMatchesRegularExpression('/[a-zA-Z]/', $c->municipality_city, 'Municipality must be a real municipality, not a row number');
            $this->assertNotEquals('OPERATIONAL', strtoupper($c->province ?? ''));
            $this->assertNotEquals('ONLINE', strtoupper($c->province ?? ''));
            $this->assertNotEquals('OPERATIONAL', strtoupper($c->municipality_city ?? ''));
            $this->assertNotEquals('ONLINE', strtoupper($c->municipality_city ?? ''));
            $this->assertNotEquals('OPERATIONAL', strtoupper($c->center_name ?? ''));
            $this->assertNotEquals('ONLINE', strtoupper($c->center_name ?? ''));
        }

        // Center 1: real identity data + two distinct Status columns (M vs P)
        $this->assertDatabaseHas('dtc_center_inventories', [
            'province' => 'Surigao del Norte',
            'municipality_city' => 'Surigao City',
            'barangay' => 'Barangay Uno',
            'center_name' => 'Surigao Tech Hub',
            'tcms_status' => 'Established',                        // Column M
            'tcms_verification_status' => 'For Scheduling',        // Column P
            'odk_status' => 'TRUE',
            'connectivity_status' => 'ONLINE',
            'operational_status' => 'OPERATIONAL',
        ]);

        // Center 2: same separation, values differ to prove no mix-up
        $this->assertDatabaseHas('dtc_center_inventories', [
            'province' => 'Surigao del Norte',
            'municipality_city' => 'Mainit',
            'barangay' => 'Poblacion',
            'center_name' => 'Mainit Tech Hub',
            'tcms_status' => 'Established',                        // Column M
            'tcms_verification_status' => 'Live',                 // Column P
            'odk_status' => 'FALSE',
            'connectivity_status' => 'OFFLINE',
            'operational_status' => 'NON-OPERATIONAL',
        ]);

        @unlink($tempPath);
    }

    public function test_flat_export_csv_roundtrip_imports_correctly(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // This mirrors the CSV our own export produces: flat single header,
        // disambiguated "TCMS Status" / "TCMS Verification Status" names.
        $csvContent = "No.,Congressional District,Province,Municipality/City,Barangay,Center Name,Longitude,Latitude,Verified,MOA Date of Signing,Date of Launching,Date of Platform Registration,TCMS Status,TCMS Key,TCMS Identifier,TCMS Verification Status,ODK Status,Connectivity Status,Type of Center Host,Operational Status\n"
                    . "1,1st District,Surigao del Norte,Surigao City,Washington,Surigao Tech Hub,120.605522,16.575633,Yes,2023-01-15,2023-06-20,2023-09-01,Established,KEY-1,ID-1,For Scheduling,TRUE,ONLINE,LGU,OPERATIONAL\n";

        $file = UploadedFile::fake()->createWithContent('centers_export.csv', $csvContent);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        if (session('error')) {
            $this->fail('Import error: ' . session('error'));
        }
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'congressional_district' => '1st District',
            'province' => 'Surigao del Norte',
            'municipality_city' => 'Surigao City',
            'barangay' => 'Washington',
            'center_name' => 'Surigao Tech Hub',
            'verified' => true,
            'tcms_status' => 'Established',
            'tcms_key' => 'KEY-1',
            'tcms_identifier' => 'ID-1',
            'tcms_verification_status' => 'For Scheduling',
            'odk_status' => 'TRUE',
            'connectivity_status' => 'ONLINE',
            'type_of_center_host' => 'LGU',
            'operational_status' => 'OPERATIONAL',
        ]);

        // The "No." column value (1) must NOT appear in any identity field
        $center = DtcCenterInventory::first();
        $this->assertNotEquals('1', $center->center_name);
        $this->assertNotEquals('1', $center->municipality_city);
        $this->assertMatchesRegularExpression('/[a-zA-Z]/', $center->center_name);
    }

    public function test_roundtrip_import_of_flat_xlsx_export(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // Build an XLSX that matches the new flat single-header export format.
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => 'No.',
            'B' => 'Congressional District',
            'C' => 'Province',
            'D' => 'Municipality/City',
            'E' => 'Barangay',
            'F' => 'Center Name',
            'G' => 'Longitude',
            'H' => 'Latitude',
            'I' => 'Verified',
            'J' => 'MOA Date of Signing',
            'K' => 'Date of Launching',
            'L' => 'Date of Platform Registration',
            'M' => 'TCMS Status',
            'N' => 'TCMS Key',
            'O' => 'TCMS Identifier',
            'P' => 'TCMS Verification Status',
            'Q' => 'ODK Status',
            'R' => 'Connectivity Status',
            'S' => 'Type of Center Host',
            'T' => 'Operational Status',
        ];
        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}1", $label);
        }

        $vals = [
            'A' => 1, 'B' => '1st District', 'C' => 'Surigao del Norte', 'D' => 'Surigao City',
            'E' => 'Washington', 'F' => 'Surigao Tech Hub', 'G' => 120.605522, 'H' => 16.575633,
            'I' => 'Yes', 'J' => '2023-01-15', 'K' => '2023-06-20', 'L' => '2023-09-01',
            'M' => 'Established', 'N' => 'KEY-1', 'O' => 'ID-1', 'P' => 'For Scheduling',
            'Q' => 'TRUE', 'R' => 'ONLINE', 'S' => 'LGU', 'T' => 'OPERATIONAL',
        ];
        foreach ($vals as $col => $val) {
            $sheet->setCellValue("{$col}2", $val);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'center_flat_') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempPath);

        $file = new \Illuminate\Http\UploadedFile($tempPath, 'centers_flat.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        if (session('error')) {
            $this->fail('Import error: ' . session('error'));
        }
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dtc_center_inventories', [
            'province' => 'Surigao del Norte',
            'municipality_city' => 'Surigao City',
            'center_name' => 'Surigao Tech Hub',
            'verified' => true,
            'tcms_status' => 'Established',
            'tcms_key' => 'KEY-1',
            'tcms_identifier' => 'ID-1',
            'tcms_verification_status' => 'For Scheduling',
            'odk_status' => 'TRUE',
            'connectivity_status' => 'ONLINE',
            'type_of_center_host' => 'LGU',
            'operational_status' => 'OPERATIONAL',
        ]);

        @unlink($tempPath);
    }

    public function test_center_inventory_per_page_filter(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        DtcCenterInventory::create([
            'municipality_city' => 'Surigao City',
            'center_name' => 'Center 1',
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->get(route('dtc.centers.index', ['per_page' => 50]));

        $response->assertStatus(200);
        $response->assertViewHas('centers', function ($centers) {
            return $centers->perPage() === 50;
        });
    }

    public function test_center_inventory_batch_delete(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $c1 = DtcCenterInventory::create([
            'municipality_city' => 'Surigao City',
            'center_name' => 'Center 1',
        ]);
        $c2 = DtcCenterInventory::create([
            'municipality_city' => 'Mainit',
            'center_name' => 'Center 2',
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.centers.batchDelete'), [
                'ids' => [$c1->id, $c2->id],
            ]);

        $response->assertRedirect(route('dtc.centers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('dtc_center_inventories', ['id' => $c1->id]);
        $this->assertDatabaseMissing('dtc_center_inventories', ['id' => $c2->id]);
    }
}

