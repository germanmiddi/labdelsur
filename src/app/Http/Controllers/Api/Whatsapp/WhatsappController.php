<?php
namespace App\Http\Controllers\Api\Whatsapp;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Message;
use App\Models\Waidsession;
use App\Models\Contact;


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

        if($prev_menu && $message != 0){
            $prev_step = $prev_menu->response;
            $current_step = $prev_step . '.' . $message;
        }else{
            $current_step = 0;
        }                             

        switch($current_step){

            case '0':
                $text = "Buen día, soy tu asistente virtual. Puedo ayudarte con los siguientes temas:";
                $text .= "\n 1 - Horario de atención y ubicación";
                $text .= "\n 2 - Turno para atención -sólo para obra social UTA";
                $text .= "\n 3 - ¿Cómo obtener mis resultados?";
                $text .= "\n 4 - Extracciones a domicilio";
                $text .= "\n 5 - COVID 19";
                $text .= "\n 6 - Indicaciones de estudios";
                $text .= "\n 7 - Obras Sociales y Prepagas";
                $text .= "\n 8 - Autorización de órdenes";
                $text .= "\n 9 - Presupuestos";
                break;


            case '0.1':
                    $text = "Horario de lunes a viernes de 7:30 a 18:00 hs y sábados de 7:30 a 13:00 hs.";
                    $text .= "\n Horarios de extracciones: Lunes a sábados de 7:30 a 10:30 hs";
                    $text .= "\n Direccion: ubicacion";
                    $text .= "\n Si es de UTA y necesita solicitar un turno";
                    $text .= "\n Indicación de estudios";
                    break;

            case '0.2':
                $text = "Los proximos turnos disponibles son XXXX dias en el horario de 7:30 a 10 para confirmar su turno digite el numero del dia que quiere asistir";
                
                $text .= "\n 1 - 10/9 de 7:30 a 10";
                $text .= "\n 2 - 11/9 de 7:30 a 10";
                $text .= "\n 3 - 12/9 de 7:30 a 10";

                $text .= "\n 4 - Necesito un turno más urgente";
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
                Contact::create(['wa_id' => $wa_id, 
                                  'name' => $name]);
            }
            
            Log::info('contact' . $contact);

            $timestamp = $request['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];

            // Si devuelve false, se cambia el signo para que procese el return
            if ( !$this->check_timestamp($wa_id, $timestamp) ) { Log::info('No se procesa por timestamp'); return; }
            

            $message = isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) 
                     ? $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
                     : '' ;

            $inbound_msj = new Message;
            $inbound_msj->wa_id     = $wa_id;
            $inbound_msj->type      = 'in';
            $inbound_msj->body      = $message;
            $inbound_msj->status    = 'initial';
            $inbound_msj->response  = ''; 
            $inbound_msj->wamid     = $request['entry'][0]['changes'][0]['value']['messages'][0]['id'];
            $inbound_msj->timestamp = $timestamp;
            $inbound_msj->save();

            $response = $this->set_message($wa_id, $message);

            
            $params = [ "messaging_product" => "whatsapp", 
                        "recipient_type"    => "individual",
                        "to"                => $wa_id, 
                        "type"              => "text",
                        "preview_url"       => false,             
                        "text"              => [ "body" => $response['text'] ]];

            $url = 'https://graph.facebook.com/v14.0/107765322075657/messages';


            $http_post = Http::withHeaders([ 'Authorization' => 'Bearer EAAMnvn93Q1ABAC3H44Hixtx2yMS0ecMe5ZBhZAAMZARmcypcdog6TD0275ctx5rDiNPAEPthlQmFbar56j74VRrMmbfBUdxseIzPZCYiapYgudNv69shF7I2b1mIV8NDHFCfvg9nC6aszYc8ZBF4iAfy0raZBpsiHV4iLr4SFEESx9bJzxmLkQ',
                                             'Content-Type'  => 'application/json'])->post($url, $params);
            
            
            $outbound_msj = new Message;
            $outbound_msj->wa_id     = $wa_id;
            $outbound_msj->type      = 'out';
            $outbound_msj->body      = $response['text']; //$message;
            $outbound_msj->status    = 'initial';
            $outbound_msj->response  = $response['id']; 
            $outbound_msj->wamid     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
            $outbound_msj->timestamp = \Carbon\Carbon::now()->timestamp;
            $outbound_msj->save();
            
            Waidsession::where('wa_id',$wa_id)->delete();

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

            Log::info($response);

        }elseif (isset($request['entry'][0]['changes'][0]['value']['statuses'][0])){

            Log::info('soy un status');
            $this->receive_status($request['entry'][0]['changes'][0]['value']['statuses'][0]); 

        } 
      

        return response($request['hub_challenge'], 200);

    }

}