<?php

namespace App\Http\Services;

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
    try {
      $users = $this->company()->users()->filter($filter)->sort($order)->paginate(10);

      return [
        'data' => CompanyUsersResource::collection($users),
        'current_page' => $users->currentPage(),
        'last_page' => $users->lastPage(),
        'total' => $users->total(),
        'per_page' => $users->perPage(),
      ];
    } catch (\Exception $e) {
      $this->logError($e, __CLASS__, __FUNCTION__);
      return $this->responseError('get_users_ko', 500);
    }
  }

  public function getEventUsers(UserFilter $filter, UserOrdenator $order, string $event_uid): array
  {
    try {
      $event =  Event::find($event_uid);
      if (!$event) return $this->responseError('event_not_found', 404);

      $users = $event->users2()->filter($filter)->sort($order)->paginate(10);

      return [
        'data' => EventUsersResource::collection($users),
        'current_page' => $users->currentPage(),
        'last_page' => $users->lastPage(),
        'total' => $users->total(),
        'per_page' => $users->perPage(),
      ];
    } catch (\Exception $e) {
      $this->logError($e, __CLASS__, __FUNCTION__);
      return $this->responseError('get_users_ko', 500);
    }
  }

  public function getEventUsersExport(UserFilter $filter, string $event_uid): AnonymousResourceCollection|array
  {
    try {

      $users = Event::find($event_uid);

      if (!$users) return $this->responseError('event_not_found', 404);

      $users = $users->users2()->filter($filter)->get();

      return CompanyUsersExportResource::collection($users);
    } catch (\Exception $e) {
      $this->logError($e, __CLASS__, __FUNCTION__);
      return $this->responseError('get_users_export_ko', 500);
    }
  }

  public function getUsersExport(UserFilter $filter, UserOrdenator $order): AnonymousResourceCollection|array
  {
    try {
      $users = $this->company()->users()->filter($filter)->sort($order)->get();
      return CompanyUsersExportResource::collection($users);
    } catch (\Exception $e) {
      $this->logError($e, __CLASS__, __FUNCTION__);
      return $this->responseError('get_users_export_ko', 500);
    }
  }
}
