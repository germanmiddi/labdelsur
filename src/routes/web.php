<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Manager\Orders\OrderController;
use App\Http\Controllers\Manager\Clients\ClientController;
use App\Http\Controllers\Manager\Drivers\DriverController;
use App\Http\Controllers\Manager\Companies\CompanyController;
use App\Http\Controllers\Manager\Whatsapp\WhatsappController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/',[HomeController::class, 'index'])
       ->name('home');

Route::get('/dashboard', function(){
            return Inertia::render('Manager/Dashboard/Index');
        })->name('dashboard')->middleware('auth');    


Route::get('/whatsapp/sendtest', [WhatsappController::class,'sendTest' ])
        ->name('whatsapp.sendtest')
        ->middleware('auth');

Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders')
    ->middleware('auth');    

Route::get('/orders/create', [OrderController::class, 'create'])
    ->name('orders.create')
    ->middleware('auth');    

Route::get('/clients', [ClientController::class, 'index'])
    ->name('clients')
    ->middleware('auth');    

Route::get('/companies', [CompanyController::class, 'index'])
    ->name('companies')
    ->middleware('auth');    

Route::get('/drivers', [DriverController::class, 'index'])
    ->name('drivers')
    ->middleware('auth');    
    