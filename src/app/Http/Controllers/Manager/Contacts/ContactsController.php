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
    public function index()
    {
        return  Inertia::render('Manager/Contact/List');
    }

    public function list(){

        $result = Contact::query();

        $length = request('length');
        $sort_by = request('sort_by') ?? 'id';
        $sort_order = request('sort_order') ?? 'DESC';

        if(request('search')){
            $search = request('search');
            $result->Where('wa_id','LIKE', '%'. $search . '%')
                    ->orWhere('name','LIKE', '%'. $search . '%')
                    ->orWhere('fullname','LIKE', '%'. $search . '%')
                    ->orWhere('nro_doc','LIKE', '%'. $search . '%');
        }
        

        $result->orderBy($sort_by, $sort_order);
        
        return  $result->paginate($length)
                        ->withQueryString()
                        ->through(fn ($user) => [
                            'id'                    => $user->id,
                            'name'                  => $user->name,
                            'fullname'              => $user->fullname,
                            'nro_doc'               => $user->nro_doc,
                            'bot_status'            => $user->bot_status,
                        ]);

    }

    public function list_dashboard(){

        $result = Contact::query();
        $result->Join(DB::raw('(select contact_id, status, created_at as date 
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
                        'id'            => $contact->id,
                        'name'          => $contact->name,
                        'wa_id'         => $contact->wa_id,
                        'bot_status'    => $contact->bot_status,
                        'message_status'       => $contact->messages()->latest()->first()->status,
                        'message'        => $contact->messages()->latest()->first()
                    ]);  
        
    }

    public function change_status_bot($id){
        try {
            
            $contact = Contact::where('id', $id)->first();

            $value = $contact->bot_status == 'CHATBOT' ? 'ASESOR' : 'CHATBOT';
            

            Contact::where('id', $id)->update([
                'bot_status' => $value
            ]);

            return response()->json(['message'=>'Contacto actualizado correctamente'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

}