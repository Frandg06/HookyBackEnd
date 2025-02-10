<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("https://www.hookyapp.es/");
});

Route::get('/email', function () {
    return view('emails.recovery_password_app', [
        'link' => 'link',
        'name' => 'name',
        'uid' => 'uid',
    ]);
});
