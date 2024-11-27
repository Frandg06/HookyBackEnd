<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\AuthCompanyResource;
use App\Models\Company;
use App\Models\TimeZone;
use Exception;

class CompanyService
{
    public function update(Company $company, array $data) {
      try {
        $timezone_uid = TimeZone::where('name', $data['timezone_string'])->first()->uid;
  
        if(!$timezone_uid) throw new CustomException("No existe el timezone seleccionado");
  
        $company->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country' => $data['country'],  
            'timezone_uid' => $timezone_uid,
        ]);

        return AuthCompanyResource::make($company);
      } catch (\Throwable $e) {
        ($e instanceof CustomException)
        ? throw new Exception($e->getMessage())
        : throw new \Exception("Se ha producido un error al crear el evento");
      }
      
    }
}