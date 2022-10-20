<?php

namespace App\Http\Controllers\Manager\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Setting;
use App\Models\Message;
use App\Models\Contact;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WhatsappController extends Controller
{
    
    
    public function sendTest(){
                
        $url    = 'https://graph.facebook.com/v13.0/106419748818148/messages';
        $params = [ "messaging_product" => "whatsapp", 
                        "to"               => "5491138175235", 
                        "type"             => "template", 
                        "template" => ["name" =>  "hello_world", 
                                    "language" =>  [ "code" => "en_US" ]
                        ]
                    ];

        $http_post = Http::withHeaders([
            'Authorization' => 'Bearer EAAMnvn93Q1ABANVg3VEjDXlhqj7PO9a4I9j7zDwLZCxgZCl7vjmK0tiB2LIHs9uZBfqZBoZA1RMko4lX2pmXlpTI6px1A7P88ivXTQKmLfOE6EllcwPdYFvK4MpOy3qkSZBHuYROOfWU4vn6qAgLxZBQCfek96erTIxOXQzGe18TxZBuLkteRw80L06w8EZC1jpkRG3PgyJsd9gZDZD', //'Basic ' . $token,
            'Content-Type'  => 'application/json'])
            ->post($url, $params);
        
        return json_decode($http_post);
    }

    public function sendMessage(Request $request){

        DB::beginTransaction();
        try {
            //Obtengo Configuraciones
            $wp_url = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
            $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();

            //Datos del contacto
            $contact = Contact::where('wa_id',$request['wa_id'])->first(); 

            //Formateo los parametros de WP
            $params = [ "messaging_product" => "whatsapp", 
                                "recipient_type"    => "individual",
                                "to"                => $request['wa_id'], 
                                "type"              => "text",
                                "preview_url"       => false,             
                                "text"              => [ "body" => $request['message'] ]];

            $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                            'Content-Type'  => 'application/json'])->post($wp_url->value, $params);

            //Almaceno el Mensaje
            $outbound_msj = new Message;
            $outbound_msj->wa_id     = $request['wa_id'];
            $outbound_msj->contact_id= $contact->id;
            $outbound_msj->type      = 'out';
            $outbound_msj->body      = $request['message']; //$message;
            $outbound_msj->status    = 'initial';
            $outbound_msj->response  = 'asesor';
            $outbound_msj->wamid     = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
            $outbound_msj->timestamp = \Carbon\Carbon::now()->timestamp;
            $outbound_msj->save();

            //Bloqueo las respuestas del Bot
            Contact::where('id', $contact->id)->update([
                'bot_status' => false
            ]);
            
            DB::Commit();
            return response()->json(['message'=>'Mensaje enviado correctamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
