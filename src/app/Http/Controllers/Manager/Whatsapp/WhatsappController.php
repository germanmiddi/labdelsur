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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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

    public function getUrl(Request $request){
       
        $message = Message::where('id',$request->id_msg)->first();
        $patch = Storage::disk('public')->url($request->wa_id.'/'.$message->body);
        
        $url = Storage::disk('imagenes')->download($archivo['nombre']);
        return $url;
        
        return response()->json(['message'=>'Se ha procesado correctamente','data'=>$patch], 200);
    }

    public function sendMessage(Request $request){

        DB::beginTransaction();
        try {
            $type_file = '';
            if (is_file($request->image)) {
                $extension = $request->image->getClientOriginalExtension();
                $nombre = 'out_'.Carbon::now()->format("Ymdhis").'-WP.'.$extension;
                $path = Storage::disk('public')->put($request->wa_id.'/'.$nombre,file_get_contents($request->image->getPathName()));
                
                if(!$path){    
                    return response()->json(['message'=>'No se ha podido almacenar la imagen'], 500);
                }

                if($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png'){
                    $type_file = 'image';
                }elseif($extension == 'pdf'){
                        $type_file = 'document';
                }
            }
            //Obtengo Configuraciones
            $wp_url = Setting::where('module', 'WP')->where('key', 'wp_url')->first();
            $wp_token = Setting::where('module', 'WP')->where('key', 'wp_token')->first();

            //Datos del contacto
            $contact = Contact::where('wa_id',$request->wa_id)->first();  

            $message = Message::where('id',86)->first();

            $patch = Storage::disk('public')->path($request->wa_id.'/'.$message->body);

                $ch2 = curl_init();
                $params = array("messaging_product" => "whatsapp", 
                                "file"              => curl_file_create($patch, mime_content_type($patch))
                        );

                $token = 'EAAMnvn93Q1ABAFr9sddWVwY38ZC3edwRqOcJvip8L2w2hHpiSHD7ZCsfSNJVEhAKS6xKHJ2KV7jVO48erB0QltoVueNiRnEZCbupKy8DjBdn2gVMppxtAcVC5ZAiQwC6F3bxyaucnHY6IhD2Q9oRCKO2Rp5MyHe172knm4cRo6CnRNM7mFAV';
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . $token));
                curl_setopt($ch2, CURLOPT_URL,'https://graph.facebook.com/v15.0/107765322075657/media');
                curl_setopt($ch2, CURLOPT_POST, true);
                curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");
                //$params_data=array('id_bandeja_salida'=>$value['id'], 'mail'=>''.$value['mail'].'', 'cuerpo_mail'=>''.$value['cuerpo_mail'].'', 'asunto_mail'=>''.$value['asunto_mail'].'');
                curl_setopt($ch2, CURLOPT_POSTFIELDS,$params);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch2);
                
                curl_close ($ch2);
                $data = json_decode($server_output);
                dd($data->id);  

            //VERIFICO SI POSEE TEXTO PARA ENVIAR
            if($request->text != ''){
                //Formateo los parametros de WP TEXT
                $params = [ "messaging_product" => "whatsapp", 
                                    "recipient_type"    => "individual",
                                    "to"                => $request->wa_id, 
                                    "type"              => "text",
                                    "preview_url"       => false,             
                                    "text"              => [ "body" => $request->text ]];
    
                $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token->value,
                                                'Content-Type'  => 'application/json'])->post($wp_url->value, $params);
    
                //Almaceno el Mensaje de TEXTO
                $outbound_msj = new Message;
                $outbound_msj->wa_id        = $request->wa_id;
                $outbound_msj->contact_id   = $contact->id;
                $outbound_msj->type         = 'out';
                $outbound_msj->type_msg     = 'text';
                $outbound_msj->body         = $request->text; //$message;
                $outbound_msj->status       = 'initial';
                $outbound_msj->response     = 'asesor';
                $outbound_msj->wamid        = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
                $outbound_msj->timestamp    = \Carbon\Carbon::now()->timestamp;
                $outbound_msj->save();
            }


            
            // ENVIO DE IMAGEN
            $params = [ "messaging_product" => "whatsapp", 
                "recipient_type"    => "individual",
                "to"                => $contact->wa_id, 
                "type"              => 'image',         
                "image"             => [ "id" => '1261088398065244' ]
            ];
        
            $http_post = Http::withHeaders([ 'Authorization' => 'Bearer '.$wp_token
                ,'Content-Type'  => 'application/json'
            ])->post($wp_url, $params); 
        
        //Almaceno el Mensaje de IMAGEN / PDF
        if($type_file != ''){
            $outbound_msj = new Message;
            $outbound_msj->wa_id        = $request->wa_id;
            $outbound_msj->contact_id   = $contact->id;
            $outbound_msj->type         = 'out';
            $outbound_msj->type_msg     = $type_file;
            $outbound_msj->body         = $nombre; //$message;
            $outbound_msj->status       = 'initial';
            $outbound_msj->response     = 'asesor';
            $outbound_msj->wamid        = $http_post['messages'][0]['id'] ? $http_post['messages'][0]['id'] : '';
            $outbound_msj->timestamp    = \Carbon\Carbon::now()->timestamp;
            $outbound_msj->save();
        }

        //Bloqueo las respuestas del Bot
        Contact::where('id', $contact->id)->update([
            'bot_status' => false
        ]);

        DB::Commit();
        return response()->json(['message'=>'Mensaje enviado correctamente'], 200);
    } catch (\Throwable $th) {
            dd($th);
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
