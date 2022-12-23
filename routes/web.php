<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NotifyController;


Route::get('/notify', [NotifyController::class, 'notify']);
