<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function getInterests() {

        $insterest = Interest::all();
        return response()->json($insterest);
    }
}
