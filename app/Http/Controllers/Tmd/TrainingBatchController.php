<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingBatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'target_count' => 'required|integer|min:0',
            'enrolled_count' => 'required|integer|min:0',
            'trainer_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
        ]);

        $batch = TrainingBatch::create([
            'batch_code' => $this->nextBatchCode(),
            'course_title' => $request->course_title,
            'venue' => $request->venue,
            'target_count' => $request->target_count,
            'enrolled_count' => $request->enrolled_count,
            'trainer_name' => $request->trainer_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'program' => 'TMD',
            'status' => $request->status,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['batch' => $batch], 201);
        }

        return redirect()->route('tmd.participants.index')->withFragment('tracker')
            ->with('success', 'Training batch added successfully.');
    }

    public function update(Request $request, TrainingBatch $batch)
    {
        $request->validate([
            'course_title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'target_count' => 'required|integer|min:0',
            'enrolled_count' => 'required|integer|min:0',
            'trainer_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
        ]);

        $batch->update([
            'course_title' => $request->course_title,
            'venue' => $request->venue,
            'target_count' => $request->target_count,
            'enrolled_count' => $request->enrolled_count,
            'trainer_name' => $request->trainer_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['batch' => $batch->fresh()]);
        }

        return redirect()->route('tmd.participants.index')->withFragment('tracker')
            ->with('success', 'Training batch updated successfully.');
    }

    protected function nextBatchCode(): string
    {
        $last = TrainingBatch::where('program', 'TMD')->orderByDesc('id')->value('batch_code');
        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        $code = 'TMD-SDN-'.date('Y').'-'.str_pad($seq, 3, '0', STR_PAD_LEFT);
        while (TrainingBatch::where('batch_code', $code)->exists()) {
            $seq++;
            $code = 'TMD-SDN-'.date('Y').'-'.str_pad($seq, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function destroy(TrainingBatch $batch)
    {
        foreach ($batch->participants as $participant) {
            if ($participant->certificate_file) {
                Storage::disk('public')->delete($participant->certificate_file);
            }
        }

        $participantCount = $batch->participants()->count();
        $batch->delete();

        $message = $participantCount > 0
            ? "Batch removed (including {$participantCount} enrolled participant(s))."
            : 'Batch removed.';

        if (request()->wantsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('tmd.participants.index')->withFragment('tracker')
            ->with('success', $message);
    }

    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $ids = array_values(array_unique($request->ids));
        $deleted = 0;
        $skipped = [];

        foreach ($ids as $id) {
            $batch = TrainingBatch::find($id);
            if (! $batch) {
                $skipped[] = ['id' => $id, 'label' => "ID {$id}", 'reason' => 'Training batch not found.'];

                continue;
            }

            try {
                $participantCount = $batch->participants()->count();
                foreach ($batch->participants as $participant) {
                    if ($participant->certificate_file) {
                        Storage::disk('public')->delete($participant->certificate_file);
                    }
                }
                $batch->delete();
                $deleted++;
            } catch (\Throwable $e) {
                $skipped[] = ['id' => $batch->id, 'label' => $batch->batch_code, 'reason' => $e->getMessage()];
            }
        }

        $message = "Successfully deleted {$deleted} training batch(es).";
        if (! empty($skipped)) {
            $message .= ' '.count($skipped).' skipped.';
        }

        if ($request->wantsJson()) {
            return response()->json(compact('message', 'deleted', 'skipped'));
        }

        return redirect()->route('tmd.participants.index')->withFragment('tracker')
            ->with('success', $message);
    }
}
