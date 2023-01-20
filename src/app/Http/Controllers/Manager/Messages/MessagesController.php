<?php

namespace App\Http\Controllers\Manager\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Message;

class MessagesController extends Controller
{

    public function list(){

        $wa_id = request('wa_id');
        
        Message::where('wa_id', $wa_id)->update(['status' => 'read']);

        return Message::where('wa_id', $wa_id)->orderBy('created_at','asc')->get();


    }

}