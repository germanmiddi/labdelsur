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

            $params = $this->set_message($contact->wa_id);
            $this->send_message($params);

        }
      
    }

    public function set_message($wa_id){

        $emojis_1 = "1️⃣";
        $text = 'Nuestros operadores estan ocupados. Indique la opción deseada. ';
        $text .= "\n " . $emoji_1 . " Continuar en linea";
        $text .= "\n #️⃣ Volver al menú anterior";

        return  [ "messaging_product" => "whatsapp", 
                    "recipient_type"    => "individual",
                    "to"                => $wa_id, 
                    "type"              => 'text',
                    "preview_url"       => false,             
                    "text"              => [ "body" => $text]];

        

    }


    public function send_message($params){

        $wp_url   = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
        $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();
        
        $http = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                    'Content-Type'  => 'application/json'])
                      ->post($wp_url->value, $params); 
    
        $data_msg['type'] = 'out';
        $data_msg['body'] = $params['text'];
        $data_msg['response']  = $response['id'];
        $data_msg['wamid']     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
        $data_msg['timestamp'] = \Carbon\Carbon::now()->timestamp;
        log::info($data_msg);
        $this->store_message($data_msg);
    
    
    }

}
