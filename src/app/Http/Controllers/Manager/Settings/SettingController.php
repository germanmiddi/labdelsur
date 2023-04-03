<?php

namespace App\Http\Controllers\Manager\Settings;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
            DetailDay::where('id', $request->form['id'])->update([
                'num_day' => $request->form['num_day'],
                'cant_orders' => $request->form['cant_orders'],
                'description' => $request->form['description']
            ]); 
            return response()->json(['message'=>'Configuración actualizada'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function store_message(Request $request){
        try {
            DefaultMessage::create(array(
                'description' => $request->form['description']
            ));
            return response()->json(['message'=>'Mensaje predefinido creado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }  

    public function update_message(Request $request){
        try {
            DefaultMessage::where('id', $request->form['id'])->update([
                'description' => $request->form['description']
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



}
