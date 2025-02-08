<?php

use App\Http\Resources\GatsByCollection;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return new GatsByCollection([]);
});
