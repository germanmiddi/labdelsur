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
        // dd($wa_id);
        return Message::where('wa_id', $wa_id)->get();


    }

}