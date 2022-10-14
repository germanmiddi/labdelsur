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

        $result = Contact::query();
        $result->leftJoin(DB::raw('(select contact_id, status, created_at as date 
                                        from messages 
                                        where id in (
                                                    select max(id) from  messages 
                                                    group by contact_id)
                                        ) as msg'), function ($join) {
                                    $join->on ( 'msg.contact_id', '=', 'id' );
                    });
        
        return  $result ->orderBy('msg.status', 'ASC')                
                        ->orderBy('msg.date', 'DESC')
                        ->paginate(999)
                        ->withQueryString()
                        ->through(fn ($contact) => [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'wa_id' => $contact->wa_id,
                        'message' => $contact->messages()->latest()->first(),
                    ]);  
        
    }

}