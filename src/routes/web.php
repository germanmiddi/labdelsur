<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Manager\Whatsapp\WhatsappController;
use App\Http\Controllers\Manager\Dashboard\DashboardController;
use App\Http\Controllers\Manager\Messages\MessagesController;
use App\Http\Controllers\Web\HomeController;


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

Route::get('/',[HomeController::class, 'index'])
       ->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('auth');    

Route::get('/whatsapp/sendtest', [WhatsappController::class,'sendTest' ])
        ->name('whatsapp.sendtest')
        ->middleware('auth');


Route::get('/messages/list', [MessagesController::class,'list' ])
        ->name('messages.list')
        ->middleware('auth');



// Route::get('/orders', [OrderController::class, 'index'])
//     ->name('orders')
//     ->middleware('auth');    

// Route::get('/orders/create', [OrderController::class, 'create'])
//     ->name('orders.create')
//     ->middleware('auth');    

// Route::get('/clients', [ClientController::class, 'index'])
//     ->name('clients')
//     ->middleware('auth');    

// Route::get('/companies', [CompanyController::class, 'index'])
//     ->name('companies')
//     ->middleware('auth');    

// Route::get('/drivers', [DriverController::class, 'index'])
//     ->name('drivers')
//     ->middleware('auth');    
    