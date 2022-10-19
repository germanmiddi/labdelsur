<?php
namespace App\Http\Controllers\Api\Whatsapp;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Message;
use App\Models\Setting;
use App\Models\Waidsession;
use App\Models\Contact;

use App\Http\Controllers\Manager\Booking\BookingController;

use Carbon\Carbon;

class WhatsappController extends Controller
{

    public function receive_message(){

    }

    public function receive_status($status){

        log::info($status);

    }

    
    public function set_message($wa_id, $message){
        $prev_menu = Message::where('wa_id', $wa_id)
                            ->where('response','!=','')   
                            ->orderBy('updated_at', 'desc')
                            ->first();
        
        $setting =  Setting::where('module', 'BOOKING')->where('key', 'cant_days_booking')->first();

        if($prev_menu && $message != 0){
            $prev_step = $prev_menu->response;
            //GENERO UN RESPONSE ESPECIAL
            if($prev_step === '0.2' && intval($message) > 0 && intval($message) <= intval($setting->value)){
                $current_step = $prev_step . '.T';    
            }else if($prev_step === '0.2.T'){
                    $current_step = $prev_step . '.N';
                }else if($prev_step === '0.2.T.N'){
                    $current_step = $prev_step . '.D';
                    }else{
                        $current_step = $prev_step . '.' . $message;
                    }

        }else{
            $current_step = 0;
        }    
                                
        switch(/* substr( */$current_step/* , 0, 3 */){

            case '0':
                $text = "Buen día, soy tu asistente virtual. Puedo ayudarte con los siguientes temas:";
                $text .= "\n 1️⃣ - Horario de atención y ubicación";
                $text .= "\n 2 - Turno para atención - sólo para obra social UTA";
                $text .= "\n 3 - ¿Cómo obtener mis resultados?";
                $text .= "\n 4 - Extracciones a domicilio";
                $text .= "\n 5 - COVID 19";
                $text .= "\n 6 - Indicaciones de estudios";
                $text .= "\n 7 - Obras Sociales y Prepagas";
                $text .= "\n 8 - Autorización de órdenes";
                $text .= "\n 9 - Presupuestos 🤖";
                
                break;


            case '0.1':
                    $text = "Detalle de horario y atencion";
                    break;

            case '0.2':
                //$this->manager_turnos($wa_id, $message, substr($current_step, 3));
                
                $text = "Dias disponibles, para confirmar su turno digite el numero del dia que quiere asistir:";
                $bookingController = new BookingController();
                $bookings = $bookingController->days_available();
                
                if($bookings['code'] == 200){
                    $pos = 1;
                    foreach ($bookings['data'] as $booking) {
                        $text .= "\n ".$pos." - Dia ".Carbon::parse($booking)->format("d-m-Y")." en el horario de 7:30 a 10:30 hs";
                        $pos++;
                    }
                }else{
                    $text .= "\nNo tenemos disponbilidad de turnos intente con otra fecha.";
                }
                break;
                
            // GESTION DE TURNOS...
            case ('0.2.T' ):
                $text = "Indique el nombre del paciente, por favor:";
                break;
            
            case ('0.2.T.N'):
                $text = "Indique el Nro de DNI del paciente, por favor:";
                break;
            
            case ('0.2.T.N.D'):
                
                //OBTENGO LAS OPCIONES DE FECHA..
                $fecha_options = Message::where('wa_id', $wa_id)
                            ->where('response','0.2')
                            ->where('type', 'out')   
                            ->orderBy('updated_at', 'desc')
                            ->first();
                
                //OBTENGO LA POSICION DE LA FECHA SELECCIONADA.
                $fecha = Message::where('wa_id', $wa_id)
                            ->where('response','0.2.T')
                            ->where('type', 'in')   
                            ->orderBy('updated_at', 'desc')
                            ->first();
                
                //RECUPERO LA FECHA SELECCIONADA.
                $options = preg_split('/\r\n|\r|\n/', $fecha_options->body);
                $row = 0;
                $selected_row = '';
                foreach ($options as $op) {
                    if(intval(substr($op, 0, 3)) == intval($fecha->body)){
                        $selected_row = $row;
                    }
                    $row++;
                }
                $position = array_search(' '.$fecha->body.' -', $options, false);
                $fecha = substr($options[$selected_row], 9, 10);
                
                //VERIFICO LA DISPONIBILIDAD DEL TURNO.
                $bookingController = new BookingController();
                if(!$bookingController->check_booking_available($fecha)){
                    //$this->manager_turnos($wa_id, $message, '');
                    $text = "Lo sentimos el turno seleccionado ha sido tomado por otro usuario. \nIndique 0 (Cero) para volver al menú principal";

                    break;
                }
                
                //RECUPERO EL NOMBRE DEL CLIENTE
                $nombre = Message::where('wa_id', $wa_id)
                            ->where('response','0.2.T.N')
                            ->where('type', 'in')   
                            ->orderBy('updated_at', 'desc')
                            ->first();

                //RECUPERO EL DNI DEL CLIENTE.
                $dni = $message;
                $form = [
                    'wa_id'     => $wa_id,
                    'fullname'  => $nombre->body,
                    'nro_doc'   => $dni,
                    'date'      => $fecha
                ];
                $bookingController = new BookingController();
                $bookings = $bookingController->store_booking($form);
                if($bookings['code'] == 200){
                    $text = "Estimado/a ".$nombre->body ." su turno a sido correctamente agendado para el dia ".$fecha.".";
                }else{
                    $text = "No ha sido posible realizar el registro de su turno, por favor comuniquese telefonicamente o intentelo mas tarde.";
                }
                break;

            case '0.3':
                $text = " Para acceder a su resultado: ";
                $text .= "\n 1 - Ingresar a www.laboratoriodelsur.com.ar solapa de resultados";
                $text .= "\n 2 - Cargá el número de orden que te dimos cuando te realizaste el estudio.";

                break;

            
            case '0.4':
                $text = "Para realizar domicilios le pedimos la foto de la credencial, DNI y orden médica y la dirección y las entrecalles. A la brevedad le confirmaremos disponibilidad. ";
                break;
            
            case '0.5':
                $text = "Los hisopados son sin turno de 11 a 15 de lunes a viernes y sábados de 9 a 12.";
                $text .= "\n Si es PCR y desea los resultados en el día puede venir de 11 a 12 o los sábados de 9 a 11. Si es antígeno demora 30 minutos el resultado. ";
                $text .= "\n Importe del estudio";
                $text .= "\n Si posee orden medica y lo realiza por obra social";
                break;
            
            case '0.6':
                $text = "12 horas de ayuno, cuando se analice: Colesterol, Triglicéridos, LDL y Hepatograma.";
                $text .= "\n 8 horas de ayuno para el resto de los análisis.";
                $text .= "\n Cortisol y Curva de glucemia: La extracción debe realizarse a las 8 AM.";
                $text .= "\n Prolactina: debe tener dos horas de haberse levantado antes de venir al laboratorio y no haber realizado actividad fisica ni esfuerzo";
                $text .= "\n Si tiene que realizarse estudios de hormonas tiroideas y toma medicacion para las tiroides ese día lo toma luego de la extracción";
                $text .= "\n Urocultivo mujeres ";
                $text .= "\n Urocultivo hombres ";
                $text .= "\n Orina de 24 hs ";
                $text .= "\n Sangre oculta en materia fecal ";
                $text .= "\n Parasitologico o coprocultivo ";
                $text .= "\n Cultivo de flujo";
                $text .= "\n Micologicoa ";
                $text .= "\n PSA ";
                $text .= "\n Si necesita ayuda para interpretar la orden. Sera contactado con un agente";
                break;

            case '0.7':
                $text = "";
                break;
            
            case '0.8':
                $text = "";
                break;
            
            case '0.9':
                $text = "";
                break;
            default:
                $text = "No entendi eso. Indique 0 (Cero) para volver al menú principal";
                break;
        }

        return ['id' => $current_step,
                'text' => $text];

    }

