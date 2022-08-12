<?php
namespace App\Http\Controllers\Api\Whatsapp;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Message;

use Carbon\Carbon;

class WhatsappController extends Controller
{

    public function receive(Request $request){
        // $event = json_decode($request->getContent(), true);
        //Log::info($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']); 

        // return response($request['hub_challenge'], 200);

        $wa_id = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']) ? 
                 $request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']
                 : '';

        $message = isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) ?
                 $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
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



        if($message == 'menu'){
            $params = [ "messaging_product" => "whatsapp", 
                        "to"                => $wa_id,
                        "type"              => "template",
                        "template"          => [ "name" => "menu_principal",
                                                 "language" => ["code" => "es_AR"]
                                                ],
                        ];
        }else{

            switch($message){
                case '1':
                    $body = "Los proximos turnos disponibles son XXXX dias en el horario de 7:30 a 10 para confirmar su turno digite el numero del dia que quiere asistir\n1 - 12/7 de 7:30 a 10 \n2 - 14/7 de 7:30 a 10 \n3 - 15/7 de 7:30 a 10 \n4 - Necesito un turno más urgente --- en ese caso redirigir a operadora";
                    break;
                
                case '2':
                    $body = "Por favor indique el numero de orden";
                    break;

                case '3':
                    $body = "Horario de lunes a viernes de 7:30 a 18:00 hs y sábados de 7:30 a 13:00 hs. 
                             Horarios de extracciones: Lunes a sábados de 7:30 a 10:30 hs
                             Direccion: ubicacion
                             Si es de UTA y necesita solicitar un turno
                             Indicación de estudios";
                    break;
                
                case '4':
                    $body = "Para realizar domicilios le pedimos la foto de la credencial, DNI y orden médica y la dirección y las entrecalles. A la brevedad le confirmaremos disponibilidad. ";
                    break;
                
                case '5':
                    $body = "Los hisopados son sin turno de 11 a 15 de lunes a viernes y sábados de 9 a 12. 
                             Si es PCR y desea los resultados en el día puede venir de 11 a 12 o los sábados de 9 a 11. Si es antígeno demora 30 minutos el resultado. 
                             Importe del estudio
                             Si posee orden medica y lo realiza por obra social";
                    break;
                
                case '6':
                    $body = "12 horas de ayuno, cuando se analice: Colesterol, Triglicéridos, LDL y Hepatograma.
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

                case '7':
                    $body = "";
                    break;
                
                case '8':
                    $body = "";
                    break;
                
                case '9':
                    $body = "";
                    break;

                default:
                    $body = "";
                    break;
            }

            $params = [ "messaging_product" => "whatsapp", 
                        "recipient_type"    => "individual",
                        "to"                => $wa_id, 
                        "type"              => "text",
                        "preview_url"       => false,             
                        "text"              => [ "body" =>  $body]];
        }

        $url = 'https://graph.facebook.com/v13.0/106419748818148/messages';

        $http_post = Http::withHeaders([
            'Authorization' => 'Bearer EAAMnvn93Q1ABAJCbyDEJZBbQZAsVP3mHscl2A6NGtHl82cpHB3KDmofZCBhmTiZA8l6kXGFWkp536VtWF7S6Y9hA2sdNFZAIUHKZCgkybnllL57q7ZCynpeQHBE5OcQX7t494lrmfgF9lzVZCBdxlB7eVcdEjTDZBAax21n3VUvlrp2OZC8j0Fdd7kQKMArkRBrwt6B2Qo2kjNsQZDZD',
            'Content-Type'  => 'application/json'])
            ->post($url, $params);
        
        Log::info($request);
        Log::info($message);
        Log::info($wa_id);

        return response($request['hub_challenge'], 200);
    }

}