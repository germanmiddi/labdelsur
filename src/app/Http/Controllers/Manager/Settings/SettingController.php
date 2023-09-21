<?php

namespace App\Http\Controllers\Manager\Settings;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\Models\Contact;
use App\Models\Holiday;
use App\Models\DefaultMessage;
use App\Models\DetailDay;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        return Inertia::render('Manager/Settings/Index_new');
        // return Inertia::render('Manager/Settings/Index_new', 
        // [
        //     'holidays' => Holiday::all(),
        //     'messages' => DefaultMessage::all(),
        //     'days' => DetailDay::all(),
        //     'settings' => Setting::get()
        // ]); 
    }

    public function store_holiday(Request $request){

        
        $input_date = $request->form['date'];
        $input_date  = date('Y-m-d', strtotime($input_date));
        try {
            Holiday::create(array(
                'date' => $input_date,
                'description' => $request->form['description']
            ));
            return response()->json(['message'=>'Feriado creado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }


    public function delete_holiday($id){
        try {
            $holiday = Holiday::find($id);
            $holiday->delete();
            return response()->json(['message'=>'Feriado eliminado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function update_day(Request $request){
        try {
            DetailDay::where('id', $request->id)->update([
                'cant_orders' => $request->cant_orders                
            ]); 
            return response()->json(['message'=>'Configuración actualizada'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function store_message(Request $request){
        try {
            DefaultMessage::create(array(
                'description' => $request->message
            ));
            return response()->json(['message'=>'Mensaje predefinido creado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }  

    public function update_message(Request $request){
        try {
            DefaultMessage::where('id', $request->id)->update([
                'description' => $request->message
            ]); 
            return response()->json(['message'=>'Mensaje predefinido actualizado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function delete_message($id){
        try {
            $message = DefaultMessage::find($id);
            $message->delete();
            return response()->json(['message'=>'Mensaje predeterminado eliminado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function list_message(){
        return DefaultMessage::all();
    }
   
    public function list_holiday(){
        return Holiday::all();
    }

    public function list_day(){
        return DetailDay::all();
    }

    // UPDATE SETTINGS
    public function update_setting(Request $request)
    {
        //dd($request[0]);
        //$rows = json_decode($request);
        //dd($request->data);
        try {

            for($i = 0; $i < count($request->data); $i++){
                $content = Setting::find($request->data[$i]['id']);
                $content->value = $request->data[$i]['value'];
                $content->save();
            }
            return response()->json(['message'=>'Configuración actualizada'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function get_whatsapp(){
        return Setting::where('module', 'WP')->get();
    }

    public function get_general(){
        return Setting::all();
        //return Setting::where('module', 'MAIN')->get();
    }

    public function update_whatsapp(Request $request){
        try {
            Setting::where('key', 'wp_url')->update([
                'value' => $request->wp_url
            ]); 
            Setting::where('key','wp_url_media' )->update([
                'value' => $request->wp_url_media
            ]); 
            Setting::where('key','wp_token')->update([
                'value' => $request->wp_token
            ]); 

            return response()->json(['message'=>'Configuración actualizada'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function update_general(Request $request){

        try {
            $rows = $request->input('rows');

            foreach ($rows as $row) {
                $key = $row['key'];
                $value = $row['value'];

               // Buscar y actualizar la fila correspondiente en la base de datos
               Setting::where('key', $key)->update(['value' => $value]);
            }

            return response()->json(['message'=>'Se han actualizado los datos'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function turnos(){

        return response()->json([
            'turnos' => Setting::where('module', 'BOOKING')->get()->pluck('value', 'key'),
            'dias' => DetailDay::all()
        ], 200);

    }

    public function update_turnos(Request $request){

        try {
            $rows = $request->all();

            
            foreach ($rows as $key => $value ) {

                DB::beginTransaction(); 
                // Buscar y actualizar la fila correspondiente en la base de datos
                Setting::where('key', $key)->update(['value' => $value]);
                DB::commit();
            }

            return response()->json(['message'=>'Se han actualizado los datos'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
