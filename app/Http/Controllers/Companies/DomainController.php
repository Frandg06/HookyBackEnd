<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies;

use App\Models\TimeZone;
use App\Http\Controllers\Controller;

final class DomainController extends Controller
{
    public function getTimeZones()
    {
        $timezones = TimeZone::all();

        return response()->json($timezones);
    }
}
