<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Manager\Whatsapp\WhatsappController;
use App\Http\Controllers\Manager\Dashboard\DashboardController;
use App\Http\Controllers\Manager\Messages\MessagesController;
use App\Http\Controllers\Manager\Contacts\ContactsController;
use App\Http\Controllers\Manager\Settings\SettingController;
use App\Http\Controllers\Manager\Booking\BookingController;
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

Route::get('/contacts/list', [ContactsController::class,'list' ])
        ->name('contacts.list')
        ->middleware('auth');        
        
Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings')
        ->middleware('auth'); 

Route::post('/settings/updateday', [SettingController::class, 'update_day'])
        ->name('settings.updateday')
        ->middleware('auth'); 

Route::post('/settings/updateholiday', [SettingController::class, 'store_holiday'])
        ->name('settings.storeholiday')
        ->middleware('auth'); 

Route::get('/settings/listholiday', [SettingController::class, 'list_holiday'])
        ->name('settings.listholiday')
        ->middleware('auth'); 

Route::get('/settings/listday', [SettingController::class, 'list_day'])
        ->name('settings.listday')
        ->middleware('auth');

Route::get('/settings/deleteholiday/{id}', [SettingController::class, 'delete_holiday'])
        ->name('settings.deleteholiday')
        ->middleware('auth');

Route::post('/settings/update/{values}', [SettingController::class, 'update_setting'])
        ->name('settings.update')
        ->middleware('auth'); 
        
Route::get('/booking/daysavailable/{date?}', [BookingController::class, 'get_days_available'])
        ->name('booking.dayavailable')
        ->middleware('auth'); 

Route::post('/booking', [BookingController::class, 'store_booking'])
        ->name('booking.storebooking')
        ->middleware('auth');

