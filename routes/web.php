<?php

use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/{short}', [ShortLinkController::class, 'redirectShortLink']);