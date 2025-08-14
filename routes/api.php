<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;


Route::post('/gps/receive', [TrackingController::class, 'receive']);
Route::get('/gps/ping', fn() => response()->json(['pong' => true]));