<?php

use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group (no CSRF protection).
|
*/

// WhatsApp webhook — receives incoming message events from Evolution API
Route::post('/whatsapp/webhook', [WhatsAppWebhookController::class, 'handle'])
    ->name('whatsapp.webhook');
