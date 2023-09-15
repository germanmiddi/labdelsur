<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Whatsapp\WhatsappController;
use App\Http\Controllers\Web\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/whatsapp/receive',[WhatsappController::class, 'receive']);
Route::post('/whatsapp/receive',[WhatsappController::class, 'receive']);
Route::post('/whatsapp/job',[WhatsappController::class, 'dispatch_job']);

Route::post('/turnos/create',[HomeController::class, 'turno_post'])->name('turno_post');