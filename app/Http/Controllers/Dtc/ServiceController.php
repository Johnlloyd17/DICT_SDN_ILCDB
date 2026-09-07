<?php

namespace App\Http\Controllers\Dtc;

use App\Http\Controllers\Controller;
use App\Models\DtcService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dtc_hub_id' => 'required|exists:dtc_hubs,id',
            'service_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $service = DtcService::create([
            'dtc_hub_id' => $request->dtc_hub_id,
            'service_name' => $request->service_name,
            'category' => $request->category,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['service' => $service], 201);
        }

        return redirect()->route('dtc.visitors.index')
            ->with('success', 'DTC service added successfully.');
    }

    public function update(Request $request, DtcService $service)
    {
        $request->validate([
            'dtc_hub_id' => 'required|exists:dtc_hubs,id',
            'service_name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $service->update([
            'dtc_hub_id' => $request->dtc_hub_id,
            'service_name' => $request->service_name,
            'category' => $request->category,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : $service->is_active,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['service' => $service->fresh()]);
        }

        return redirect()->route('dtc.visitors.index')
            ->with('success', 'DTC service updated successfully.');
    }

    public function destroy(DtcService $service)
    {
        $service->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'DTC service removed.']);
        }

        return redirect()->route('dtc.visitors.index')
            ->with('success', 'DTC service removed.');
    }
}