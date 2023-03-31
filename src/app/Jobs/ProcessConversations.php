<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

class ProcessConversations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(date("Y-m-d H:i:s") . " - Inicio del Job");

        $contacts = Contact::where([ 
                                      ['bot_status', 'WAITING'], 
                                      ['updated_at', '<=', now()->subSeconds(10) ]
                                    ])->get();
        
        
        foreach($contacts as $contact ){


            $last_message = Message::where('contact_id', $contact->id)->orderBy('id', 'desc')->first();
            
            $params = $this->set_message($contact->wa_id);
            Log::info($params);
            Contact::where('id', $contact->id)->update(['bot_status' => 'CHATBOT']);
            $this->send_message($params, $contact);

        }
      
    }

    public function set_message($wa_id){

        $emojis_0 = "0️⃣";
        $emojis_1 = "1️⃣";
        $text = 'Nuestros operadores estan ocupados. Indique la opción deseada. ';
        $text .= "\n " . $emojis_1 . " Continuar en linea";
        $text .= "\n " . $emojis_0 . " Volver al menú principal";

        return  [ "messaging_product" => "whatsapp", 
                  "recipient_type"    => "individual",
                  "to"                => $wa_id, 
                  "type"              => 'text',
                  "preview_url"       => false,             
                  "text"              => [ "body" => $text]];
    }


    public function send_message($params, $contact){

        $wp_url   = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
        $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();
        
        $http = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                    'Content-Type'  => 'application/json'])
                      ->post($wp_url->value, $params); 
    
        // $data_msg['type'] = 'out';
        // $data_msg['body'] = '';
        // $data_msg['response']  = $response['id'];
        // $data_msg['wamid']     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
        
        $msj = new Message;
        $msj->wa_id         = $contact->wa_id;
        $msj->contact_id    = $contact->id;
        $msj->type          = 'out';
        $msj->type_msg      = 'text';
        $msj->body          = $params['text']['body'];
        $msj->status        = 'initial';
        $msj->response      = 'waiting'; 
        $msj->wamid         = $http['messages'][0]['id'] ? $http['messages'][0]['id'] : '';
        $msj->timestamp     = \Carbon\Carbon::now()->timestamp;
        $msj->save();        

        Log::info($msj);
    
    }

    


}
