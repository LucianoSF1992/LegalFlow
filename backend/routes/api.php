<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ClientController,LegalCaseController,TaskController,DashboardController,DocumentController};
Route::prefix('auth')->group(function(){
 Route::post('/register',[AuthController::class,'register']);
 Route::post('/login',[AuthController::class,'login']);
 Route::middleware('auth:sanctum')->group(function(){
  Route::get('/me',[AuthController::class,'me']);
  Route::post('/logout',[AuthController::class,'logout']);
 });
});
Route::middleware('auth:sanctum')->group(function(){
 Route::get('/dashboard',DashboardController::class);
 Route::apiResource('clients',ClientController::class);
 Route::apiResource('cases',LegalCaseController::class);
 Route::apiResource('tasks',TaskController::class);
 Route::get('/documents/{document}/download',[DocumentController::class,'download']);
 Route::apiResource('documents',DocumentController::class)->only(['index','store','destroy']);
});