    public function check_timestamp($wa_id, $timestamp){

        //Busco en la base un mensaje mas nuevo que el mensaje entrante
        $message = Message::where('wa_id',$wa_id)
                          ->where('timestamp', '>', $timestamp)->first();
        
        if($message){
            //Si encuentro un mensaje mas nuevo en la base, no se procesa el mensaje entrante
            Log::info('pase por el true, devuelvo false');
            return false; 
        }else{
            //Si el mensaje entrante es mas nuevo, se procesa el mensaje
            Log::info('pase por el false, devuelvo true');
            return true;
        }

    }

    public function receive(Request $request){
        
        // return response($request['hub_challenge'], 200);
        if( isset($request['entry'][0]['changes'][0]['value']['messages'][0]) ){

            DB::beginTransaction();
            try {
                $a = json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]);
                Log::info('soy un mensaje '. $a);

                $wa_id = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']) 
                    ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']
                    : '';

                $name = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name']) 
                    ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name']
                    : '';

                $session = Waidsession::where('wa_id',$wa_id)->first(); 
    
                if($session){
                    Log::info('No se procesa por Session'); return;
                }else{  
                    Waidsession::create(['wa_id' => $wa_id]);
                }                    

                $contact = Contact::where('wa_id',$wa_id)->first(); 
                
                if(!$contact){
                    $contact = Contact::create(['wa_id' => $wa_id, 
                                    'name' => $name]);
                }

                $timestamp = $request['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];

                // Si devuelve false, se cambia el signo para que procese el return
                if ( !$this->check_timestamp($wa_id, $timestamp) ) { Log::info('No se procesa por timestamp'); return; }
            

                $message = isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) 
                        ? $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
                        : '' ;

                $response = $this->set_message($wa_id, $message);
                
                $inbound_msj = new Message;
                $inbound_msj->wa_id     = $wa_id;
                $inbound_msj->contact_id     = $contact->id;
                $inbound_msj->type      = 'in';
                $inbound_msj->body      = $message;
                $inbound_msj->status    = 'initial';
                //$inbound_msj->response  = ''; 
                $inbound_msj->response  = $response['id']; 
                $inbound_msj->wamid     = $request['entry'][0]['changes'][0]['value']['messages'][0]['id'];
                $inbound_msj->timestamp = $timestamp;
                $inbound_msj->save();

                
                $params = [ "messaging_product" => "whatsapp", 
                            "recipient_type"    => "individual",
                            "to"                => $wa_id, 
                            "type"              => "text",
                            "preview_url"       => false,             
                            "text"              => [ "body" => $response['text'] ]];
            
            
                $wp_url = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
                $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();

                //$url = 'https://graph.facebook.com/v14.0/107765322075657/messages';

                $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,//EAAMnvn93Q1ABALdBvkY0T4d57N3GsbXAQgHxvsE0teRq9FhlDLid2V0yNMNVOnH1ZCuYIEDLf2eK2iF8FPjLLaWV5UKJebCAuVJbOBzkzMM4O9Ex8EOoDOBS834XVyKUo5bHZCDSoQ3iSdOFZCV1H1ZC0RZBmQMhhpS8FBANM7YnzR8GUEFxANe3P6KBPlZAgZAPnjbPZBOOGAZDZD',
                                                'Content-Type'  => 'application/json'])->post($wp_url->value, $params);
                $outbound_msj = new Message;
                $outbound_msj->wa_id     = $wa_id;
                $outbound_msj->contact_id= $contact->id;
                $outbound_msj->type      = 'out';
                $outbound_msj->body      = $response['text']; //$message;
                $outbound_msj->status    = 'initial';
                $outbound_msj->response  = $response['id']; 
                $outbound_msj->wamid     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
                $outbound_msj->timestamp = \Carbon\Carbon::now()->timestamp;
                $outbound_msj->save();
                
                Waidsession::where('wa_id',$wa_id)->delete();
                DB::Commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }elseif( isset($request['entry'][0]['changes'][0]['value']['statuses'][0]['status']) ){
            
            // $b = json_encode($request['entry'][0]['changes']);
            // Log::info('soy un status'. $b ); //$request['entry'][0]['changes'][0]['value']['statuses'][0]['status']);

            // $message = Message::where('wamid', $request['entry'][0]['changes'][0]['value']['statuses'][0]['id'])->first();
            // $message->status = $request['entry'][0]['changes'][0]['value']['statuses'][0]['status'];
            // $message->save();
            

        }

        
        return response($request['hub_challenge'], 200);

    }

    public function _receive(Request $request){
        

        if(isset($request['entry'][0]['changes'][0]['value']['messages'][0])){
            
            Log::info('soy un mensaje');   

            $wa_id = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']) 
                     ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']
                     : '';

            $message = isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) 
                       ? $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
                       : '' ;                 

            $prev_step = Message::where('wa_id', $wa_id)
                                ->where('status', 'open')
                                ->get();

            if($prev_step){
                
            }else{
                
            }

            $current_step = new Message;
            $current_step->wa_id  = $wa_id;
            $current_step->body   = $message;
            $current_step->status = 'open';
            $current_step->save();
            $body = '';

            // Pasos
            // ID   -   Step - previo
            // 0    -   0    -   1   -   menu - 
            // 1    -   1    -   0   -   Turno para atención por UTA  
            // 2    -   2    -   0   -   ¿Cómo obtener mis resultados?
            // 3    -   3    -   0   -   Horario de atención y ubicación
            // 4    -   4    -   0   -   Domicilios
            // 5    -   5    -   0   -   COVID 19
            // 6    -   6    -   0   -   Indicaciones de estudios
            // 7    -   7    -   0   -   Coberturas
            // 8    -   8    -   0   -   Autorización de órdenes
            // 9    -   9    -   0   -   Presupuestos
            // 10   -          
            // 11   -    
            // 12   -    
            // 13   -    
            // 14   -    
            // 15   -    
            // 16   -    



            // if($message == ''){
            //     $params = [ "messaging_product" => "whatsapp", 
            //                 "to"                => $wa_id,
            //                 "type"              => "template",
            //                 "template"          => [ "name" => "menu_principal",
            //                                             "language" => ["code" => "es_AR"]
            //                                         ],
            //                 ];
            // }else{

            switch($message){

                case 'menu':
                    $body = "buen dia ";
                    break;
                case '1':
                    $body = "";
                    break;
                
                

                default:
                    $body = "No entendi tu mensaje";
                    break;
            
            }

            $params = [ "messaging_product" => "whatsapp", 
                        "recipient_type"    => "individual",
                        "to"                => $wa_id, 
                        "type"              => "text",
                        "preview_url"       => false,             
                        "text"              => [ "body" =>  $body]];
            //}

            $url = 'https://graph.facebook.com/v14.0/107765322075657/messages';


            $http_post = Http::withHeaders([ 'Authorization' => 'Bearer EAAMnvn93Q1ABALk779sptZC2X6PeXNUG6aZBII1BMh4jcNifUVCDfxsIYIWs8z8r7KdLWuYgVDIZCyU5JaKZCoe4KZCWqkrV6lRSoAGyR5xHlikQEYI1vR90ZBkpUjK0DZCpEun6ZCi5IAXhMF23KqyMMSMjKy08wDXckZA6p9jTHEIIZA4aOPw5xI9TXOlCkBUCNZCYiYIlABt4QZDZD',
                                             'Content-Type'  => 'application/json'])->post($url, $params);
            
            $response = json_decode($http_post);

        }elseif (isset($request['entry'][0]['changes'][0]['value']['statuses'][0])){

            Log::info('soy un status');
            $this->receive_status($request['entry'][0]['changes'][0]['value']['statuses'][0]); 

        } 
      

        return response($request['hub_challenge'], 200);

    }

    public function manager_turnos($wa_id, $message, $current_step, $text = ''){

       /*  switch ($current_step) {
            $text = '';
            
            
            case 'value':
                # code...
                break;
            
            default:
                $text .= "Dias disponibles, para confirmar su turno digite el numero del dia que quiere asistir:";
                $bookingController = new BookingController();
                $bookings = $bookingController->days_available();
                
                if($bookings['code'] == 200){
                    $pos = 1;
                    foreach ($bookings['data'] as $booking) {
                        $text .= "\n ".$pos." - Dia ".Carbon::parse($booking)->format("d-m-Y")." en el horario de 7:30 a 10:30 hs";
                        $pos++;
                    }
                }else{
                    $text .= "\nNo tenemos disponbilidad de turnos intente con otra fecha.";
                }
                return $text;
                break;
        } */
    }

}