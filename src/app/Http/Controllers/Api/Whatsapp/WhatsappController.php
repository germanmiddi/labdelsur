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
                $text = "Buen día, soy tu asistente virtual. Puedo ayudarte con los siguientes temas:
1 - Turno para atención -sólo para obra social UTA- (lo pondría más abajo, para que el impacto no sea que uno se dedica prioritariamente a esta obra social)    
2 - ¿Cómo obtener mis resultados?
3 - Horario de atención y ubicación
4 - Extracciones a domicilio
5 - COVID 19
6 - Indicaciones de estudios
7 - Coberturas
8 - Autorización de órdenes
9 - Presupuestos";                
                break;
            case '0.1':
                $text = "Los proximos turnos disponibles son XXXX dias en el horario de 7:30 a 10 para confirmar su turno digite el numero del dia que quiere asistir
1 - 10/9 de 7:30 a 10 
2 - 11/9 de 7:30 a 10 
3 - 12/9 de 7:30 a 10 
4 - Necesito un turno más urgente";                
                break;  

            case '0.2':
                $text = " Para acceder a su resultado: ";
                $text .= "\n1 - Ingresar a www.laboratoriodelsur.com.ar solapa de resultados";
                $text .= "\n2 - Cargá el número de orden que te dimos cuando te realizaste el estudio.";

                break;

            case '0.3':
                $text = "Horario de lunes a viernes de 7:30 a 18:00 hs y sábados de 7:30 a 13:00 hs. 
Horarios de extracciones: Lunes a sábados de 7:30 a 10:30 hs
Direccion: ubicacion
Si es de UTA y necesita solicitar un turno
Indicación de estudios";
                break;
            
            case '0.4':
                $text = "Para realizar domicilios le pedimos la foto de la credencial, DNI y orden médica y la dirección y las entrecalles. A la brevedad le confirmaremos disponibilidad. ";
                break;
            
            case '0.5':
                $text = "Los hisopados son sin turno de 11 a 15 de lunes a viernes y sábados de 9 a 12. 
Si es PCR y desea los resultados en el día puede venir de 11 a 12 o los sábados de 9 a 11. Si es antígeno demora 30 minutos el resultado. 
Importe del estudio
Si posee orden medica y lo realiza por obra social";
                break;
            
            case '0.6':
                $text = "12 horas de ayuno, cuando se analice: Colesterol, Triglicéridos, LDL y Hepatograma.
8 horas de ayuno para el resto de los análisis.
               
Cortisol y Curva de glucemia: La extracción debe realizarse a las 8 AM.
Prolactina: debe tener dos horas de haberse levantado antes de venir al laboratorio y no haber realizado actividad fisica ni esfuerzo
                            
Si tiene que realizarse estudios de hormonas tiroideas y toma medicacion para las tiroides ese día lo toma luego de la extracción
                            
Urocultivo mujeres 
Urocultivo hombres 
Orina de 24 hs 
Sangre oculta en materia fecal 
Parasitologico o coprocultivo 
Cultivo de flujo
Micologicoa 
PSA 
Si necesita ayuda para interpretar la orden. Sera contactado con un agente";
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
        
        //return;
        
        if( isset($request['entry'][0]['changes'][0]['value']['messages'][0]) ){
            
            

            $a = json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]);
            Log::info('soy un mensaje '. $a);

            $wa_id = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']) 
                     ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']
                     : '';


            // $session = Waidsession::create(['wa_id' => $wa_id]);

            // Log::info('session' . $session);

            $timestamp = $request['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];

            // Si devuelve false, se cambia el signo para que procese el return
            if ( !$this->check_timestamp($wa_id, $timestamp) ) { Log::info('No se procesa por timestamp'); return; }
            

            $message = isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) 
                     ? $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
                     : '' ;
            
            $response = $this->set_message($wa_id, $message);

            
            $params = [ "messaging_product" => "whatsapp", 
                        "recipient_type"    => "individual",
                        "to"                => $wa_id, 
                        "type"              => "text",
                        "preview_url"       => false,             
                        "text"              => [ "body" => $response['text'] ]];

            $url = 'https://graph.facebook.com/v14.0/107765322075657/messages';


            $http_post = Http::withHeaders([ 'Authorization' => 'Bearer EAAMnvn93Q1ABAEM5JwtfmqXEe6btOqcXWDpmm7KBM28GS5Ewcwaqyt95BzZAlg2Ov2N8lBUVMuGpnmqdjSM6sR2eF6ciYzhVBRyZAyfyimN4vnToCKBpJtXGTvaWMYuZCX7ZAmrxA7b8umZBONpTgWHVhEdlfJRfoO759viw5stj4pvJeMhlL2DU97CDtHl5OmrZAdPBhPwgZDZD',
                                             'Content-Type'  => 'application/json'])->post($url, $params);
            
            
            $current_step = new Message;
            $current_step->wa_id     = $wa_id;
            $current_step->body      = $message;
            $current_step->status    = 'initial';
            $current_step->response  = $response['id']; 
            $current_step->wamid     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
            $current_step->timestamp = $timestamp;
            $current_step->save();
            

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