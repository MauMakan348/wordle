<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordleController;

Route::get('/', [WordleController::class, 'index']);
