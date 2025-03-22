<?php

namespace App\Http\Controllers;

use App\Http\Filters\UserFilter;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyUsersController extends Controller
{
    public function getUsers(Request $request) {}

    public function getEventUsers(UserFilter $filter, $event_uid)
    {
        $users =  Event::find($event_uid)->users2()->filter($filter)->paginate(10);

        return response()->json(['success' => true, 'resp' => $users], 200);
    }
}
