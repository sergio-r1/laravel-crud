<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $mensagem = "test working";
    $status = "ok";

    return response()->json([
        'message' => $mensagem,
        'status' => $status
    ]);
});

Route::get('/contacts', function () {
    return file_get_contents(public_path('index.html'));
});
