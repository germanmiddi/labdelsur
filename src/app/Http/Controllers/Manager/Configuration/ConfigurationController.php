<?php

namespace App\Http\Controllers\Manager\Configuration;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Contact;
use App\Models\Holiday;
use App\Models\DetailDay;

class ConfigurationController extends Controller
{
    public function index(){
        return Inertia::render('Manager/Configuration/Index', 
        [
            'holidays' => Holiday::all(),
            'days' => DetailDay::all()
        ]);
    }

    public function store_holiday(Request $request){

        //dd();
        $input_date = $request->form['date'];
        $input_date  = date('Y-m-d', strtotime($input_date));
        try {
            Holiday::create(array(
                'date' => $input_date,
                'description' => $request->form['description']
            ));
            return response()->json(['message'=>'Feriado creado con éxito'], 200);
         } catch (\Throwable $th) {
             return response()->json(['message'=>'Se ha producido un error'], 203);
         }
    }


    public function delete_holiday($id){
        try {
            $holiday = Holiday::find($id);
            $holiday->delete();
            return response()->json(['message'=>'Feriado eliminado con éxito'], 200);
         } catch (\Throwable $th) {
             return response()->json(['message'=>'Se ha producido un error'], 203);
         }
    }

    public function update_day(Request $request){
        try {
            DetailDay::where('id', $request->form['id'])->update([
                'num_day' => $request->form['num_day'],
                'cant_orders' => $request->form['cant_orders'],
                'description' => $request->form['description']
            ]); 
            return response()->json(['message'=>'Configuración actualizada con éxito'], 200);
         } catch (\Throwable $th) {
             return response()->json(['message'=>'Se ha producido un error'], 203);
         }
    }

    public function list_holiday(){
        return Holiday::all();
    }

    public function list_day(){
        return DetailDay::all();
    }
}
