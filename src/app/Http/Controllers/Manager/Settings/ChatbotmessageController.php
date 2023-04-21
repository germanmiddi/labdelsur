<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chatbotmessage;

class ChatbotmessageController extends Controller
{
    public function list()
    {
        return Chatbotmessage::all();
     
    }

    public function update(Request $request)
    {
        try{
            Chatbotmessage::where('id', $request->id)
                          ->update(['message' => $request->message]);
        
            return response()->json(['message'=>'Mensaje actualizado'], 200);
        
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 400);
        }
    }

}
