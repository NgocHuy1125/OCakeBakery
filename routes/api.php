<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SepayWebhookController;

Route::group([
    'prefix' => 'sepay',
    'as' => 'sepay.',
    'middleware' => ['api', 'sepay.webhook'],
], function () {
    Route::match(['post', 'get'], '/webhook', [SepayWebhookController::class, 'handle'])->name('webhook');
    Route::options('/webhook', fn () => response()->json(['message' => 'OK']));
});
