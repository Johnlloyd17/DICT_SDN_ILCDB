<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;

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

    protected function nextBatchCode(): string
    {
        $last = TrainingBatch::where('program', 'TMD')->orderByDesc('id')->value('batch_code');
        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        $code = 'TMD-SDN-' . date('Y') . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
        while (TrainingBatch::where('batch_code', $code)->exists()) {
            $seq++;
            $code = 'TMD-SDN-' . date('Y') . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}
