<?php

namespace Tests\Feature;

use App\Models\DtcHub;
use App\Models\DtcService;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Database\Seeders\DtcHubSeeder;
use Database\Seeders\DtcServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DtcVisitorRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    protected function seedDtc(): void
    {
        $this->seed([DtcHubSeeder::class, DtcServiceSeeder::class]);
    }

    public function test_dashboard_renders_and_service_tab_has_services(): void
    {
        $this->seedDtc();
        $response = $this->actingAs($this->user())->get(route('dtc.visitors.index'));
        $response->assertStatus(200);
        $response->assertSee('Free High-Speed Internet');
    }

    public function test_add_new_visitor_creates_visit_and_visit_services(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc1 = DtcService::where('dtc_hub_id', $hub->id)->first();
        $svc2 = DtcService::where('dtc_hub_id', $hub->id)->skip(1)->first();

        $response = $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'Verification Test Visitor',
                'contact_number' => '09998887777',
                'gender' => 'Male',
                'age' => 28,
                'demographic_sector' => 'Student / Youth',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc1->service_name, $svc2->service_name],
                'purpose_of_visit' => 'Print documents',
                'check_in_time' => '2026-09-07T10:00',
                'check_out_time' => '2026-09-07T11:30',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJsonPath('visitor.visitor_name', 'Verification Test Visitor');
        $response->assertJsonPath('visitor.status', 'Completed');
        $response->assertJsonCount(2, 'visitor.services_ailed');
        $response->assertJsonPath('visitor.session_duration', '1 hr 30 mins');

        $this->assertDatabaseHas('visitors', ['name' => 'Verification Test Visitor']);
        $this->assertDatabaseCount('visits', 1);
        $this->assertDatabaseCount('visit_services', 2);

        $visit = Visit::first();
        $this->assertMatchesRegularExpression('/^DTC-VIS-\d{4}-\d{3}$/', $visit->visit_code);
    }

    public function test_returning_visitor_dedup_does_not_create_duplicate(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc = DtcService::where('dtc_hub_id', $hub->id)->first();

        $payload = [
            'visitor_name' => 'Returning Citizen',
            'contact_number' => '09111111111',
            'gender' => 'Female',
            'age' => 35,
            'demographic_sector' => 'MSME / Freelancer',
            'dtc_hub_id' => $hub->id,
            'services' => [$svc->service_name],
        ];

        $this->actingAs($this->user())->post(route('dtc.visitors.store'), $payload, ['Accept' => 'application/json'])->assertStatus(201);
        $this->actingAs($this->user())->post(route('dtc.visitors.store'), $payload, ['Accept' => 'application/json'])->assertStatus(201);

        $this->assertDatabaseCount('visitors', 1);
        $this->assertDatabaseCount('visits', 2);
    }

    public function test_edit_visit_updates_fields_and_services(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $all = DtcService::where('dtc_hub_id', $hub->id)->get();
        $svc1 = $all[0];
        $svc2 = $all[1];
        $svc3 = $all[2];

        $store = $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'Editable Person',
                'contact_number' => '09222222222',
                'gender' => 'Male',
                'age' => 42,
                'demographic_sector' => 'LGU / Govt Employee',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc1->service_name, $svc2->service_name],
            ], ['Accept' => 'application/json']);
        $store->assertStatus(201);
        $visitId = $store->json('visitor.id');

        $update = $this->actingAs($this->user())
            ->put(route('dtc.visitors.update', $visitId), [
                'visitor_name' => 'Editable Person',
                'contact_number' => '09222222222',
                'gender' => 'Male',
                'age' => 43,
                'demographic_sector' => 'LGU / Govt Employee',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc3->service_name],
                'purpose_of_visit' => 'Updated purpose',
                'status' => 'Completed',
            ], ['Accept' => 'application/json']);

        $update->assertStatus(200);
        $update->assertJsonPath('visitor.age', 43);
        $update->assertJsonPath('visitor.status', 'Completed');
        $update->assertJsonCount(1, 'visitor.services_ailed');

        $this->assertDatabaseCount('visit_services', 1);
        $this->assertDatabaseHas('visit_services', ['visit_id' => $visitId, 'service_id' => $svc3->id]);
    }

    public function test_cancel_visit_status(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc = DtcService::where('dtc_hub_id', $hub->id)->first();

        $store = $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'Cancel Me',
                'contact_number' => '09333333333',
                'gender' => 'Female',
                'age' => 50,
                'demographic_sector' => 'Senior Citizen / PWD',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc->service_name],
            ], ['Accept' => 'application/json']);
        $store->assertStatus(201);
        $visitId = $store->json('visitor.id');

        $cancel = $this->actingAs($this->user())
            ->put(route('dtc.visitors.update', $visitId), [
                'visitor_name' => 'Cancel Me',
                'contact_number' => '09333333333',
                'gender' => 'Female',
                'age' => 50,
                'demographic_sector' => 'Senior Citizen / PWD',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc->service_name],
                'status' => 'Cancelled',
            ], ['Accept' => 'application/json']);
        $cancel->assertJsonPath('visitor.status', 'Cancelled');
        $this->assertDatabaseHas('visits', ['id' => $visitId, 'status' => 'Cancelled']);
    }

    public function test_import_csv_with_new_template(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();

        $csv = "Visitor Name,Contact Number,Gender,Age,Demographic Sector,DTC Hub,Services,Purpose of Visit,Visit Date\n"
             . "Imported Citizen,09444444444,Male,30,Student / Youth,{$hub->name},Free High-Speed Internet; Printing & Document Scanning,Apply online,2026-09-01 09:00:00\n";

        $file = UploadedFile::fake()->createWithContent('visitors.csv', $csv);

        $response = $this->actingAs($this->user())
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->post(route('dtc.visitors.import'), ['file' => $file]);

        $response->assertRedirect(route('dtc.visitors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('visitors', ['name' => 'Imported Citizen']);
        $this->assertDatabaseCount('visits', 1);
        $this->assertDatabaseCount('visit_services', 2);

        $visit = Visit::with('visitor')->first();
        $this->assertSame('Imported Citizen', $visit->visitor->name);
    }

    public function test_export_dtc_visitors_csv_and_template(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc = DtcService::where('dtc_hub_id', $hub->id)->first();

        $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'Export Me',
                'contact_number' => '09555555555',
                'gender' => 'Female',
                'age' => 22,
                'demographic_sector' => 'Jobseeker / Out-of-School Youth',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc->service_name],
            ], ['Accept' => 'application/json'])->assertStatus(201);

        $template = $this->actingAs($this->user())
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->get(route('export.template', 'dtc-visitors'));
        $template->assertStatus(200);
        $template->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $templateBody = $template->streamedContent();
        foreach (['Visitor Name', 'Contact Number', 'Gender', 'Age', 'Demographic Sector', 'DTC Hub', 'Services', 'Purpose of Visit', 'Visit Date'] as $col) {
            $this->assertStringContainsString($col, $templateBody);
        }

        $csv = $this->actingAs($this->user())
            ->withoutMiddleware(\App\Http\Middleware\EnsureEmailIsVerified::class)
            ->get(route('export.csv', 'dtc-visitors'));
        $csv->assertStatus(200);
        $this->assertStringContainsString('Export Me', $csv->streamedContent());
        $this->assertStringContainsString($hub->name, $csv->streamedContent());
    }

    public function test_api_endpoints_returnChartData(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc = DtcService::where('dtc_hub_id', $hub->id)->first();

        $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'Chart Person',
                'contact_number' => '09666666666',
                'gender' => 'Male',
                'age' => 29,
                'demographic_sector' => 'Student / Youth',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc->service_name],
            ], ['Accept' => 'application/json'])->assertStatus(201);

        $vis = $this->actingAs($this->user())->get(route('api.dtc.visitors'))->assertStatus(200)->json();
        $this->assertArrayHasKey('demographic_sector', $vis[0]);

        $traffic = $this->actingAs($this->user())->get(route('api.dtc.traffic', ['year' => date('Y')]))->assertStatus(200)->json();
        $this->assertArrayHasKey(date('n'), $traffic);

        $services = $this->actingAs($this->user())->get(route('api.dtc.services'))->assertStatus(200)->json();
        $this->assertArrayHasKey($svc->service_name, $services);
    }

    public function test_sdn_pdi_page_renders_and_tracks_foot_traffic(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();
        $svc = DtcService::where('dtc_hub_id', $hub->id)->first();

        $this->actingAs($this->user())
            ->post(route('dtc.visitors.store'), [
                'visitor_name' => 'SDN Visitor',
                'contact_number' => '09777777777',
                'gender' => 'Male',
                'age' => 31,
                'demographic_sector' => 'MSME / Freelancer',
                'dtc_hub_id' => $hub->id,
                'services' => [$svc->service_name],
            ], ['Accept' => 'application/json'])->assertStatus(201);

        $response = $this->actingAs($this->user())->get(route('sdn-pdi.index'));
        $response->assertStatus(200);
    }

    public function test_service_crud(): void
    {
        $this->seedDtc();
        $hub = DtcHub::first();

        $store = $this->actingAs($this->user())
            ->post(route('dtc.services.store'), [
                'dtc_hub_id' => $hub->id,
                'service_name' => 'Verification New Service',
                'category' => 'Productivity',
                'is_active' => true,
            ])->assertStatus(302);

        $service = DtcService::where('service_name', 'Verification New Service')->first();
        $this->assertNotNull($service);

        $this->actingAs($this->user())
            ->put(route('dtc.services.update', $service->id), [
                'dtc_hub_id' => $hub->id,
                'service_name' => 'Verification New Service Renamed',
                'category' => 'Support',
                'is_active' => false,
            ])->assertRedirect();

        $this->assertDatabaseHas('dtc_services', ['service_name' => 'Verification New Service Renamed', 'is_active' => false]);

        $this->actingAs($this->user())
            ->delete(route('dtc.services.destroy', $service->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('dtc_services', ['id' => $service->id]);
    }

    public function test_centers_tab_receives_all_centers_for_client_side_pagination(): void
    {
        \App\Models\DtcCenterInventory::truncate();

        for ($i = 1; $i <= 65; $i++) {
            \App\Models\DtcCenterInventory::create([
                'municipality_city' => $i % 2 === 0 ? 'Surigao City' : 'Mainit',
                'center_name' => "Test Center {$i}",
                'barangay' => "Barangay {$i}",
            ]);
        }

        $response = $this->actingAs($this->user())
            ->get(route('dtc.visitors.index', ['view' => 'centers']));

        $response->assertStatus(200);
        $response->assertViewHas('totalCenters', 65);

        // The client-side table needs the FULL dataset in the view, not just
        // the server's default 15-row page (regression for "showing 15 of 65").
        $response->assertViewHas('centers', function ($centers) {
            return $centers->count() === 65;
        });

        // KPI card must say 65
        $response->assertSee('65', false);
    }

    public function test_dashboard_widget_receives_all_sdn_centers_for_client_side_pagination(): void
    {
        \App\Models\DtcCenterInventory::truncate();

        for ($i = 1; $i <= 65; $i++) {
            \App\Models\DtcCenterInventory::create([
                'municipality_city' => $i % 2 === 0 ? 'Surigao City' : 'Mainit',
                'center_name' => "Test Center {$i}",
                'barangay' => "Barangay {$i}",
            ]);
        }

        $response = $this->actingAs($this->user())
            ->get(route('dtc.visitors.index', ['view' => 'dashboard']));

        $response->assertStatus(200);
        $response->assertViewHas('totalCenters', 65);

        // Dashboard-tab "DTC Center Inventory" widget is client-side and needs
        // the FULL dataset, not the server's default 10-row sdn page (regression
        // for "showing 1-10 of 10" while Total Centers is 65).
        $response->assertViewHas('sdnCenters', function ($centers) {
            return $centers->count() === 65;
        });
    }
}