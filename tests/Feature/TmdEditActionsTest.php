<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Models\TmdPenetration;
use App\Models\TrainingBatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TmdEditActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    protected function makeBatch(array $overrides = []): TrainingBatch
    {
        return TrainingBatch::create(array_merge([
            'batch_code' => 'TMD-SDN-2026-'.str_pad((string) (TrainingBatch::count() + 1), 3, '0', STR_PAD_LEFT),
            'course_title' => 'Cybersecurity Fundamentals',
            'venue' => 'DICT SDN Regional Office',
            'target_count' => 20,
            'enrolled_count' => 5,
            'trainer_name' => 'Maria S. Santos',
            'start_date' => '2026-06-01',
            'end_date' => '2026-07-15',
            'program' => 'TMD',
            'status' => 'Upcoming',
        ], $overrides));
    }

    public function test_batch_update_modifies_existing_record_without_duplicate(): void
    {
        $batch = $this->makeBatch();

        $response = $this->actingAs($this->user())
            ->put(route('tmd.batches.update', $batch->id), [
                'course_title' => 'Advanced Cybersecurity Operations',
                'venue' => 'Surigao City DTC Main Hub',
                'target_count' => 30,
                'enrolled_count' => 12,
                'trainer_name' => 'Dr. Ramon Reyes',
                'start_date' => '2026-08-01',
                'end_date' => '2026-09-20',
                'status' => 'Ongoing',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonPath('batch.id', $batch->id);
        $response->assertJsonPath('batch.course_title', 'Advanced Cybersecurity Operations');
        $response->assertJsonPath('batch.status', 'Ongoing');

        $this->assertDatabaseCount('training_batches', 1);
        $this->assertDatabaseHas('training_batches', [
            'id' => $batch->id,
            'course_title' => 'Advanced Cybersecurity Operations',
            'venue' => 'Surigao City DTC Main Hub',
            'enrolled_count' => 12,
            'status' => 'Ongoing',
        ]);

        $updated = $batch->fresh();
        $this->assertSame('2026-08-01', $updated->start_date->toDateString());
        $this->assertSame('2026-09-20', $updated->end_date->toDateString());
    }

    public function test_batch_update_rejects_invalid_status(): void
    {
        $batch = $this->makeBatch();

        $this->actingAs($this->user())
            ->put(route('tmd.batches.update', $batch->id), [
                'course_title' => 'X',
                'venue' => 'Y',
                'target_count' => 5,
                'enrolled_count' => 1,
                'trainer_name' => 'Z',
                'start_date' => '2026-08-01',
                'end_date' => '2026-09-20',
                'status' => 'Bogus',
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->assertDatabaseHas('training_batches', ['course_title' => 'Cybersecurity Fundamentals']);
        $this->assertDatabaseCount('training_batches', 1);
    }

    public function test_participant_update_returns_json_and_does_not_duplicate(): void
    {
        $batch = $this->makeBatch();

        $participant = Participant::create([
            'participant_code' => 'TMD-2026-001',
            'full_name' => 'Original Name',
            'training_batch_id' => $batch->id,
            'municipality' => 'Surigao City',
            'agency_sector' => 'LGU Mainit',
            'completion_status' => 'Pending',
            'completion_date' => null,
        ]);

        $response = $this->actingAs($this->user())
            ->put(route('tmd.participants.update', $participant->id), [
                'full_name' => 'Edited Name',
                'training_batch_id' => $batch->id,
                'municipality' => 'Mainit',
                'agency_sector' => 'DICT Scholar',
                'completion_status' => 'Completed',
                'completion_date' => '2026-09-01',
            ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonPath('participant.id', $participant->id);
        $response->assertJsonPath('participant.full_name', 'Edited Name');
        $response->assertJsonPath('participant.completion_status', 'Completed');

        $this->assertDatabaseCount('participants', 1);
        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'full_name' => 'Edited Name',
            'municipality' => 'Mainit',
            'completion_status' => 'Completed',
        ]);
        $this->assertSame('2026-09-01', $participant->fresh()->completion_date->toDateString());
    }

    public function test_participant_update_assigns_completion_date_when_completed_and_blank(): void
    {
        $batch = $this->makeBatch();

        $participant = Participant::create([
            'participant_code' => 'TMD-2026-002',
            'full_name' => 'Auto Date',
            'training_batch_id' => $batch->id,
            'municipality' => 'Claver',
            'agency_sector' => 'SK Council',
            'completion_status' => 'Ongoing',
            'completion_date' => null,
        ]);

        $this->actingAs($this->user())
            ->put(route('tmd.participants.update', $participant->id), [
                'full_name' => 'Auto Date',
                'training_batch_id' => $batch->id,
                'municipality' => 'Claver',
                'agency_sector' => 'SK Council',
                'completion_status' => 'Completed',
                'completion_date' => '',
            ], ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'completion_status' => 'Completed',
        ]);
        $this->assertSame(now()->toDateString(), $participant->fresh()->completion_date->toDateString());
    }

    public function test_penetration_update_recomputes_total_without_duplicate(): void
    {
        $record = TmdPenetration::create([
            'municipality' => 'Surigao City',
            'male' => 2,
            'female' => 3,
            'total' => 5,
        ]);

        $response = $this->actingAs($this->user())
            ->put(route('tmd.penetration.update', $record->id), [
                'municipality' => 'Surigao City',
                'male' => 6,
                'female' => 4,
            ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonPath('record.id', $record->id);
        $response->assertJsonPath('record.total', 10);

        $this->assertDatabaseCount('tmd_penetration', 1);
        $this->assertDatabaseHas('tmd_penetration', ['id' => $record->id, 'male' => 6, 'female' => 4, 'total' => 10]);
    }

    public function test_penetration_update_rejects_duplicate_municipality(): void
    {
        TmdPenetration::create(['municipality' => 'Surigao City', 'male' => 1, 'female' => 1, 'total' => 2]);
        $second = TmdPenetration::create(['municipality' => 'Mainit', 'male' => 1, 'female' => 1, 'total' => 2]);

        $this->actingAs($this->user())
            ->put(route('tmd.penetration.update', $second->id), [
                'municipality' => 'Surigao City',
                'male' => 2,
                'female' => 2,
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->assertDatabaseHas('tmd_penetration', ['id' => $second->id, 'municipality' => 'Mainit']);
    }

    public function test_index_controller_passes_edit_batch_options_including_referenced_batches(): void
    {
        $target = $this->makeBatch();
        // A batch not in the Upcoming/Ongoing dropdown, but referenced by a participant,
        // must still be offered in the edit dropdown so prefilled value never breaks.
        $older = $this->makeBatch([
            'batch_code' => 'TMD-SDN-2025-999',
            'start_date' => '2025-03-01',
            'end_date' => '2025-04-30',
            'status' => 'Completed',
        ]);
        Participant::create([
            'participant_code' => 'TMD-2025-999',
            'full_name' => 'Legacy Participant',
            'training_batch_id' => $older->id,
            'municipality' => 'Claver',
            'agency_sector' => 'LGU',
            'completion_status' => 'Completed',
            'completion_date' => '2025-04-30',
        ]);

        $controller = app(\App\Http\Controllers\Tmd\ParticipantController::class);
        $response = $controller->index(Request::create('/tmd/participants'));
        $data = $response->getData();

        $options = collect($data['editBatchOptions']);
        $this->assertTrue($options->contains(fn ($o) => $o['id'] === $target->id));
        $this->assertTrue($options->contains(fn ($o) => $o['id'] === $older->id));
    }

    public function test_index_blade_contains_all_three_edit_forms(): void
    {
        $blade = file_get_contents(resource_path('views/tmd/participants/index.blade.php'));

        $this->assertStringContainsString('edit-participant', $blade);
        $this->assertStringContainsString('edit-batch', $blade);
        $this->assertStringContainsString('edit-penetration', $blade);
        $this->assertStringContainsString('editParticipantForm', $blade);
        $this->assertStringContainsString('editBatchForm', $blade);
        $this->assertStringContainsString('editPenetrationForm', $blade);
        $this->assertStringContainsString("participant-updated", $blade);
        $this->assertStringContainsString("batch-updated", $blade);
        $this->assertStringContainsString("penetration-updated", $blade);
    }
}