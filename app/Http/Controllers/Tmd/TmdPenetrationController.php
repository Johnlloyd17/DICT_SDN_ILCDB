<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\TmdPenetration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TmdPenetrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'municipality' => 'required|string|max:100|unique:tmd_penetration,municipality',
            'male' => 'required|integer|min:0',
            'female' => 'required|integer|min:0',
        ]);

        $record = TmdPenetration::create([
            'municipality' => $request->municipality,
            'male' => $request->male,
            'female' => $request->female,
            'total' => $request->male + $request->female,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['record' => $record], 201);
        }

        return redirect()->route('tmd.participants.index')->withFragment('penetration')
            ->with('success', 'Penetration record added successfully.');
    }

    public function update(Request $request, TmdPenetration $penetration)
    {
        $request->validate([
            'municipality' => 'required|string|max:100|unique:tmd_penetration,municipality,'.$penetration->id,
            'male' => 'required|integer|min:0',
            'female' => 'required|integer|min:0',
        ]);

        $penetration->update([
            'municipality' => $request->municipality,
            'male' => $request->male,
            'female' => $request->female,
            'total' => $request->male + $request->female,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['record' => $penetration->fresh()]);
        }

        return redirect()->route('tmd.participants.index')->withFragment('penetration')
            ->with('success', 'Penetration record updated successfully.');
    }

    public function previewDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $preview = TmdPenetration::whereIn('id', $request->ids)->get()->map(fn (TmdPenetration $row) => [
            'id' => $row->id,
            'municipality' => $row->municipality,
            'male' => $row->male,
            'female' => $row->female,
            'total' => $row->total,
            'participants' => Participant::where('municipality', $row->municipality)->count(),
        ])->values();

        return response()->json(['preview' => $preview]);
    }

    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $rows = TmdPenetration::whereIn('id', $request->ids)->get();
        $deleted = [];
        $skipped = [];
        $totalParticipants = 0;

        foreach ($rows as $row) {
            try {
                $participants = Participant::where('municipality', $row->municipality)->get();
                foreach ($participants as $participant) {
                    if ($participant->certificate_file) {
                        Storage::disk('public')->delete($participant->certificate_file);
                    }
                    $participant->delete();
                }
                $totalParticipants += $participants->count();
                $deleted[] = [
                    'id' => $row->id,
                    'municipality' => $row->municipality,
                    'male' => $row->male,
                    'female' => $row->female,
                    'total' => $row->total,
                    'participantsDeleted' => $participants->count(),
                ];
                $row->delete();
            } catch (\Throwable $e) {
                $skipped[] = ['id' => $row->id, 'label' => $row->municipality, 'reason' => 'Could not delete (database error).'];
            }
        }

        $message = 'Permanently deleted '.count($deleted).' penetration summary row(s) and '.$totalParticipants.' participant record(s).';
        if (count($skipped) > 0) {
            $message .= ' '.count($skipped).' skipped.';
        }

        return response()->json(['deleted' => $deleted, 'skipped' => $skipped, 'message' => $message]);
    }
}
