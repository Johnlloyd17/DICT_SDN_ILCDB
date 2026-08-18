<?php

namespace App\Http\Controllers\Tmd;

use App\Http\Controllers\Controller;
use App\Models\TmdPenetration;
use Illuminate\Http\Request;

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
}
