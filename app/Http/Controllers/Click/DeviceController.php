<?php

namespace App\Http\Controllers\Click;

use App\Http\Controllers\Controller;
use App\Models\ClickDevice;
use App\Models\FundingRecord;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = ClickDevice::query();

        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        if ($request->filled('municipality') && $request->municipality !== 'ALL') {
            $query->where('municipality', $request->municipality);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_id', 'like', "%{$search}%")
                  ->orWhere('device_type', 'like', "%{$search}%")
                  ->orWhere('beneficiary', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%");
            });
        }

        $devices = $query->orderByDesc('donation_date')->paginate(10)->withQueryString();

        $totalDevices = ClickDevice::sum('quantity');
        $totalBatches = ClickDevice::count();
        $turnedOver = ClickDevice::where('status', 'Turned Over')->sum('quantity');
        $pending = ClickDevice::where('status', 'Pending')->sum('quantity');
        $inTransit = ClickDevice::where('status', 'In Transit')->sum('quantity');
        $municipalities = ClickDevice::distinct()->pluck('municipality')->sort()->values();

        $clickFunding = FundingRecord::where('project', 'PROJECT CLICK')
            ->selectRaw('SUM(allocated) as total_allocated')
            ->selectRaw('SUM(obligated) as total_obligated')
            ->selectRaw('SUM(disbursed) as total_disbursed')
            ->first();

        return view('click.devices.index', compact(
            'devices', 'totalDevices', 'totalBatches', 'turnedOver',
            'pending', 'inTransit', 'municipalities', 'clickFunding'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|string|max:50|unique:click_devices,batch_id',
            'donation_date' => 'required|date',
            'device_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'beneficiary' => 'required|string|max:255',
            'municipality' => 'required|string|max:100',
            'status' => 'required|in:Turned Over,Pending,In Transit',
        ]);

        ClickDevice::create($request->only([
            'batch_id', 'donation_date', 'device_type', 'quantity',
            'beneficiary', 'municipality', 'status',
        ]));

        return redirect()->route('click.devices.index')->with('success', 'Device donation logged successfully.');
    }

    public function update(Request $request, ClickDevice $device)
    {
        $request->validate([
            'batch_id' => 'required|string|max:50|unique:click_devices,batch_id,' . $device->id,
            'donation_date' => 'required|date',
            'device_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'beneficiary' => 'required|string|max:255',
            'municipality' => 'required|string|max:100',
            'status' => 'required|in:Turned Over,Pending,In Transit',
        ]);

        $device->update($request->only([
            'batch_id', 'donation_date', 'device_type', 'quantity',
            'beneficiary', 'municipality', 'status',
        ]));

        return redirect()->route('click.devices.index')->with('success', 'Device record updated.');
    }

    public function destroy(ClickDevice $device)
    {
        $device->delete();
        return redirect()->route('click.devices.index')->with('success', 'Device record removed.');
    }
}
