<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\TimeZone;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function getInterests() {

        $insterest = Interest::all();
        return response()->json($insterest);
    }

    public function getTimeZones() {
        $timezones = TimeZone::all();
        return response()->json($timezones);
    }
}
