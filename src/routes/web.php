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
use App\Http\Controllers\Manager\User\UserController;
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

Route::post('/whatsapp/getUrl', [WhatsappController::class,'getUrl' ])
        ->name('whatsapp.geturl')
        ->middleware('auth');

Route::post('/whatsapp/sendMessage', [WhatsappController::class,'sendMessage' ])
        ->name('whatsapp.sendmessage')
        ->middleware('auth');


Route::get('/messages/list', [MessagesController::class,'list' ])
        ->name('messages.list')
        ->middleware('auth');

Route::get('/contacts', [ContactsController::class,'index' ])
        ->name('contacts')
        ->middleware('auth');

Route::get('/contacts/list', [ContactsController::class,'list' ])
        ->name('contacts.list')
        ->middleware('auth');

Route::get('/contacts/changestatusbot/{id}', [ContactsController::class,'change_status_bot' ])
        ->name('contacts.changestatusbot')
        ->middleware('auth');

Route::get('/contacts/listdashboard', [ContactsController::class,'list_dashboard' ])
        ->name('contacts.listdashboard')
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

Route::post('/settings/update', [SettingController::class, 'update_setting'])
        ->name('settings.update')
        ->middleware('auth'); 

Route::get('/booking', [BookingController::class, 'index'])
        ->name('booking')
        ->middleware('auth');

Route::get('booking/list', [BookingController::class, 'list'])
        ->name('booking.list')
        ->middleware('auth');
        
Route::get('/booking/daysavailable/{date?}', [BookingController::class, 'get_days_available'])
        ->name('booking.dayavailable')
        ->middleware('auth'); 

Route::post('/booking', [BookingController::class, ' create_booking'])
        ->name('booking.createbooking')
        ->middleware('auth');

Route::get('/user', [UserController::class, 'index'])
        ->name('user')
        ->middleware('auth');

Route::get('user/list', [UserController::class, 'list'])
        ->name('user.list')
        ->middleware('auth');

Route::post('user/store', [UserController::class, 'store'])
        ->name('user.store')
        ->middleware('auth');

Route::post('user/update', [UserController::class, 'update'])
        ->name('user.update')
        ->middleware('auth');