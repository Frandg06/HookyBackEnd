<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
  protected $request;
  protected $builder;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function apply(Builder $query)
  {
    $this->builder = $query;
    foreach ($this->requestFields() as $field => $value) {
      $method = Str::camel($field);
      // This hace referencia a la clase a la que le agregamos esta absracta y busca el metodo
      if (method_exists($this, $method)) {
        // llama al metodo y le pasa el valor
        call_user_func_array([$this, $method], (array)$value);
      }
    }
  }

  public function requestFields()
  {
    // Con el filtro eliminamos los campos vacios
    return array_filter(
      // con el map y trim los espacios en blanco
      array_map('trim', $this->request->all())
    );
  }
}
