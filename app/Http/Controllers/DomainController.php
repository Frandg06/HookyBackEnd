<?php

namespace App\Http\Controllers;

use App\Models\TimeZone;

class DomainController extends Controller
{
    public function getTimeZones()
    {
        $timezones = TimeZone::all();
        return response()->json($timezones);
    }
}
