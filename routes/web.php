<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("https://www.hookyapp.es/");
});

Route::get('/health', function () {
    echo "The server is up!";
});
