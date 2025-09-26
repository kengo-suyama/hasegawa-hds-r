<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('index');
});

Route::get('/quiz', function () {
    return view('hasegawa_quiz');
});
