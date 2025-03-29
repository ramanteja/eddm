<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DispenserApiController;
use App\Http\Middleware\VerifyDispenserToken;

Route::middleware([VerifyDispenserToken::class])->group(function () {
    Route::get('/next-order', [DispenserApiController::class, 'getNextOrder']);
    Route::post('/dispense', [DispenserApiController::class, 'markAsDispensed']);
});
