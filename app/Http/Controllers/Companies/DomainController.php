<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Models\TimeZone;

class DomainController extends Controller
{
    public function getTimeZones()
    {
        $timezones = TimeZone::all();

        return response()->json($timezones);
    }
}
