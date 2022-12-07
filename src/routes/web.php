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
use App\Http\Controllers\Manager\ObrasSociales\ObrasSocialesController;
use App\Http\Controllers\Manager\Estudios\EstudiosController;
use App\Http\Controllers\Manager\Faqs\FaqsController;
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

Route::get('/preguntas-frecuentes',[HomeController::class, 'faq'])
       ->name('preguntas-frecuentes');

Route::get('/estudios',[HomeController::class, 'estudios'])
       ->name('estudios');

Route::get('/obras-sociales',[HomeController::class, 'osociales'])
       ->name('obras-sociales');       

Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('auth');    

Route::get('/whatsapp/sendtest', [WhatsappController::class,'sendTest' ])
        ->name('whatsapp.sendtest')
        ->middleware('auth');

Route::get('/whatsapp/{id}/getUrl', [WhatsappController::class,'getUrl' ])
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
        
Route::post('/settings/storemessage', [SettingController::class, 'store_message'])
        ->name('settings.storemessage')
        ->middleware('auth');

Route::get('/settings/listholiday', [SettingController::class, 'list_holiday'])
        ->name('settings.listholiday')
        ->middleware('auth');
        
Route::get('/settings/listmessage', [SettingController::class, 'list_message'])
        ->name('settings.listmessage')
        ->middleware('auth');

Route::get('/settings/listday', [SettingController::class, 'list_day'])
        ->name('settings.listday')
        ->middleware('auth');

Route::get('/settings/deleteholiday/{id}', [SettingController::class, 'delete_holiday'])
        ->name('settings.deleteholiday')
        ->middleware('auth');

Route::get('/settings/deletemessage/{id}', [SettingController::class, 'delete_message'])
        ->name('settings.deletemessage')
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
        
Route::post('/booking', [BookingController::class, 'create_booking'])
        ->name('booking.createbooking')
        ->middleware('auth');
        
Route::post('/booking/updatestatus', [BookingController::class, 'update_status'])
        ->name('booking.updatestatus')
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

// Obras Sociales.

Route::get('/obras-sociales/index', [ObrasSocialesController::class, 'index'])
        ->name('obras-sociales.index')
        ->middleware('auth');
        
Route::get('/obras-sociales/list', [ObrasSocialesController::class, 'list'])
        ->name('obras-sociales.list')
        ->middleware('auth');

Route::post('/obras-sociales/store', [ObrasSocialesController::class, 'store'])
        ->name('obras-sociales.store')
        ->middleware('auth');

Route::post('/obras-sociales/update', [ObrasSocialesController::class, 'update'])
        ->name('obras-sociales.update')
        ->middleware('auth');

Route::post('/obras-sociales/update_visibilidad', [ObrasSocialesController::class, 'update_visibilidad'])
        ->name('obras-sociales.update_visibilidad')
        ->middleware('auth');

// Estudios

Route::get('/estudios/index', [EstudiosController::class, 'index'])
->name('estudios.index')
->middleware('auth');

Route::get('/estudios/list', [EstudiosController::class, 'list'])
->name('estudios.list')
->middleware('auth');

Route::post('/estudios/store', [EstudiosController::class, 'store'])
->name('estudios.store')
->middleware('auth');

Route::post('/estudios/update', [EstudiosController::class, 'update'])
->name('estudios.update')
->middleware('auth');

Route::post('/estudios/update_visibilidad', [EstudiosController::class, 'update_visibilidad'])
->name('estudios.update_visibilidad')
->middleware('auth');

// FAQS

Route::get('/faqs/index', [FaqsController::class, 'index'])
->name('faqs.index')
->middleware('auth');

Route::get('/faqs/list', [FaqsController::class, 'list'])
->name('faqs.list')
->middleware('auth');

Route::post('/faqs/store', [FaqsController::class, 'store'])
->name('faqs.store')
->middleware('auth');

Route::post('/faqs/update', [FaqsController::class, 'update'])
->name('faqs.update')
->middleware('auth');

Route::post('/faqs/update_visibilidad', [FaqsController::class, 'update_visibilidad'])
->name('faqs.update_visibilidad')
->middleware('auth');
