<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Filters\UserFilter;
use App\Http\Orders\UserOrdenator;
use App\Http\Resources\CompanyUsersResource;
use App\Http\Resources\EventUsersResource;
use App\Http\Resources\Exports\CompanyUsersExportResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyUsersService extends Service
{
    public function getUsers(UserFilter $filter, UserOrdenator $order): array
    {
        $users = $this->company()->users()->filter($filter)->sort($order)->paginate(10);

        return [
            'data' => CompanyUsersResource::collection($users),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
        ];
    }

    public function getEventUsers(UserFilter $filter, UserOrdenator $order, string $event_uid): array
    {

        $event =  Event::find($event_uid);

        if (!$event) {
            throw new ApiException('event_not_found', 404);
        }

        $users = $event->users2()->filter($filter)->sort($order)->paginate(10);

        return [
            'data' => EventUsersResource::collection($users),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
        ];
    }

    public function getEventUsersExport(UserFilter $filter, string $event_uid): AnonymousResourceCollection|array
    {
        $users = Event::find($event_uid);

        if (!$users) {
            throw new ApiException('event_not_found', 404);
        }

        $users = $users->users2()->filter($filter)->get();

        return CompanyUsersExportResource::collection($users);
    }

    public function getUsersExport(UserFilter $filter, UserOrdenator $order): AnonymousResourceCollection|array
    {

        $users = $this->company()->users()->filter($filter)->sort($order)->get();
        return CompanyUsersExportResource::collection($users);
    }
}
