<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartUserAndIncomesResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function getUserIncomesData(Request $request)
    {
        // Get last 7 events ordered by date desc, then reverse the collection
        $events = $this->company()->events()
            ->withCount('users2')
            ->orderBy('st_date', 'desc')
            ->take($request->limit ?? 15)
            ->get()
            ->reverse();



        $events = ChartUserAndIncomesResource::make($events);
        return $this->response($events);
    }
}
