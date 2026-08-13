<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Participant;
use App\Models\TmdPenetration;
use App\Models\Trainer;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array((int) $request->input('per_page'), [5, 10, 20, 30, 40, 50, 100, 150, 200])
            ? (int) $request->input('per_page')
            : 5;

        $query = Participant::with('trainingBatch');

        if ($request->filled('batch') && $request->batch !== 'ALL') {
            $query->where('training_batch_id', $request->batch);
        }

        if ($request->filled('cert') && $request->cert !== 'ALL') {
            if ($request->cert === 'Uploaded') {
                $query->whereNotNull('certificate_file');
            } elseif ($request->cert === 'Pending') {
                $query->where('completion_status', 'Pending');
            } elseif ($request->cert === 'Ongoing') {
                $query->where('completion_status', 'Ongoing');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%")
                  ->orWhere('agency_sector', 'like', "%{$search}%")
                  ->orWhere('participant_code', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

        $total = Participant::count();
        $certified = Participant::where('completion_status', 'Completed')->count();
        $uploaded = Participant::whereNotNull('certificate_file')->count();
        $ongoing = Participant::where('completion_status', 'Ongoing')->count();
        $lgus = Participant::distinct('municipality')->count('municipality');
        $completionRate = $total > 0 ? round($certified / $total * 100) : 0;

        $batches = TrainingBatch::where('program', 'TMD')->orderBy('batch_code')->get();
        $penetration = TmdPenetration::orderBy('municipality')->get();
        $trainers = Trainer::orderBy('full_name')->get();

        $batchesAll = TrainingBatch::where('program', 'TMD')->orderByDesc('start_date')->paginate($perPage)->withQueryString();
        $allCourses = Course::orderBy('course_code')->paginate($perPage)->withQueryString();
        $penetrationRows = TmdPenetration::orderBy('municipality')->paginate($perPage)->withQueryString();
        $penetrationGrandMale = TmdPenetration::sum('male');
        $penetrationGrandFemale = TmdPenetration::sum('female');
        $penetrationGrandTotal = TmdPenetration::sum('total');

        return view('tmd.participants.index', compact(
            'participants', 'total', 'certified', 'uploaded', 'ongoing', 'lgus',
            'completionRate', 'batches', 'penetration', 'trainers',
            'batchesAll', 'allCourses', 'penetrationRows',
            'penetrationGrandMale', 'penetrationGrandFemale', 'penetrationGrandTotal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'training_batch_id' => 'required|exists:training_batches,id',
            'municipality' => 'required|string|max:100',
            'agency_sector' => 'required|string|max:255',
            'completion_status' => 'required|in:Completed,Ongoing,Pending',
            'certificate_file' => 'nullable|file|image|max:5120',
        ]);

        $lastId = Participant::max('id') ?? 0;
        $code = 'TMD-' . date('Y') . '-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $data = [
            'participant_code' => $code,
            'full_name' => $request->full_name,
            'training_batch_id' => $request->training_batch_id,
            'municipality' => $request->municipality,
            'agency_sector' => $request->agency_sector,
            'completion_status' => $request->completion_status,
            'completion_date' => $request->completion_status === 'Completed' ? now()->toDateString() : null,
        ];

        if ($request->hasFile('certificate_file')) {
            $data['certificate_file'] = $request->file('certificate_file')->store('certificates', 'public');
        }

        Participant::create($data);

        return redirect()->route('tmd.participants.index')->with('success', 'Participant registered successfully.');
    }

    public function show(Participant $participant)
    {
        $participant->load('trainingBatch');
        return view('tmd.participants.show', compact('participant'));
    }

    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'training_batch_id' => 'required|exists:training_batches,id',
            'municipality' => 'required|string|max:100',
            'agency_sector' => 'required|string|max:255',
            'completion_status' => 'required|in:Completed,Ongoing,Pending',
        ]);

        $participant->update([
            'full_name' => $request->full_name,
            'training_batch_id' => $request->training_batch_id,
            'municipality' => $request->municipality,
            'agency_sector' => $request->agency_sector,
            'completion_status' => $request->completion_status,
            'completion_date' => $request->completion_status === 'Completed' && !$participant->completion_date
                ? now()->toDateString()
                : $participant->completion_date,
        ]);

        return redirect()->route('tmd.participants.index')->with('success', 'Participant updated.');
    }

    public function destroy(Participant $participant)
    {
        if ($participant->certificate_file) {
            Storage::disk('public')->delete($participant->certificate_file);
        }
        $participant->delete();
        return redirect()->route('tmd.participants.index')->with('success', 'Participant removed.');
    }

    public function uploadCertificate(Request $request, Participant $participant)
    {
        $request->validate([
            'certificate_file' => 'required|file|image|max:5120',
        ]);

        if ($participant->certificate_file) {
            Storage::disk('public')->delete($participant->certificate_file);
        }

        $path = $request->file('certificate_file')->store('certificates', 'public');
        $participant->update(['certificate_file' => $path]);

        return redirect()->route('tmd.participants.index')->with('success', 'Certificate uploaded.');
    }

    public function deleteCertificate(Participant $participant)
    {
        if ($participant->certificate_file) {
            Storage::disk('public')->delete($participant->certificate_file);
            $participant->update(['certificate_file' => null]);
        }

        return redirect()->route('tmd.participants.index')->with('success', 'Certificate removed.');
    }
}
