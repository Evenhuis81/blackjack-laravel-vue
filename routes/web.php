<?php

Route::get('/', function () {
    return view('main');
});

// deze route kan weggelaten worden want wordt niet gebruikt
Auth::routes();
