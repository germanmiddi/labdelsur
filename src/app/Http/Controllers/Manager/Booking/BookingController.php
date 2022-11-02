<?php

namespace App\Http\Controllers\Manager\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use Illuminate\Support\Facades\Log;

use App\Models\DetailDay;
use App\Models\Holiday;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Contact;
use App\Models\Setting;

class BookingController extends Controller
{

    public function index()
    {
        return  Inertia::render('Manager/Booking/List', 
        [
            'booking_status' => BookingStatus::all()
        ]);
    }

    public function list(){

        $result = Booking::query();

        $length = request('length');
        $sort_by = request('sort_by') ?? 'id';
        $sort_order = request('sort_order') ?? 'DESC';

        if(request('search_date')){
            $date = json_decode(request('search_date'));
            $search_date = date('Y-m-d', strtotime($date));
            $result->whereDate('date', $search_date);
        }

        if(request('search')){
            $search = request('search');

            $result->whereHas('contact', function($q) use ($search){
                $q->Where('fullname','LIKE', '%'. $search . '%')
                    ->orWhere('name','LIKE', '%'. $search . '%')
                    ->orWhere('wa_id','LIKE', '%'. $search . '%')
                    ->orWhere('nro_affiliate','LIKE', '%'. $search . '%');
            });

            
        }

        if($sort_by === 'fullname' || $sort_by === 'nro_affiliate' || $sort_by === 'wa_id' || $sort_by === 'name'){
            $sort_by = 'contact.'.$sort_by;
            $result->leftJoin(DB::raw('(select * from contacts) as contact'), function ($join) {
                                    $join->on ( 'contact.id', '=', 'contact_id' );
                    });
        }else if($sort_by === 'status'){
            $sort_by = 'booking_status.'.$sort_by;
            $result->leftJoin(DB::raw('(select * from booking_status) as booking_status'), function ($join) {
                                    $join->on ( 'booking_status.id', '=', 'status_id' );
                    });
        }

        
        return  $result ->orderBy($sort_by, $sort_order)
                        ->paginate($length)
                        ->withQueryString()
                        ->through(fn ($booking) => [
                            'id'                    => $booking->id,
                            'date'                  => Carbon::parse($booking->date)->format("d-m-Y"),
                            'contact'               => $booking->contact()->first(),
                            'status'                => $booking->status()->first(),
                        ]);

    }

    public function days_available($date = ''){
        
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
                    Booking::where('date',$date->format('Y-m-d'))->where('status_id', 1)->count() < $cant_orders['cant_orders']
                )
                {
                    $days[] = $date->format('Y-m-d');
                    $flag_days++;
                }
            }

            if(!isset($days)){
                return $data = [
                    'code' => 203,
                    'message' => 'No se han encontrado turnos disponibles',
                    'data' => ''
                ];
            }
            return $data = [
                'code' => 200,
                'message' => 'Se ha procesado con exito',
                'data' => $days
            ];
        } catch (\Throwable $th) {
            return $data = [
                'code' => 500,
                'message' => 'Se ha producido un erro',
                'data' => $days
            ];
            
        }
    }
    public function get_days_available($date = ''){
        
        $data = $this->days_available($date);

        if($data['code'] == 200){
            return response()->json($data, 200);
        }elseif($data['code'] == 203){
            return response()->json($data, 203);
        }else{
            return response()->json($data, 500);
        }
    }

    public function create_booking(Request $request){
        $data = $this->store_booking($request->form);

        if($data['code'] == 200){
            return response()->json($data, 200);
        }else{
            return response()->json($data, 500);
        }
    }

    public function store_booking($data){
        DB::beginTransaction();
        try {
            // ACTUALIZO LOS DATOS DEL CONTACTO
            Contact::where('wa_id', $data['wa_id'])->update([
                'fullname' => $data['fullname'],
                'nro_doc'  => $data['nro_doc'] ?? null, 
                'nro_affiliate' => $data['nro_affiliate'] ?? null  
            ]);
            
            $contact = Contact::where('wa_id', $data['wa_id'])->first();
            // CREO EL NUEVO TURNO..            
            $input_date = $data['date'];
            $input_date  = date('Y-m-d', strtotime($input_date));

            $booking = new Booking;
            $booking->date = $input_date;
            $booking->status_id = 1;
            $booking->contact_id = $contact->id;

            $booking->save();

            DB::commit();
            
            return $data = [
                'code' => 200,
                'message' => 'Se ha almacenado correctamente el turno'
            ];
        } catch (\Throwable $th) {
            log::info($th);
            DB::rollBack();
            return $data = [
                'code' => 500,
                'message' => 'Se ha producido un erro'
            ];
    }
}

    public function check_booking_available($date){
        
        $date = Carbon::parse($date);
        $cant_orders = DetailDay::select('cant_orders')->where('num_day', '=', date('w', strtotime($date)))->first();

        Booking::where('date',$date->format('Y-m-d'))->where('status_id', 1)->count();
        
        if(Booking::where('date',$date->format('Y-m-d'))->where('status_id', 1)->count() < $cant_orders['cant_orders']){
            return true;
        }

        return false;
        
    }

    public function get_bookings($wa_id){
        $contact = Contact::where('wa_id', $wa_id)->first();
        $booking = Booking::where('contact_id',$contact->id)->where('status_id', 1)->first();
        if($booking){
            return Carbon::parse($booking->date)->format("d-m-Y");
        }
        return '';
    }  
    
    public function cancel_booking($wa_id){
        try {
            $contact = Contact::where('wa_id', $wa_id)->first();
    
            $status = BookingStatus::where('status', 'CANCELADO')->first();
            Booking::where('contact_id',$contact->id)->where('status_id', 1)->first()->update([
                    'status_id' => $status->id
                ]);

            return $data = [
                'code' => 200,
                'message' => 'Se ha actualizado correctamente'
            ];
        } catch (\Throwable $th) {
            log::info($th);
            return $data = [
                'code' => 500,
                'message' => 'Se ha producido un error'
            ];
        }
    }

    public function update_status(Request $request){
        try {
            Booking::where('id',$request->form['id'])->update([
                    'status_id' => $request->form['status_id']
                ]);

            return $data = [
                'code' => 200,
                'message' => 'Se ha actualizado correctamente'
            ];
        } catch (\Throwable $th) {
            log::info($th);
            return $data = [
                'code' => 500,
                'message' => 'Se ha producido un error'
            ];
        }
    }

}
