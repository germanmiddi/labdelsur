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
    protected  $emojis;

    public function __construct(){
        $this->emojis[0] = "0️⃣";
        $this->emojis[1] = "1️⃣";
        $this->emojis[2] = "2️⃣";
        $this->emojis[3] = "3️⃣";
        $this->emojis[4] = "4️⃣";
        $this->emojis[5] = "5️⃣";
        $this->emojis[6] = "6️⃣";
        $this->emojis[7] = "7️⃣";
        $this->emojis[8] = "8️⃣";
        $this->emojis[9] = "9️⃣";
        $this->emojis[10] = "1️⃣0️⃣";
        $this->emojis[11] = "1️⃣1️⃣";
        $this->emojis[12] = "1️⃣2️⃣";
        $this->emojis[13] = "1️⃣3️⃣";
        $this->emojis[14] = "1️⃣4️⃣";
        $this->emojis[15] = "1️⃣5️⃣";
        $this->emojis[16] = "1️⃣6️⃣";
        $this->emojis[17] = "1️⃣7️⃣";
        $this->emojis[18] = "1️⃣8️⃣";
        $this->emojis[19] = "1️⃣9️⃣";
        $this->emojis[20] = "2️⃣0️⃣";
    }

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
        
        //CHECK FECHA ULTIMO MENSAJE       
        $last_date = false;
        if($prev_menu){
           $last_date = $this->check_last_date($prev_menu->created_at);
        }
        
        $setting =  Setting::where('module', 'BOOKING')->where('key', 'cant_days_booking')->first();
        
        if($prev_menu && $message != 0 && $last_date){
            $prev_step = $prev_menu->response;
            $current_step = $prev_step . '.' . $message;

            //GENERO UNA VARIABLE PARA EL SWITCH
            $steps = explode('.', $current_step);
            $step = $steps[0].'.'.$steps[1];
        }else{
            $current_step = 0;
            $step = 0;
        }   
        
        switch($step){
        
            case '0':
                $text = "Buen día 👋, soy tu asistente virtual 🤖​. \nPuedo ayudarte con los siguientes temas:\n";
                $text .= "\n ".$this->emojis[1]." Turno para atención *sólo para obra social UTA*.";
                $text .= "\n ".$this->emojis[2]." ¿Cómo obtener mis resultados?";
                $text .= "\n ".$this->emojis[3]." Horario de atención y ubicación.";
                $text .= "\n ".$this->emojis[4]." Extracciones a domicilio.";
                $text .= "\n ".$this->emojis[5]." COVID 19";
                $text .= "\n ".$this->emojis[6]." Indicaciones de estudios";
                $text .= "\n ".$this->emojis[7]." Coberturas";
                $text .= "\n ".$this->emojis[8]." Autorización de órdenes";
                $text .= "\n ".$this->emojis[9]." Presupuestos";
                
                break;


            case '0.1':
                $data = $this->manager_turnos($wa_id, $message, $prev_step);
                $current_step = $data['id'];
                $text = $data['text'];

                break;

            case '0.2':
                $text = "📒 Para acceder a su resultado debe realizar los siguientes pasos:";
                $text .= "\n\n*Paso 1* - Dirigite a este link: www…..com.ar.";
                $text .= "\n*Paso 2* - Ingresá al punto de menú _'resultados'_";
                $text .= "\n*Paso 3* - Cargá el número de orden que te dimos cuando te realizaste el estudio.";
                $text .= "\n*Paso 4* - Si no contás con el número de orden, cargá tal dato…";
                break;

            case '0.3':
                $text = "*​⌚​ Atención general:* De lunes a viernes de 7:30 a 18:00 hs y sábados de 7:30 a 13:00 hs.";
                $text .= "\n\n*​​➡️​ Horarios de extracciones:* Lunes a sábados de 7:30 a 10:30 hs. ";
                $text .= "\n\n*​➡️​ Cortisol y Curva de glucemia:* La extracción debe realizarse a las 8:00 AM.";
                $text .= "\n\n*​➡️​ Prolactina* Tiene que tener dos horas de haberse levantado y no haber hecho ningún tipo de esfuerzo o actividad física, excepto que tu médica/o te indique otra preparación.";
                $text .= "\n\n*📍​ Ubicación:* Margarita Weild 1200 Lanús Este, Prov. Buenos Aires \n📞​ 4225-0789 / 4249-8651\n✉️​ labdelsur@yahoo.com.ar";
                
                break;

            
            case '0.4':
                $text = "💬 Usted esta siendo derivado a un agente, por favor aguarde…";
                break;
            
            case '0.5':

                $data = $this->manager_covid($wa_id, $message, $prev_step);
                $current_step = $data['id'];
                $text = $data['text'];
                
                break;

            case '0.6':

                $data = $this->manager_analisis($wa_id, $message, $prev_step);
                $current_step = $data['id'];
                $text = $data['text'];
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
                $text = "No entendi eso.";
                break;
        }

        if($current_step != '0'){
            $text .= "\n\n0️⃣ Menú principal.";
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

    public function check_last_date($date){
        $date->addHour(12);
        $date_now = Carbon::now();//->tz('-3');
        if($date >= $date_now){
            return true;
        }else{
            return false;
        }
    }

    public function receive(Request $request){
        
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
                $inbound_msj->contact_id= $contact->id;
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

    public function manager_turnos($wa_id, $message, $prev_step, $text = ''){
        
        $setting =  Setting::where('module', 'BOOKING')->where('key', 'cant_days_booking')->first();
        $steps = explode('.', $prev_step);
        if(count($steps) <= 1){
            $base_step = $prev_step.'.'.$message;
        }else{
            $base_step = $steps[0].'.'.$steps[1];
            unset($steps[0], $steps[1]);
            $current_step = implode('.', $steps);
        }
        
        //Obtengo el id del menu a buscar..
        unset($steps[0], $steps[1]);
        $current_step = implode('.', $steps);
        
        
        if($message === '#' || $prev_step == 0){
            $current_step = '';
        }else if($prev_step != 0 && intval($message) > 0 && intval($message) <= intval($setting->value)){
                $current_step .= 'T';    
            }else if($current_step === 'T'){
                $current_step .= '.N';
                }else if($current_step === 'T.N'){
                    $current_step .= '.D';
                    }else{ 
                        $current_step .= '.'. $message;
                    }
        
                    switch ($current_step) {
            case '':
                $text = "🗓️ Los próximos turnos disponibles son días en el horario de ⌚️ 7:30 a 10:00 hs. \nPara confirmar su turno digite el número del día que quiere asistir:\n";
                $bookingController = new BookingController();
                $bookings = $bookingController->days_available();
                
                if($bookings['code'] == 200){
                    $pos = 1;
                    foreach ($bookings['data'] as $booking) {
                        $text .= "\n ".$this->emojis[$pos].". Dia ".Carbon::parse($booking)->format("d-m-Y").".";
                        $pos++;
                    }
                }else{
                    $text .= "\nNo tenemos disponbilidad de turnos intente con otra fecha.";
                }

                break;
            case ('T' ):
                $text = "👤 Indique el nombre del paciente, por favor:";
                break;
            
            case ('T.N'):
                $text = "📇 Indique el Nro de DNI del paciente, por favor:";
                break;
            
            case ('T.N.D'):
                
                //OBTENGO LAS OPCIONES DE FECHA..
                $fecha_options = Message::where('wa_id', $wa_id)
                            ->where('response',$base_step)
                            ->where('type', 'out')   
                            ->orderBy('updated_at', 'desc')
                            ->first();
                
                //OBTENGO LA POSICION DE LA FECHA SELECCIONADA.
                $fecha = Message::where('wa_id', $wa_id)
                            ->where('response',$base_step.'.T')
                            ->where('type', 'in')   
                            ->orderBy('updated_at', 'desc')
                            ->first();
                
                //RECUPERO LA FECHA SELECCIONADA.
                $options = preg_split('/\r\n|\r|\n/', $fecha_options->body);

                $fecha_parse = explode('.', $options[intval($fecha->body)+2]);
                $fecha = substr($fecha_parse[1], 5, 10);
                //VERIFICO LA DISPONIBILIDAD DEL TURNO.
                $bookingController = new BookingController();
                if(!$bookingController->check_booking_available($fecha)){
                    //$this->manager_turnos($wa_id, $message, '');
                    $text = "No hemos podido reservar su turno, seleccione otra opción.";

                    break;
                }
                
                //RECUPERO EL NOMBRE DEL CLIENTE
                $nombre = Message::where('wa_id', $wa_id)
                            ->where('response',$base_step.'.T.N')
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
                    $text = "✅ Estimado/a ".$nombre->body ." su turno a sido correctamente agendado para el dia ".$fecha.".";
                }else{
                    $text = "⛔ No ha sido posible realizar el registro de su turno, por favor comuniquese telefonicamente o intentelo mas tarde.";
                } 
                break;
            
            default:
                $text = "No entendi eso.";
                break;
                
        }
        if($current_step != ''){
            $text .= "\n\n#️⃣ Presione para volver al menú de turnos.";
        }

        return ['id' => $current_step == '' ? $base_step : $base_step.'.'.$current_step,
                'text' => $text];
    }

    public function manager_covid($wa_id, $message, $prev_step, $text = ''){
        
        $steps = explode('.', $prev_step);
        if(count($steps) <= 1){
            $base_step = $prev_step.'.'.$message;
        }else{
            $base_step = $steps[0].'.'.$steps[1];
            unset($steps[0], $steps[1]);
            $current_step = implode('.', $steps);
        }
        
        //Obtengo el id del menu a buscar..
        unset($steps[0], $steps[1]);
        $current_step = implode('.', $steps);
        
        
        if($message === '#' || $prev_step == 0){
            $current_step = '';
        }else {
            $current_step .= '.'. $message;
        }
        
        switch($current_step) {

            case '':

                $text = "🦠​​ *COVID 19* - Los hisopados son sin turno de ⌚ 11:00 a 15:00 hs. de lunes a viernes y sábados de ⌚ 9:00 a 12:00 hs. (test de antígeno) y de 7:30 a 9.30 (Hisopado PCR).";
                $text .= "\n📌​ Si es PCR y desea los resultados en el día puede venir de ⌚ 11:00 a 12:00 hs. o los sábados de ⌚ 7:00 a 11:00 hs.";
                $text .= "\n📌​ Si es antígeno demora 30 minutos el resultado.";
                $text .= "\n\n *_Mas Información:_*";
                $text .= "\n\n".$this->emojis[1]." Importe del estudio particular.";
                $text .= "\n".$this->emojis[2]." Si desea realizarlo por obra social / prepaga.";
                $text .= "\n".$this->emojis[3]." Hisopados a domicilio.";

                break;

            case '.1':
                $text = "El hisopado PCR  para SARS-CoV-2 🦠​ tiene un valor de $7.900 pesos con tarjeta de débito y $7.000 si abona en efectivo. Puede venir de lunes a viernes de 11:00 a 15:00 hs y sábados de 8:00 a 9:00 hs. Si desea los resultados en el día debería acercarse a las 11:00 o a las 8:00 hs. respectivamente.";
                $text .= "\n\nEl test rápido para SARS-CoV-2 tiene un valor de $4.600 pesos con tarjeta de débito y $4.000 en efectivo. En caso de que quiera realizarlo puede venir de lunes a viernes de 11:00 a 15:00 hs. y sábado de 8:00 a 12:00 hs. Obtiene el resultado en el momento.";
                $text .= "\n\nA domicilio el valor es $5.500 pesos el test rápido y $8.000 la PCR.";
                break;
            
            case '.2':
                $text = "Por favor envíe una 📷 foto de la orden (al operador).";
                break;

            case '.3':
                $text = "Por favor indique 📌 domicilio y entre calles.";
                break;
        
            default:
                $text = "No entendi eso.";
                break;
                
        }
        if($current_step != ''){
            $text .= "\n\n#️⃣ Presione para volver al menú anterior.";
        }

        return ['id' => $current_step == '' ? $base_step : $base_step.'.'.$current_step,
                'text' => $text];
    }

    public function manager_analisis($wa_id, $message, $prev_step, $text = ''){
        
        $steps = explode('.', $prev_step);
        if(count($steps) <= 1){
            $base_step = $prev_step.'.'.$message;
        }else{
            $base_step = $steps[0].'.'.$steps[1];
            unset($steps[0], $steps[1]);
            $current_step = implode('.', $steps);
        }
        
        //Obtengo el id del menu a buscar..
        unset($steps[0], $steps[1]);
        $current_step = implode('.', $steps);
        
        
        if($message === '#' || $prev_step == 0){
            $current_step = '';
        }else {
            $current_step .= '.'. $message;
        }
        
        switch($current_step) {

            case '':

                $text = "✏️​ *12 horas de ayuno*, cuando se analice: Colesterol total,  Hepatograma, colesterol HDL o LDL o Triglicéridos.";
                $text .= "\n✏️​ *8 horas de ayuno* para el resto de los análisis.";
                $text .= "\n✏️​ *Cortisol y Curva de glucemia:* La extracción debe realizarse a las 8:00 AM.";
                $text .= "\n✏️​ *Prolactina:* debe tener dos horas de haberse levantado antes de venir al laboratorio y no haber realizado actividad física ni esfuerzo alguno.";
                $text .= "\n\n🩺​ Si tiene que realizarse estudios de hormonas tiroideas y toma medicación para las tiroides ese día lo deberá tomar luego de la extracción.\n";
                $text .= "\n".$this->emojis[1]." Urocultivo mujeres.";
                $text .= "\n".$this->emojis[2]." Urocultivo hombres.";
                $text .= "\n".$this->emojis[3]." Urocultivo bebés y niñas/os.";
                $text .= "\n".$this->emojis[4]." Orina de 24 hs.";
                $text .= "\n".$this->emojis[5]." Sangre oculta en materia fecal.";
                $text .= "\n".$this->emojis[6]." Parasitológico o coprocultivo.";
                $text .= "\n".$this->emojis[7]." Cultivo de flujo.";
                $text .= "\n".$this->emojis[8]." Micológico.";
                $text .= "\n".$this->emojis[9]." PSA.";
                $text .= "\n".$this->emojis[10]." Si necesita ayuda para interpretar la orden. Será contactado con un agente...";

                break;  

            case '.1':
                $text = "💧 Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a tres horas.";
                $text .= "*A_* Se practicará un cuidadoso lavado de la zona genital con abundante agua y jabón.";
                $text .= "*B_* Secar con una toalla limpia y planchada, o con toallitas descartables.";
                $text .= "*C_* Taponar el orificio vaginal con algodón o con un tampón vaginal.";
                $text .= "*D_* Separar los labios y orinar desechando el primer chorro de la micción.";
                $text .= "*E_* Recolectar la porción media de la micción en un frasco estéril.";
                $text .= "*F_* Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.";
                break;

            case '.2':
                $text = "💧 Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a tres horas.";
                $text .= "*A_* Se practicará un cuidadoso lavado de la zona genital con abundante agua y jabón.";
                $text .= "*B_* Secar con una toalla limpia y planchada, o con toallitas descartables.";
                $text .= "*C_* Rebatir el prepucio y orinar, desechando el primer chorro de la micción.";
                $text .= "*D_* Recolectar la porción media de la micción en un frasco estéril.";
                $text .= "*E_* Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.";
                break;

            case '.3':
                $text = "🍼 Bebés y niños/as.";
                $text .= "*-* Higienizar muy bien los genitales externos con agua y jabón.";
                $text .= "*-* Recoger orina AL ACECHO en frasco estéril (una sola micción, no importa que la cantidad sea escasa). Tapar inmediatamente el frasco y conservar en heladera.";
                break;

            case '.4':
                $text = "💧 Juntar orina de 24 hs. En una o varias botella/s de plástico de agua mineral (2 litros o más) desechar la primera orina de la mañana y comenzar la recolección hasta el otro día a la misma hora con la primera orina de la mañana inclusive. Todo el contenido se debe traer al Laboratorio para realizar el estudio correspondiente. \n*_Importante:_* Se debe recolectar el total de la orina.";
                break;

            case '.5':
                $text = "💧 *Sangre oculta en materia fecal:* Condiciones previas a la recolección de la muestra:   \n\nDurante tres días consecutivos el/la paciente evitará comer carne roja y alimentos que contengan sangre. \nDeberá evitarse la ingestión de: rábanos, nabos y cacao. \nLos analgésicos y antirreumáticos no son aconsejables durante estos tres días.\n Al cuarto día recolectar en un frasco de boca ancha bien limpio y seco una porción de una deposición espontánea  (no recolectar orina).\n Aclarar si el paciente sufre de hemorroides. \nRotular con nombre y apellido.";
                break;

            case '.6':
                $text = "💧 Puede acercarse de lunes a viernes de 11:00 a 18:00 hs. o sábados de 11:00 a 13:00 hs. para pedir el material y las indicaciones necesarias.";
                break;

            case '.7':
                $text = "💧 Durante 72 hs. anteriores al estudio:";
                $text .= "\n\n⛔ No tomar antibióticos.";
                $text .= "\n⛔ No colocarse ningún tipo de crema, talco, óvulos, etc.";
                $text .= "\n⛔ No mantener relaciones sexuales.";
                $text .= "\n⛔ No realizarse ecografías transvaginales.";
                $text .= "\n⛔ No estar menstruando.";
                $text .= "\n*El día del estudio:* ⛔ No utilizar bidet.";
                break;

            case '.8':
                $text = "🗓️ 3 días antes de concurrir al Laboratorio se deben hacer baños de agua tibia y sal, 3 veces por día durante 15 minutos en la uña o uñas afectadas. \nEl día del estudio no debe tener esmaltes ni cremas.";
                break;

            case '.9':
                $text = "Abstinencia sexual al menos 48 hs. previas a la extracción.";
                $text .= "\n⛔ No haberse realizado en la semana previa tacto rectal o ecografía transrectal o biopsia.";
                $text .= "\n⛔ No haber realizado ejercicios sentado (como andar en bicicleta o a caballo) al menos 48 hs. previas a la extracción.";
                break;

            case '.10':
                $text = "💬 Usted esta siendo derivado a un agente, por favor aguarde…";
                break;
        
            default:
                $text = "No entendi eso.";
                break;
                
        }
        if($current_step != ''){
            $text .= "\n\n#️⃣ Presione para volver al menú anterior.";
        }

        return ['id' => $current_step == '' ? $base_step : $base_step.'.'.$current_step,
                'text' => $text];
    }

}