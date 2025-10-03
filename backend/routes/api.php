<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::apiResource('contacts', ContactController::class);
