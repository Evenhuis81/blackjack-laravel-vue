<?php

Route::get('/', function () {
    return view('main');
});

Auth::routes();
