<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo phpinfo();
    // return redirect("https://www.hookyapp.es/");
});
