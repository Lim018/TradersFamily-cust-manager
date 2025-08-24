<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/spreadsheet-update', [WebhookController::class, 'spreadsheetUpdate']);
Route::get('/maintain-data', [WebhookController::class, 'getMaintainData'])->name('api.maintain-data');
Route::get('/maintain-data/{id}/notes', [WebhookController::class, 'getMaintainNotes'])->name('api.maintain-notes');