<?php

namespace App\Http\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryOrdenator
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
        foreach ($this->requestFields() as $field => $order) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], !is_null($order) ? (array)$order : []);
            }
        }
    }

    public function requestFields()
    {
        $fields = [];
        foreach (explode(',', $this->request->sort) as $value) {
            $field = explode(':', $value);
            $fields[$field[0]] = isset($field[1]) ? $field[1] : null;
        }
        return $fields;
    }
}
