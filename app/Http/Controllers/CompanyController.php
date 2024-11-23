<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function getLink(Request $request) {
        $company = $request->user();
        return response()->json([
            'status' => 'success',
            'resp' => $company->link,
        ]);
    }
}
