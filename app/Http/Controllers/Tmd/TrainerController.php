<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        return response()->json(
            Trainer::orderBy('full_name')->get()->map(fn ($t) => $this->payload($t))
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $trainer = Trainer::create([
            'full_name' => $data['name'],
            'designation' => $data['designation'] ?? null,
            'specialty' => $data['specialty'],
            'agency' => $data['agency'] ?? null,
            'contact' => $data['contact'] ?? null,
            'phone' => $data['phone'] ?? null,
            'courses' => $data['courses'] ?? 0,
            'rating' => $data['rating'] ?? 0,
            'status' => $data['status'],
        ]);

        return response()->json(['trainer' => $this->payload($trainer)], 201);
    }

    public function update(Request $request, Trainer $trainer)
    {
        $data = $this->validated($request);

        $trainer->update([
            'full_name' => $data['name'],
            'designation' => $data['designation'] ?? null,
            'specialty' => $data['specialty'],
            'agency' => $data['agency'] ?? null,
            'contact' => $data['contact'] ?? null,
            'phone' => $data['phone'] ?? null,
            'courses' => $data['courses'] ?? 0,
            'rating' => $data['rating'] ?? 0,
            'status' => $data['status'],
        ]);

        return response()->json(['trainer' => $this->payload($trainer->fresh())]);
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->delete();

        return response()->json(['message' => 'Trainer deleted successfully.']);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'agency' => 'nullable|string|max:255',
            'contact' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:Active,Inactive',
            'courses' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);
    }

    protected function payload(Trainer $trainer): array
    {
        return [
            'id' => $trainer->id,
            'name' => $trainer->full_name,
            'designation' => $trainer->designation,
            'specialty' => $trainer->specialty,
            'agency' => $trainer->agency,
            'contact' => $trainer->contact,
            'phone' => $trainer->phone,
            'status' => $trainer->status,
            'courses' => (int) $trainer->courses,
            'rating' => (float) $trainer->rating,
        ];
    }
}
