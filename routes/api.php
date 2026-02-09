<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolCheckoutController;

Route::get('/tool-checkouts/current', [ToolCheckoutController::class, 'current']);
Route::patch('/tool-checkouts/{toolCheckout}/check-in', [ToolCheckoutController::class, 'checkIn']);
