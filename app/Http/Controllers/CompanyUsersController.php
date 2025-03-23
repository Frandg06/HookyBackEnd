<?php

namespace App\Http\Controllers;

use App\Http\Filters\UserFilter;
use App\Http\Resources\EventUsersResource;
use App\Http\Resources\Exports\EventUsersExportResource;
use App\Models\Event;
use Illuminate\Http\Request;

class CompanyUsersController extends Controller
{
    public function getUsers(Request $request) {}

    public function getEventUsers(UserFilter $filter, $event_uid)
    {
        $users =  Event::find($event_uid)->users2()->filter($filter)->paginate(10);


        return response()->json(['success' => true, 'resp' => [
            'data' => EventUsersResource::collection($users),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
        ]], 200);
    }

    public function getEventUsersExport(UserFilter $filter, $event_uid)
    {
        $users = Event::find($event_uid)->users2()->filter($filter)->get();

        $users->event_uid = $event_uid;

        $users = EventUsersExportResource::collection($users);

        return response()->json(['success' => true, 'resp' => $users], 200);
    }
}
