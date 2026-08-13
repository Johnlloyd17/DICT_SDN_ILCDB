<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClickDevice;

class ClickController extends Controller
{
    public function index()
    {
        return response()->json(
            ClickDevice::select('batch_id', 'device_type', 'quantity', 'beneficiary', 'municipality', 'status')
                ->get()
                ->toArray()
        );
    }
}
