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
use App\Models\Chatbotmessage;

use App\Http\Controllers\Manager\Booking\BookingController;

use Carbon\Carbon;
use App\Jobs\ProcessConversations;

class WhatsappController extends Controller
{
    protected  $emojis;
    protected  $dias;
    protected  $boot_status;

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

        $this->dias[1] = "Lunes";
        $this->dias[2] = "Martes";
        $this->dias[3] = "Miercoles";
        $this->dias[4] = "Jueves";
        $this->dias[5] = "Viernes";
        $this->dias[6] = "Sabado";
        $this->dias[7] = "Domingo";

        $this->boot_status = true;

        $this->messages = Chatbotmessage::pluck('message', 'name')->toArray();
    }

    public function receive_message(){

    }

    public function receive_status($status){

        log::info($status);

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

    public function send_message($params){
        $wp_url = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
        $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();
        
        return Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                            'Content-Type'  => 'application/json'])->post($wp_url->value, $params); 
    }

    public function store_message($data){
        $msj = new Message;
            $msj->wa_id         = $data['wa_id'];
            $msj->contact_id    = $data['contact_id'];
            $msj->type          = $data['type'];
            $msj->type_msg   = $data['type_msg'];
            $msj->body          = $data['body'];
            $msj->status        = $data['status'];
            $msj->response      = $data['response']; 
            $msj->wamid         = $data['wamid'];
            $msj->timestamp     = $data['timestamp'];
            $msj->save();
    }

    public function receive(Request $request){
        
        if( isset($request['entry'][0]['changes'][0]['value']['messages'][0]) ){

            DB::beginTransaction();
            try {

                $a = json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]);
                Log::info('soy un mensaje '. $a);
                $type_msg = str_replace("\"", "",json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]['type']));

                $wp_url = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
                $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();

                $wa_id = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']) 
                    ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id']
                    : '';

                $name = isset($request['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name']) 
                    ? $request['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name']
                    : '';

                /** 
                * Las sesiones controlan el overlap de mensajes del mismo contacto
                * Si el usuario manda varios mensajes juntos, el BOT procesa solo el primero y da respuesta solo para ese mensaje
                * El resto de los mensajes que se envian, mientras procesa el primero, se pierden
                */

                $session = Waidsession::where('wa_id',$wa_id)->first(); 
    
                if($session){
                    Log::info('No se procesa por Session'); return;
                }else{  
                    Waidsession::create(['wa_id' => $wa_id]);
                }                    

                $contact = Contact::where('wa_id',$wa_id)->first(); 
                
                if(!$contact){
                    $contact = Contact::firstOrCreate(['wa_id' => $wa_id, 
                                                       'name' => $name]);
                    $contact = Contact::where('wa_id',$wa_id)->first();
                }

                /**
                 * Por motivos de la API de Whatsapp, pueden llegar mensajes anteriores a los ya procesados.
                 * Se verifica en la BD si para el contacto ya se respondieron mensajes mas nuevos al que se esta procesando
                 */
                $timestamp = $request['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
                
                // Si devuelve false, se cambia el signo para que procese el return
                if ( !$this->check_timestamp($wa_id, $timestamp) ) { Log::info('No se procesa por timestamp'); return; }
                
                Log::info('TIPO FILE: '.$type_msg);

                switch ($type_msg) {
                    case 'text':
                        $message =  isset($request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) 
                                    ? $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']
                                    : '' ;
            
                            // CONTROLA SI TIENE HABILITADO EL BOT
                            if($contact->bot_status == 'CHATBOT'){
                                
                                $response = $this->set_message($wa_id, $message);
                                
                                // STORE MESSAGES IN
                                $data_msg = [
                                    "wa_id"         => $wa_id,
                                    "contact_id"    => $contact->id,
                                    "type"          => 'in',
                                    "type_msg"      => $type_msg,
                                    "body"          => $message,
                                    "status"        => 'initial',
                                    "response"      => $response['id'], 
                                    "wamid"         => $request['entry'][0]['changes'][0]['value']['messages'][0]['id'],
                                    "timestamp"     => $timestamp,
                                ];
                                
                                $this->store_message($data_msg);
                                // SEND MESSAGES

                                $params = [ "messaging_product" => "whatsapp", 
                                            "recipient_type"    => "individual",
                                            "to"                => $wa_id, 
                                            "type"              => $type_msg,
                                            "preview_url"       => false,             
                                            "text"              => [ "body" => $response['text'] ]];
                            
                                
                                /*  $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                                                'Content-Type'  => 'application/json'])->post($wp_url->value, $params); */

                                $http_post = $this->send_message($params);
                                log::info('DATA: '.$http_post);

                                $data_msg['type']      = 'out';
                                $data_msg['body']      = $response['text'];
                                $data_msg['response']  = $response['id'];
                                $data_msg['wamid']     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
                                $data_msg['timestamp'] = \Carbon\Carbon::now()->timestamp;
                                
                                log::info($data_msg);
                                $this->store_message($data_msg);

                            }else{

                                $data_msg = [
                                    "wa_id"         => $wa_id,
                                    "contact_id"    => $contact->id,
                                    "type"          => 'in',
                                    "type_msg"      => $type_msg,
                                    "body"          => $message,
                                    "status"        => 'initial',
                                    "response"      => 'asesor', 
                                    "wamid"         => $request['entry'][0]['changes'][0]['value']['messages'][0]['id'],
                                    "timestamp"     => $timestamp,
                                ];

                                $this->store_message($data_msg);
                                log::info('Contacto: '. $contact->wa_id .'tiene el chat con el Bot desactivado');

                            }
                            
                        break;
                        
                    case ('image' and 'document'):

                        Log::info("DENTRO DE FILE AND DOCUMENT");

                            if($type_msg == 'image'){
                                $type_image = str_replace("\"", "",json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]['image']['mime_type']));
                                $type_image = explode('/', $type_image);
                            }else{
                                $type_image = str_replace("\"", "",json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]['document']['mime_type']));
                                $type_image = explode('/', $type_image);
                            }
                        
                            if($type_image[1] == 'jpeg' || $type_image[1] == 'jpg' || $type_image[1] == 'png' || $type_image[1] == 'pdf'){
                                if($type_msg == 'image'){
                                    $image_id = str_replace("\"", "",json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]['image']['id']));
                                }else{
                                    $image_id = str_replace("\"", "",json_encode($request['entry'][0]['changes'][0]['value']['messages'][0]['document']['id']));
                                }
    
                                $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                                                    'Content-Type'  => 'application/json'])->get('https://graph.facebook.com/v15.0/'.$image_id);
                                
                                $image_name = 'in_'.Carbon::now()->format("Ymdhis").'_wp.'.$type_image[1];
                                
                                $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                    'Content-Type'  => 'application/json'])->get($http_post['url']);
                                Storage::disk('wp')->put($wa_id.'/'.$image_name, $http_post);

                                // ALMACENO MENSAJE
                                $data_msg = [
                                    "wa_id"         => $wa_id,
                                    "contact_id"    => $contact->id,
                                    "type"          => 'in',
                                    "type_msg"      => $type_msg,
                                    "body"          => $image_name,
                                    "status"        => 'initial',
                                    "response"      => 'asesor', 
                                    "wamid"         => $request['entry'][0]['changes'][0]['value']['messages'][0]['id'],
                                    "timestamp"     => $timestamp,
                                ];

                                $this->store_message($data_msg);

                                // SEND MESSAGES

                                $params = [ "messaging_product" => "whatsapp", 
                                            "recipient_type"    => "individual",
                                            "to"                => $wa_id, 
                                            "type"              => 'text',
                                            "preview_url"       => false,             
                                            "text"              => [ "body" => "📎 Su archivo ha sido recibido. 📥 \n".$this->message_default(2, $wa_id) ]];
                            
                                $http_post = $this->send_message($params);
                                $data_msg['type'] = 'out';
                                $data_msg['body'] = $params['text'];
                                $data_msg['response'] = 'asesor_image';
                                $data_msg['wamid'] = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
                                $data_msg['timestamp'] = \Carbon\Carbon::now()->timestamp;

                                
                            }else{ 
                                Log::info("File no es un formato permitido");
                                $params = [ "messaging_product" => "whatsapp", 
                                            "recipient_type"    => "individual",
                                            "to"                => $wa_id, 
                                            "type"              => 'text',
                                            "preview_url"       => false,             
                                            "text"              => [ "body" => '❌❌❌ - 🔎 El archivo enviado no corresponde a una imagen o un pdf, intente enviar el archivo con otro formato. ']];
                            
                                $http_post = $this->send_message($params);
                            }

                        break;
                        
                    default:
                            log::info('Han enviado un archivo desconocido');
                        break;
                }

                Waidsession::where('wa_id',$wa_id)->delete();

                DB::Commit();

            } catch (\Throwable $th) {
                Log::info($th);
                DB::rollBack();
            }

        }elseif( isset($request['entry'][0]['changes'][0]['value']['statuses'][0]['status']) ){
            

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

    public function set_message($wa_id, $message){
        
        //CHECK ULTIMO MENSAJE
        $prev_menu = Message::where('wa_id', $wa_id)
                            ->where('response','!=','')   
                            ->orderBy('created_at', 'desc')
                            ->first();
        
        //CHECK FECHA ULTIMO MENSAJE, si pasan mas de 12hs del ultimo mensaje, se resetea el menu       
        $last_date = false;
        if($prev_menu){
            $last_date = $this->check_last_date($prev_menu->created_at);
        }
        
        // si existe un paso anterior
        // si el mensaje no es 0, o sea que no lo reinicio
        // si la fecha del ultimo mensaje es menor a 12hs
        // si el paso anterior no es asesor
        // se concatena con el nuevo mensaje
        
        if($prev_menu && $message != 0 && $last_date && $prev_menu != 'asesor'){
            // $prev_menu->response tiene el paso anterior
            $prev_step = $prev_menu->response;
            $current_step = $prev_step . '.' . $message;
        }else{
            $current_step = 0;
        }   

        switch($current_step){
            case '0':
                $text = "Hola 👋, se comunicó con *_DEL SUR ANÁLISIS CLÍNICOS_*, soy su Asistente Virtual 🤖."; 
                $text .= "\nIndique la opción deseada:\n";
                $text .= "\n ".$this->emojis[1]." 📆​ Turno para atención";
                $text .= "\n ".$this->emojis[2]." ✅ Autorizaciones de órdenes (IOMA, OSSEG, COMEI, FATSA)";
                $text .= "\n ".$this->emojis[3]." 📄 ¿Cómo obtener mis resultados?";
                $text .= "\n ".$this->emojis[4]." 📍 Horario de atención y ubicación";
                $text .= "\n ".$this->emojis[5]." 🚗 Extracciones a domicilio";
                $text .= "\n ".$this->emojis[6]." 🦠 COVID 19";
                $text .= "\n ".$this->emojis[7]." 🔬 Indicaciones de estudios";
                $text .= "\n ".$this->emojis[8]." 🏥 Coberturas";
                $text .= "\n ".$this->emojis[9]." 💲 Presupuestos";
                break;

            case strpos($current_step, "0.1") === 0: // Manejo de Turnos...

                $step = $this->cut_step('0.1', $current_step);
                $data = $this->manager_turnos($wa_id, $message, $step);
                $current_step = $this->join_step('0.1', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.2") === 0: // Manejo de Autorizaciones...

                $step = $this->cut_step('0.2', $current_step);
                $data = $this->manager_autorizaciones($wa_id, $message, $step);
                $current_step = $this->join_step('0.2', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.3") === 0: // Manejo de resultados. 

                $step = $this->cut_step('0.3', $current_step);
                $data = $this->manager_resultados($wa_id, $message, $step);
                $current_step = $this->join_step('0.3', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.4") === 0: // Manejo de Horarios

                $step = $this->cut_step('0.4', $current_step);
                $data = $this->manager_horarios($wa_id, $message, $step);
                $current_step = $this->join_step('0.4', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.5") === 0: // Manejo de Extracciones.
                
                $step = $this->cut_step('0.5', $current_step);
                $data = $this->manager_extracciones($wa_id, $message, $step);
                $current_step = $this->join_step('0.5', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.6") === 0: // Manejo de Covid

                $step = $this->cut_step('0.6', $current_step);
                $data = $this->manager_covid($wa_id, $message, $step);
                $current_step = $this->join_step('0.6', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.7") === 0: //Manejo de Analisis
                
                $step = $this->cut_step('0.7', $current_step);
                $data = $this->manager_analisis($wa_id, $message, $step);
                $current_step = $this->join_step('0.7', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.8") === 0: // Manejo de Coberturas

                $step = $this->cut_step('0.8', $current_step);
                $data = $this->manager_coberturas($wa_id, $message, $step);
                $current_step = $this->join_step('0.8', $data['id']);
                $text = $data['text'];

                break;

            case strpos($current_step, "0.9") === 0: // Manejo de Presupuestos.

                $step = $this->cut_step('0.9', $current_step);
                $data = $this->manager_presupuestos($wa_id, $message, $step);
                $current_step = $this->join_step('0.9', $data['id']);
                $text = $data['text'];

                break;
            
            
            case "waiting.1": 
                $text = $this->message_default(2, $wa_id);
                break;

            default:
                $text = $this->message_default(3);
                break;
        }

        if($current_step != '0'){
            if($this->boot_status){
                $text .= "\n\n0️⃣ Menú principal.";
            }
        }
        log::info("SALIDA: ".$current_step);
        return ['id' => $current_step,
                'text' => $text];
    }

    /**
     * Manejo de Turnos.
     * $wa_id = ID de Whatsapp.
     * $message = Mensaje recibido.
     * $current_step = Paso actual.
     * $retorno = Si es true, retorna el texto a mostrar.
     * $text = Texto a mostrar.     
     */

    public function manager_turnos($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        //Obtengo datos de Configuracion.
        $setting =  Setting::where('module', 'BOOKING')->where('key', 'cant_days_booking')->first();
        log::info("TURNOS: ".$current_step. " MSG: ". $message);
        if($current_step != ''){

            if($message === '#'){ // Vuelvo a mostrar el Menu de turnos..
                $current_step = '';
            }else if($message === '*' ){
                $current_step = str_replace('*','U',$current_step);
                }else if($message === '9'){
                    $current_step = str_replace('9','M',$current_step);
                    }else{
                        $array = explode('.',$current_step);
                        unset($array[count($array)-1]);
                        $array = implode(".",$array);
                        switch ($array) {
                            case '1':
                                $current_step = $array.".L";
                                break;
                            case '1.L':
                                $current_step = $array.".T";
                                break;
                            case '1.L.T':
                                $current_step = $array.".N";
                                break;
                            case '1.L.T.N':
                                $current_step = $array.".D";
                                break;
                            case '1.L.T.N.D':
                                $current_step = $array.'.'.$message;
                                break;
                            case '1.L.T.N.D.7':
                                $current_step = $array.'.'.$message;
                                break;
                            default:
                                //$current_step = $array.'.'.$message;
                                break;
                        }
                    }
        }

        Log::INFO('ANTES DEL SWITCH TURNOS: '. $current_step);
        switch ($current_step) {
            case '':
                
                $text .= "\nIndique la opción deseada:\n";
                $text .= "\n".$this->emojis[1]." UTA";
                $text .= "\n".$this->emojis[2]." PAMI";
                $text .= "\n".$this->emojis[3]." IOMA";
                $text .= "\n".$this->emojis[4]." SWISS Medical, OSDE, GALENO";
                $text .= "\n".$this->emojis[5]." Otras";
                $text .= "\n".$this->emojis[6]." Particular";

                break;
            case '1':
                
                $text = "🗓️ Los próximos turnos disponibles son días en el horario de ⌚️ 7:30 a 10:30 hs."; 
                $text .= "\nIndique la opción deseada:\n";
                $bookingController = new BookingController();
                $bookings = $bookingController->days_available();
                
                if($bookings['code'] == 200){
                    $pos = 1;
                    foreach ($bookings['data'] as $booking) {
                        $text .= "\n".$this->emojis[$pos].". Dia ".Carbon::parse($booking)->format("d-m-Y").".";
                        $pos++;
                    }
                    $text .= "\n".$this->emojis[9]." Mis Turnos.";
                    $text .= "\n*️⃣​ Necesito un turno más urgente.";
                }else{
                    $text .= "\nNo tenemos disponbilidad de turnos.";
                }

            break;
            case '2':
                $text = "Para PAMI puede venir sin turno de lunes a viernes de 7:30 a 10:30 hs. con fotocopia de su DNI y carnet"; 
                break;
            case '3':
                $text = "Los pacientes de IOMA deben enviar las órdenes médicas para autorizar antes de concurrir al laboratorio.";
                $text .= "Para enviar la orden a autorizar o bien si desea consultar el estado de una orden que envio previamente puede hacerlo digitando la opción:";
                $text .= "\n\n".$this->emojis[1]." ✅ Autorizaciones de órdenes"; 
                $text .= "\n\n*_Si su orden ya está autorizada puede venir sin turno de 7:30 a 10:30 hs. de lunes a sábados_*. Si posee la orden original traigala el día del estudio junto con el número de PRECARGA que le asignamos. Una vez autorizado tiene 3 meses para realizar los estudios";
                $text .= "\nSi ya envió la orden para autorizar también puede consultar el estado de la misma ingresando a www.faba.org.ar en la opción “consulta de afiliado de IOMA” con su número de DNI";
                break;

            case strpos($current_step, "3.1") === 0: // Manejo de Presupuestos.

                $step = $this->cut_step('3.1', $current_step);
                $data = $this->manager_autorizaciones($wa_id, $message, $step, false);
                $current_step = $this->join_step('3.1', $data['id']);
                $text = $data['text'];

                break;

            case '4':
                $text = "Pacientes de OSDE / SWISS MEDICAL / GALENO concurrir con la orden médica, credencial y dni sin turno de lunes a sábados de 7:30 a 10:30 hs.";
                break;                
            
            case '5':
                // $text = "El horario de extracciones y entrega de muestras es de lunes a sábados de ⌚️ 7:30 a 10:30 hs."; 
                $text = "El horario de extracciones y entrega de muestras es sin turno de lunes a sábados de ⌚️ 7:30 a 10:30 hs.";
                $text .= "\nSi desea consultar su cobertura puede hacerlo desde la siguiente opción.";
                $text .= "\n ".$this->emojis[1]." 🏥 Coberturas";
                
                break;  

            case strpos($current_step, "5.1") === 0: // Manejo de Presupuestos.

                $step = $this->cut_step('5.1', $current_step);
                $data = $this->manager_coberturas($wa_id, $message, $step, false);
                $current_step = $this->join_step('5.1', $data['id']);
                $text = $data['text'];

                break;
                
            case '6':
                $text = "El horario de extracciones y entrega de muestras es sin turno de lunes a sábados de ⌚️ 7:30 a 10:30 hs.";
                $text .= "\nSi desea consultar su presupuesto puede hacerlo desde la siguiente opción";
                $text .= "\n ".$this->emojis[1]." 💲 Presupuestos";

            break;

            case strpos($current_step, "6.1") === 0:

                $step = $this->cut_step('6.1', $current_step);
                $data = $this->manager_presupuestos($wa_id, $message, $step, false);
                $current_step = $this->join_step('6.1', $data['id']);
                $text = $data['text'];

                break;

            case ('1.L' ):
                $text = "👤 Indique el Nombre y Apellido del paciente, por favor:";
                break;
            
            case ('1.L.T'):
                $text = "📇 Indique el Nro de DNI del paciente, por favor:";
                break;
            
            case ('1.L.T.N'):
                //OBTENGO LAS OPCIONES DE FECHA..
                $fecha_options = Message::where('wa_id', $wa_id)
                            ->where('response','like', '%.1')
                            ->where('type', 'out')   
                            ->orderBy('created_at', 'desc')
                            ->first();

                //OBTENGO LA POSICION DE LA FECHA SELECCIONADA.
                $fecha = Message::where('wa_id', $wa_id)
                            ->where('response','like', '%.1.L')
                            ->where('type', 'in')   
                            ->orderBy('created_at', 'desc')
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
                            ->where('response','like', '%.1.L.T')
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
                    $text = "✅ Estimado/a ".$nombre->body ." su turno a sido correctamente agendado para el dia ".$fecha.", en el horario de ⌚️ 7:30 a 10:30 hs.";
                    
                    $text .= "\n🤔​ Recuerde consultar las indicaciones para su estudio.";
                    $text .= "\n\nPresione ".$this->emojis[7]." para ver el menu de 🔬 Indicaciones de estudios";

                    $text .= "\n\n📝 *_Puede venir en el día asignado de 7:30 a 10:30 hs. con la orden, el carnet y la autorización._* Por favor asistir con la orden firmada al dorso con DNI, firma y aclaración y lo mismo en las autorizaciones al frente. Solicitamos concurrir sin acompañantes.";
                    $text .= "\n▶ Si pertenece a la mutual (carnet dorado) no abona el coseguro y sólo abona el Acto Profesional Bioquímico de $1.800 pesos, si no tiene mutual se suma el valor del coseguro indicado por la obra social en la autorización.";
                }else{
                    $text = "⛔ No se ha sido posible realizar el registro de su turno, por favor comuniquese telefónicamente o intentelo mas tarde.";
                } 
                break;
            
            case strpos($current_step, "1.L.T.N.D") === 0: 

                Log::info("MENU TURNOS - 01");

                $step = $this->cut_step('1.L.T.N.D', $current_step);
                $data = $this->manager_analisis($wa_id, $message, $step, false);
                $current_step = $this->join_step('1.L.T.N.D', $data['id']);
                $text = $data['text'];

                break;

            case $current_step === '1.M':
                $bookingController = new BookingController();
                $booking = $bookingController->get_bookings($wa_id);
                if($booking){

                    $text = "🗓️ Usted posee el siguiente turno agendado:\n";
                    $text .= "\n - Dia ".Carbon::parse($booking)->format("d-m-Y").".";
                    $text .= "\n\n".$this->emojis[1]." para cancelar su *Turno* ⛔";
                    
                }else{
                    $text = "🗓️ Usted No posee turnos agengados:";
                }
                break;
            case $current_step === '1.M.1':
                    $bookingController = new BookingController();
                    $booking = $bookingController->cancel_booking($wa_id);
                    if($booking['code'] == 200){
                        $text = "✅ Estimado/a su turno a sido correctamente cancelado";
                    }else{
                        $text = "⛔ No se ha sido posible realizar la operacion, por favor comuniquese con un asesor.";
                    } 
                
                break;

            case $current_step === '1.U':
                $text = $this->message_default(2, $wa_id);
                break;
            
            default:
            Log::info("MENU TURNOS - 02");
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
        'text' => $text];
    }

    public function manager_autorizaciones($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        switch($current_step) {

            case '':
                $text  = "Para autorizaciones envie foto de la orden, del carnet y del bono si tiene uno. ";
                $text .= "Su orden sera revisada por un agente y pasada a autorizar en la brevedad.";
                $text .= "Cuando la misma se haya realizado le indicaremos un numero de precarga que debe tener presente al momento de asistir al laboratorio";
                $text .= "\n\n*_Si Ud._* ha dejado su orden para autorizar y desea conocer su estado digite la siguiente opción y luego indíquenos su apellido y número de precarga";
                $text .= "\n\n".$this->emojis[1]." ". $this->message_default(1);
                break;

            case '1':
                $text = $this->message_default(2, $wa_id);
                break;

            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_resultados($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        switch($current_step) {

            case '':
                $text = "📒 Para acceder a su resultado debe realizar los siguientes pasos:";
                $text .= "\n\n*Paso 1* - Dirigite a este link: https://delsur.kernitcloud.com/#/login/paciente.";
                $text .= "\n*Paso 2* - Ingresá al punto de menú _'resultados'_";
                $text .= "\n*Paso 3* - Cargá el número de orden que te dimos cuando te realizaste el estudio.";
                $text .= "\n*Paso 4* - Si no contás con el número de orden, cargá tal dato…";
                break;

            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_horarios($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        switch($current_step) {

            case '':
                $text = "*​⌚​ Atención general:* De lunes a viernes de 7:30 a 18:00 hs y sábados de 7:30 a 13:00 hs.";
                $text .= "\n\n*​​➡️​ Horarios de extracciones:* Lunes a sábados de 7:30 a 10:30 hs. ";
                $text .= "\n\n*​➡️​ Cortisol y Curva de glucemia:* La extracción debe realizarse a las 8:00 AM.";
                $text .= "\n\n*​➡️​ Prolactina* Tiene que tener dos horas de haberse levantado y no haber hecho ningún tipo de esfuerzo o actividad física, excepto que tu médica/o te indique otra preparación.";
                $text .= "\n\n*📍​ Ubicación:* Margarita Weild 1200 Lanús Este, Prov. Buenos Aires \n📞​ 4225-0789 / 4249-8651\n✉️​ labdelsur@yahoo.com.ar";
                break;

            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_extracciones($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        switch($current_step) {

            case '':
                $text = $this->message_default(2, $wa_id);
                break;

            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_covid($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }
        
        switch($current_step) {

            case '':

                $text = "🦠​​ *COVID 19* - Los hisopados son sin turno de ⌚ 11:00 a 15:00 hs. de lunes a viernes y sábados de ⌚ 9:00 a 12:00 hs.";
                $text .= "\n📌​ Si es PCR y desea los resultados en el día puede venir de lunes a viernes de ⌚ 11:00 a 12:00 hs. Si viene el día sábado obtiene el resultado el lunes antes de las 20 hs.";
                $text .= "\n📌​ Si es antígeno el resultado demora 30 minutos.";
                $text .= "\n\n *_Mas Información:_*";
                $text .= "\n\n".$this->emojis[1]." Importe del estudio particular.";
                $text .= "\n".$this->emojis[2]." Si desea realizarlo por obra social / prepaga.";
                $text .= "\n".$this->emojis[3]." Hisopados a domicilio.";

                break;

            case $current_step ===  '1':
                // $text = "El hisopado PCR  para SARS-CoV-2 🦠​ tiene un valor de $7.900 pesos con tarjeta de débito y $7.000 si abona en efectivo. Puede venir de lunes a viernes de 11:00 a 15:00 hs y sábados de 8:00 a 9:00 hs. Si desea los resultados en el día debería acercarse a las 11:00 o a las 8:00 hs. respectivamente.";
                // $text .= "\n\nEl test rápido para SARS-CoV-2 tiene un valor de $4.600 pesos con tarjeta de débito y $4.000 en efectivo. En caso de que quiera realizarlo puede venir de lunes a viernes de 11:00 a 15:00 hs. y sábado de 8:00 a 12:00 hs. Obtiene el resultado en el momento.";
                // $text .= "\n\nA domicilio el valor es $5.500 pesos el test rápido y $8.000 la PCR.";
                $text = $this->messages['covid_valor'];
                break;
            
            case $current_step ===  '2':
                $text = "Por favor envíe una 📷 foto de la orden (al operador).";
                break;

            case $current_step ===  '3':
                $text = "Por favor indique 📌 domicilio y entre calles.";
                break;
        
            default:
                if(strlen($message) > 2){
                    $text = $this->message_default(2, $wa_id);
                }else{
                    $text = $this->message_default(3);
                }
                break;
                
        }
        if($current_step != ''&& $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_analisis($wa_id, $message, $current_step, $retorno = true, $text = ''){

        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        Log::info("ESTUDIOS: ".$current_step);
        switch($current_step) {

            case '':

                $text = "✏️​ *12 horas de ayuno*, cuando se analice: Colesterol total,  Hepatograma, colesterol HDL o LDL o Triglicéridos.";
                $text .= "\n✏️​ *8 horas de ayuno* para el resto de los análisis.";
                $text .= "\n✏️​ *Cortisol y Curva de glucemia:* La extracción debe realizarse a las 8:00 AM.";
                $text .= "\n✏️​ *Prolactina:* debe tener dos horas de haberse levantado antes de venir al laboratorio y no haber realizado actividad física ni esfuerzo alguno.";
                $text .= "\n\n🩺​ Si tiene que realizarse estudios de hormonas tiroideas y toma medicación para las tiroides ese día lo deberá tomar luego de la extracción.\n";
                $text .= "\n\n Indique la opción deseada:";
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

            case $current_step === "1":
                $text = "💧 Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a tres horas.";
                $text .= "\n*A_* Se practicará un cuidadoso lavado de la zona genital con abundante agua y jabón.";
                $text .= "\n*B_* Secar con una toalla limpia y planchada, o con toallitas descartables.";
                $text .= "\n*C_* Taponar el orificio vaginal con algodón o con un tampón vaginal.";
                $text .= "\n*D_* Separar los labios y orinar desechando el primer chorro de la micción.";
                $text .= "\n*E_* Recolectar la porción media de la micción en un frasco estéril.";
                $text .= "\n*F_* Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.";
                break;

            case $current_step ===  '2':
                $text = "💧 Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a tres horas.";
                $text .= "\n*A_* Se practicará un cuidadoso lavado de la zona genital con abundante agua y jabón.";
                $text .= "\n*B_* Secar con una toalla limpia y planchada, o con toallitas descartables.";
                $text .= "\n*C_* Rebatir el prepucio y orinar, desechando el primer chorro de la micción.";
                $text .= "\n*D_* Recolectar la porción media de la micción en un frasco estéril.";
                $text .= "\n*E_* Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.";
                break;

            case $current_step ===  '3':
                $text = "🍼 Bebés y niños/as.";
                $text .= "*-* Higienizar muy bien los genitales externos con agua y jabón.";
                $text .= "*-* Recoger orina AL ACECHO en frasco estéril (una sola micción, no importa que la cantidad sea escasa). Tapar inmediatamente el frasco y conservar en heladera.";
                break;

            case $current_step ===  '4':
                $text = "💧 Juntar orina de 24 hs. En una o varias botella/s de plástico de agua mineral (2 litros o más) desechar la primera orina de la mañana y comenzar la recolección hasta el otro día a la misma hora con la primera orina de la mañana inclusive. Todo el contenido se debe traer al Laboratorio para realizar el estudio correspondiente. \n*_Importante:_* Se debe recolectar el total de la orina.";
                break;

            case $current_step ===  '5':
                $text = "💧 *Sangre oculta en materia fecal:* Condiciones previas a la recolección de la muestra:   \n\nDurante tres días consecutivos el/la paciente evitará comer carne roja y alimentos que contengan sangre. \nDeberá evitarse la ingestión de: rábanos, nabos y cacao. \nLos analgésicos y antirreumáticos no son aconsejables durante estos tres días.\n Al cuarto día recolectar en un frasco de boca ancha bien limpio y seco una porción de una deposición espontánea  (no recolectar orina).\n Aclarar si el paciente sufre de hemorroides. \nRotular con nombre y apellido.";
                break;

            case $current_step ===  '6':
                $text = "💧 Puede acercarse de lunes a viernes de 11:00 a 18:00 hs. o sábados de 11:00 a 13:00 hs. para pedir el material y las indicaciones necesarias.";
                break;

            case $current_step ===  '7':
                $text = "💧 Durante 72 hs. anteriores al estudio:";
                $text .= "\n\n⛔ No tomar antibióticos.";
                $text .= "\n⛔ No colocarse ningún tipo de crema, talco, óvulos, etc.";
                $text .= "\n⛔ No mantener relaciones sexuales.";
                $text .= "\n⛔ No realizarse ecografías transvaginales.";
                $text .= "\n⛔ No estar menstruando.";
                $text .= "\n*El día del estudio:* ⛔ No utilizar bidet.";
                break;

            case $current_step ===  '8':
                $text  = "*MICOLÓGICO UÑAS*";
                $text .= "\n*A_* Suspender medicación antimicótica, por lo menos 10 días antes de la recolección.";
                $text .= "\n*B_* No utilizar esmalte, talco, crema, aerosol, desinfectante, loción, etc. sobre la lesión por lo menos 3 días antes de la toma de muestra.";
                $text .= "\n*C_* Durante los 3 días previos a la toma, cepillar sus uñas con agua y jabón blanco por encima y por debajo de la lámina ungueal, al menos 3 veces al día. Evitar cortarlas desde la semana previa.";
                $text .= "\n*D_* Un día antes, hacer 3 baños con agua y sal. Preparados con una cuchara sopera de sal fina en un litro de agua previamente hervida y entibiada.";
                $text .= "\n*E_* ¡ATENCIÓN!  Si la lesión es en los pies, concurrir con calzado cerrado y medias.";
                $text .= "\n\n*MICOLÓGICO LESIONES EN  PIEL O CUERO CABELLUDO*";
                $text .= "\n*A_* Suspender medicación antimicótica, por lo menos 10 días antes de la recolección.";
                $text .= "\n*B_* No utilizar talco, crema, aerosol, desinfectante, loción, etc. sobre la lesión por lo menos 3 días antes de la toma de muestra.";
                $text .= "\n*C_* Lavar la zona lesionada con jabón blanco o neutro, por lo menos 3 veces al día durante los 3 días previos a la toma de  muestra.";
                $text .= "\n*D_* ¡ATENCIÓN!  Si la lesión es en los pies, concurrir con calzado cerrado y medias.";
                
                break;

            case $current_step ===  '9':
                $text = "Abstinencia sexual al menos 48 hs. previas a la extracción.";
                $text .= "\n⛔ No haberse realizado en la semana previa tacto rectal o ecografía transrectal o biopsia.";
                $text .= "\n⛔ No haber realizado ejercicios sentado (como andar en bicicleta o a caballo) al menos 48 hs. previas a la extracción.";
                break;

            case strpos($current_step, "10") === 0:
                $text = $this->message_default(2, $wa_id);
                break;
        
            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    public function manager_coberturas($wa_id, $message, $current_step, $retorno = true, $text = ''){
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }
        switch($current_step) {

            case '':

                $text .= "\nIndique la opción deseada:\n";
                $text .= "\n".$this->emojis[1]." UTA";
                $text .= "\n".$this->emojis[2]." PAMI";
                $text .= "\n".$this->emojis[3]." IOMA";
                $text .= "\n".$this->emojis[4]." SWISS Medical, OSDE, GALENO";
                $text .= "\n".$this->emojis[5]." Otras";

                break;

            case $current_step === "1":
                // Opcion UTA
                $text = "Antes de concurrir al laboratorio debe solicitar un turno puede hacerlo marcando la opción 1:";
                $text .= "\n\n".$this->emojis[1]." 📆​ Solicitar Turno.";    
            break;


                case strpos($current_step, "1.1") === 0: 
                    // Se deriva a la funcion manager_turnos
                    $step = $this->cut_step('1.1', $current_step);
                    $data = $this->manager_turnos($wa_id, $message, $step, false);
                    $current_step = $this->join_step('1.1', $data['id']);
                    $text = $data['text'];

                    break;
            
            case $current_step === "2":
                $text = "🔔 Para realizar estudios por PAMI deberá traer:";
                $text .= "\n\n✅ Fotocopia de Documento.";
                $text .= "\n✅ Fotocopia de Carnet de Afiliado.";
                $text .= "\n✅ Orden médica del médico de cabecera o del hospital donde capita.";
                $text .= "\n💉 Las extracciones son de lunes a viernes de 7:30 a 10:00 hs.";
                break;

            case $current_step === "3":
                // $text = "Los pacientes de IOMA deben enviar las órdenes médicas para autorizar antes de concurrir al laboratorio.";
                // $text .= "Para enviar la orden a autorizar o bien si desea consultar el estado de una orden que envio previamente puede hacerlo digitando la opción:";
                // $text .= "\n\n".$this->emojis[1]." ✅ Autorizaciones de órdenes"; 
                // $text .= "\n\n*_Si su orden ya está autorizada puede venir sin turno de 7:30 a 10:30 hs. de lunes a sábados_*. Si posee la orden original traigala el día del estudio junto con el número de PRECARGA que le asignamos. Una vez autorizado tiene 3 meses para realizar los estudios";
                // $text .= "\nSi ya envió la orden para autorizar también puede consultar el estado de la misma ingresando a www.faba.org.ar en la opción “consulta de afiliado de IOMA” con su número de DNI";
                $text = $this->messages['ioma'];
                break;
            
                case strpos($current_step, "3.1") === 0:

                    $step = $this->cut_step('3.1', $current_step);
                    $data = $this->manager_autorizaciones($wa_id, $message, $step, false);
                    $current_step = $this->join_step('3.1', $data['id']);
                    $text = $data['text'];

                    break;

            case $current_step === "4":
                $text = "Pacientes de OSDE / SWISS MEDICAL / GALENO concurrir con la orden médica, credencial y dni sin turno de lunes a sábados de 7:30 a 10:30 hs.";
                break;
        
            case $current_step === "5":
                $text = "Indique su obra social o prepaga. Y lo derivaremos a un agente.";
                break;

            default:
                if(strlen($message) > 3){
                    $text = $this->message_default(2, $wa_id);
                }else{
                    $text = $this->message_default(3);
                }
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        Log::INFO("VALUE ANTES DE RETURN: ".$current_step);
        return ['id' => $current_step,
                'text' => $text];

    }

    public function manager_presupuestos($wa_id, $message, $current_step, $retorno = true, $text = ''){ 
        
        if($current_step != ''){
            if($message === '#'){ // Vuelvo a mostrar el Menu del modulo
                $current_step = '';
            }
        }

        switch($current_step) {

            case '':
                $text = "💬 Por favor envíenos una foto de la orden médica / archivo de la misma para pasarle un presupuesto. Asimismo indique si posee cobertura / si lo hará particular.";
                break;

            default:
                $text = $this->message_default(3);
                break;
                
        }
        if($current_step != '' && $retorno){
            $text .= $this->message_default(4);
        }

        return ['id' => $current_step,
                'text' => $text];
    }

    // Funcion de mensajes predeterminados.
    function message_default($id, $wa_id = ''){
        switch ($id) {
            case 1: // 
                return "Contactarse con un asesor. 💁‍♂️";
                break;
            case 2: // 
                if($wa_id){
                    $this->disable_bot($wa_id);
                }
                return "💬 Usted esta siendo derivado a un agente, por favor aguarde y será contactado.";
                break;
            case 3: // 
                return "No entendi su consulta. 🤔​";
                break;
            case 4: // 
                if($this->boot_status){
                    return "\n\n#️⃣ Menú anterior.";
                }else{
                    return "";
                }
                break;
            default:
                
                break;
                
        }
    }

    // Funcion cortar step
    function cut_step($step_base, $current_step){
        
        $step = $this->str_replace_first($step_base,'',$current_step);
        $step= ltrim ($step,'.'); // Elimina el primer caracter si es un '.'

        return $step;
    }

    // Funcion unir step
    function join_step($step_base, $parse_step){
        return $parse_step == '' ? $step_base : $step_base.'.'.$parse_step;
    }

    // Disable Bot
    function disable_bot($wa_id){
        Contact::where('wa_id',$wa_id)->update([
            'bot_status' => 'WAITING'
        ]);
        $this->boot_status = false;
        $this->dispatch_job();
    }

    function str_replace_first($search, $replace, $subject) {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

    function dispatch_job(){

        $waiting_time = Setting::where('key','waiting_time')->first()->value;

        Log::info(date("Y-m-d H:i:s") . " - Inicio del dispatch");
        ProcessConversations::dispatch()->delay(now()->addMinutes($waiting_time));
        
    }



}