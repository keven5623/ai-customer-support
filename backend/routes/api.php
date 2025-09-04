<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::post('/chat', [ChatController::class, 'sendMessage']);
Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'getWeather']);


Route::post('chat/image', [ChatController::class, 'chatImage']);
Route::post('chat/audio', [ChatController::class, 'chatAudio']);