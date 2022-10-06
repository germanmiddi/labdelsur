<?php

namespace App\Http\Controllers\Manager\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\DetailDay;
use App\Models\Holiday;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Setting;

class BookingController extends Controller
{
    public function get_days_available($date = ''){
        try {
            // Formatea variable de fecha..
            if($date === ''){
                $date = Carbon::now()->tz('-3');
            }else{
                $date = Carbon::parse($date);
            }

            // Se obtienen los datos de configuración.
            $setting =  Setting::where('module', 'BOOKING')->get();
            foreach ($setting as $s) {
                switch ($s['key']) {
                    case 'day_limit_booking':
                        $day_limit = $s['value'];
                        break;
                    case 'cant_days_booking':
                        $cant_days = $s['value'];
                        break;
                    case 'hora_limit_booking':
                        $hora_limit = $s['value'];
                        break;
                }
            }

            // Se realizan los calculos de limites de fecha y hora.
            $hora = $date->format('H:i');
            
            // Se controla el horario limite, si la hora actual es menor se resta 1 dia para comenzar con fecha actual.
            
            if($hora < $hora_limit){
                $date->addDay(-1);
            }
            $flag_days = 0;
            while ($flag_days < $cant_days  && $date <= Carbon::parse($day_limit)->addDay(-1)) {
                //control horario de cierre
                $date->addDay(1);
                //Obtengo la cantidad de Orders permitidas segun el dia de la semana a consultar...
                $cant_orders = DetailDay::select('cant_orders')->where('num_day', '=', date('w', strtotime($date)))->first();
                if(
                    // Check que se tomen turnos para el dia seleccionado.
                    $cant_orders['cant_orders'] > 0
                    && 
                    // Check que fecha no sea feriado.
                    Holiday::where('date', '=', $date->format('Y-m-d'))->doesntExist()
                    &&
                    // Check que no se hayan completado el limite de las orders diarias. 
                    Booking::where('date',$date->format('Y-m-d'))->where('status', 1)->count() < $cant_orders['cant_orders']
                )
                {
                    $days[] = $date->format('Y-m-d');
                    $flag_days++;
                }
            }

            if(!isset($days)){
                return response()->json(['message'=>'No se han encontrado turnos disponibles'], 203);
            }
            return response()->json(['message'=>'Se ha procesado con exito', 'data' => $days], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
        

        //response()->json($days);
    }

    public function store_booking(Request $request){
        DB::beginTransaction();
        try {
            // ACTUALIZO LOS DATOS DEL CONTACTO
            $contact = Contact::where('wa_id', $request->form['wa_id'])->update([
                'fullname' => $request->form['fullname'],
                'nro_doc'  => $request->form['nro_doc'] ?? null, 
                'nro_affiliate' => $request->form['nro_affiliate'] ?? null  
            ]);

            // CREO EL NUEVO TURNO..            
            $input_date = $request->form['date'];
            $input_date  = date('Y-m-d', strtotime($input_date));

            $booking = new Booking;
            $booking->status = 0;
            $booking->date = $input_date;
            $booking->contact_id = $contact->id;

            $booking->save();

            DB::commit();
            
            return response()->json(['message'=>'Turno registrado'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error'], 500);
    }
}
}
