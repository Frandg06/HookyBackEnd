<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Filters\UserFilter;
use App\Http\Orders\UserOrdenator;
use App\Http\Services\CompanyUsersService;

class CustomersController extends Controller
{
    protected $companyUsersService;

    public function __construct(CompanyUsersService $companyUsersService)
    {
        $this->companyUsersService = $companyUsersService;
    }

    public function getUsers(UserFilter $filter, UserOrdenator $order)
    {
        $response = $this->companyUsersService->getUsers($filter, $order,);
        return $this->response($response);
    }

    public function getEventUsers(UserFilter $filter, UserOrdenator $order, $event_uid)
    {
        $response =  $this->companyUsersService->getEventUsers($filter, $order, $event_uid);
        return $this->response($response);
    }

    public function getEventUsersExport(UserFilter $filter, $event_uid)
    {
        $response =  $this->companyUsersService->getEventUsersExport($filter, $event_uid);
        return $this->response($response);
    }

    public function getUsersExport(UserFilter $filter, UserOrdenator $order)
    {
        $response =  $this->companyUsersService->getUsersExport($filter, $order);
        return $this->response($response);
    }
}
