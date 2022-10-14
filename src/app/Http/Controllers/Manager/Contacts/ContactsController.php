<?php

namespace App\Http\Controllers\Manager\Contacts;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Contact;
use App\Models\Message;

class ContactsController extends Controller
{

    public function list(){

        /* $d= Contact::get();
        $d->with('messages')->get(); */
                                //->where('cod_equipamento', $equip->cod)
                                //->where('parecer', '1')
                               // ->messages();
                                //->rightjoin('messages', 'messages.contact_id', '=', 'contacts.id')
                                //->select('contacts.*')
                                //->orderBy('messages.id', 'Desc')
                                //->get();

        //dd($d);
        /* $result = Message::query();
        return  $result ->orderBy("status", 'DESC')
                        ->orderBy("created_at", 'DESC')
                        
                        ->paginate(999)
                        ->withQueryString()
                        ->through(fn ($message) => [
                        'contact' => $message->contact(),
                        //'message' => $message->with('contact')->latest()->first(),
                    ]); 
        } */
        //return Contact::with('messages')->get();
        $result = Contact::query();
        $result->leftJoin(DB::raw('(select contact_id, status, created_at as date from messages 
                                        where id in (
                                        select max(id) from  messages 
                                        group by contact_id)) as msg'), function ($join) {
                                    $join->on ( 'msg.contact_id', '=', 'id' );
                    });
        //$order = 'ASC';
        //$result->leftjoin('messages', 'messages.contact_id', '=', 'contacts.id')->select('*');
        return  $result/* ->orderBy("id", 'DESC') */
                        ->orderBy('msg.status', 'ASC')                
                        ->orderBy('msg.date', 'DESC')
                        ->paginate(999)
                        ->withQueryString()
                        ->through(fn ($contact) => [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'wa_id' => $contact->wa_id,
                        'message' => $contact->messages()->latest()->first(),
                        // 'service' => $order->service()->latest()->with('driver')->with('type')->first(),
                        //'client'   => $order->client()->with('address')->with('company')->first(),
                        //'order_status' => $order->status()->first(),
                        //'order_total_price' => $order->total_price,
                        //'order_unit_price' => $order->unit_price,
                        //'company' => $order->client()->with('company')->first(),
                    ]);  
        
    }

}