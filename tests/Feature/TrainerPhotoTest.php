<?php

namespace Tests\Feature;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainerPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    protected function trainer(): Trainer
    {
        return Trainer::create([
            'full_name' => 'Test Trainer',
            'specialty' => 'Cybersecurity',
            'status' => 'Active',
        ]);
    }

    public function test_trainer_profile_photo_can_be_uploaded(): void
    {
        Storage::fake('public');

        $trainer = $this->trainer();

        $response = $this->actingAs($this->user())->postJson("/tmd/trainers/{$trainer->id}/photo", [
            'profile_image' => UploadedFile::fake()->image('photo.png', 100, 100)->size(100),
        ]);

        $response->assertOk();

        $stored = $trainer->fresh()->profile_image;
        $this->assertNotNull($stored);
        $this->assertStringStartsWith('trainer-photos/', $stored);
        Storage::disk('public')->assertExists($stored);
        $this->assertSame($stored, $response->json('trainer.profile_image'));
    }

    public function test_trainer_profile_photo_rejects_invalid_type(): void
    {
        Storage::fake('public');

        $trainer = $this->trainer();

        $response = $this->actingAs($this->user())->postJson("/tmd/trainers/{$trainer->id}/photo", [
            'profile_image' => UploadedFile::fake()->create('photo.gif', 100),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('profile_image');
        $this->assertNull($trainer->fresh()->profile_image);
    }

    public function test_trainer_profile_photo_rejects_oversized_image(): void
    {
        Storage::fake('public');

        $trainer = $this->trainer();

        $response = $this->actingAs($this->user())->postJson("/tmd/trainers/{$trainer->id}/photo", [
            'profile_image' => UploadedFile::fake()->image('photo.png')->size(3000),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('profile_image');
    }

    public function test_trainer_photo_can_be_removed(): void
    {
        Storage::fake('public');

        $trainer = $this->trainer();
        $trainer->update(['profile_image' => 'trainer-photos/photo.png']);
        Storage::disk('public')->put('trainer-photos/photo.png', 'x');

        $response = $this->actingAs($this->user())->deleteJson("/tmd/trainers/{$trainer->id}/photo");

        $response->assertOk()->assertJsonPath('trainer.profile_image', null);
        Storage::disk('public')->assertMissing('trainer-photos/photo.png');
    }

    public function test_deleting_trainer_removes_its_photo(): void
    {
        Storage::fake('public');

        $trainer = $this->trainer();
        $trainer->update(['profile_image' => 'trainer-photos/photo.png']);
        Storage::disk('public')->put('trainer-photos/photo.png', 'x');

        $this->actingAs($this->user())->deleteJson("/tmd/trainers/{$trainer->id}");

        Storage::disk('public')->assertMissing('trainer-photos/photo.png');
        $this->assertNull($trainer->fresh());
    }
}
