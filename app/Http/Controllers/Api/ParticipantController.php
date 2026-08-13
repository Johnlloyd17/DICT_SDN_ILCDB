<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;

class ParticipantController extends Controller
{
    public function index()
    {
        return response()->json(
            Participant::select('full_name', 'municipality', 'agency_sector', 'completion_status')
                ->get()
                ->toArray()
        );
    }
}
