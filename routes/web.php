<?php

use App\Http\Controllers\TextbookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/textbooks', [TextbookController::class, 'index']);
Route::get('/{boardGroup}', [TextbookController::class, 'boardGroup']);
Route::get('/{boardGroup}/{board}', [TextbookController::class, 'board']);
Route::get('/{boardGroup}/{board}/{class}', [TextbookController::class, 'class']);
Route::get('/{boardGroup}/{board}/{class}/{subject}', [TextbookController::class, 'subject']);
Route::get('/{boardGroup}/{board}/{class}/{subject}/unit-{unitNumber}', [TextbookController::class, 'unit']);
