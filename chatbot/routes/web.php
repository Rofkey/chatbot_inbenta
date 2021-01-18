<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Api\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/info', function () {
    return view('welcome');
});

Route::get('/', [ChatbotController::class, 'index']);

Route::get('access_token',[ChatController::class,'token']);
Route::get('/session_token',[ChatController::class,'session']);
Route::get('/send_message',[ChatController::class,'sendMessage']);
Route::get('/get_message_heroes',[ChatController::class,'getMessageHeroes']);
Route::get('/get_message_films',[ChatController::class,'getMessagefilms']);
Route::get('/get_history',[ChatController::class,'getHistory']);