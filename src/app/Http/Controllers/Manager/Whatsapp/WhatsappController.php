<?php

namespace App\Http\Controllers\Manager\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhatsappController extends Controller
{
    
    
    public function sendTest(){
                  
        $url    = 'https://graph.facebook.com/v13.0/106419748818148/messages';
        $params = [ "messaging_product" => "whatsapp", 
                     "to"               => "5491138175235", 
                     "type"             => "template", 
                     "template" => [ "name" =>  "hello_world", 
                                     "language" =>  [ "code" => "en_US" ]
                     ]
                    ];

        $http_post = Http::withHeaders([
            'Authorization' => 'Bearer EAAMnvn93Q1ABANVg3VEjDXlhqj7PO9a4I9j7zDwLZCxgZCl7vjmK0tiB2LIHs9uZBfqZBoZA1RMko4lX2pmXlpTI6px1A7P88ivXTQKmLfOE6EllcwPdYFvK4MpOy3qkSZBHuYROOfWU4vn6qAgLxZBQCfek96erTIxOXQzGe18TxZBuLkteRw80L06w8EZC1jpkRG3PgyJsd9gZDZD', //'Basic ' . $token,
            'Content-Type'  => 'application/json'])
            ->post($url, $params);
        
        return json_decode($http_post);
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
