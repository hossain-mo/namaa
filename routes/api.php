<?php

use App\Http\Controllers\UserTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserTransactionController::class, 'index']);
