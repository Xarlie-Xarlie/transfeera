<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiverController;

Route::apiResource('receiver', ReceiverController::class);
Route::delete('/receivers', [ReceiverController::class, "destroyMany"]);
