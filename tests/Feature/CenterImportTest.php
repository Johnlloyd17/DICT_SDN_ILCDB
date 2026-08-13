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

