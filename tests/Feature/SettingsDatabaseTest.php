<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SettingsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create(['email_verified_at' => now(), 'is_admin' => true]);
    }

    protected function regularUser(): User
    {
        return User::factory()->create(['email_verified_at' => now(), 'is_admin' => false]);
    }

    public function test_guest_is_redirected_to_login()
    {
        $this->get(route('settings.database.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_settings()
    {
        $this->actingAs($this->regularUser())
            ->get(route('settings.database.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_settings()
    {
        $this->actingAs($this->admin())
            ->get(route('settings.database.index'))
            ->assertOk()
            ->assertSee('Export Database')
            ->assertSee('Import Database');
    }

    public function test_non_admin_cannot_trigger_export_or_import()
    {
        $this->actingAs($this->regularUser())
            ->post(route('settings.database.export'))
            ->assertForbidden();

        $this->actingAs($this->regularUser())
            ->post(route('settings.database.import'), [
                'sql_file' => UploadedFile::fake()->create('backup.sql', 5),
                'confirmation' => 'IMPORT',
            ])
            ->assertForbidden();
    }

    public function test_non_sql_file_is_rejected()
    {
        $this->actingAs($this->admin())
            ->post(route('settings.database.import'), [
                'sql_file' => UploadedFile::fake()->create('evil.txt', 10),
                'confirmation' => 'IMPORT',
            ])
            ->assertSessionHas('error');
    }

    public function test_import_requires_typed_confirmation()
    {
        $this->actingAs($this->admin())
            ->post(route('settings.database.import'), [
                'sql_file' => UploadedFile::fake()->create('backup.sql', 5),
                'confirmation' => 'wrong',
            ])
            ->assertSessionHas('error');
    }

    public function test_download_rejects_non_sql_or_missing_files()
    {
        $this->actingAs($this->admin())
            ->get(route('settings.database.download', ['file' => 'nope.txt']))
            ->assertNotFound();

        $this->actingAs($this->admin())
            ->get(route('settings.database.download', ['file' => 'does_not_exist.sql']))
            ->assertNotFound();
    }
}
