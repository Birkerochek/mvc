<?php
Route::get('/register', [MainController::class,'registerRender']);
Route::post('/register', [MainController::class,'register']);
Route::get('logout', [MainController::class,'logout']);
Route::get('/login', [MainController::class,'loginRender']);
Route::post('/login', [MainController::class,'login']);
Route::get('/posts', [MainController::class,'testGetPosts']);