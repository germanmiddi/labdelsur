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
                            'fullname'                 => $user->fullname,
                            'nro_doc'                 => $user->nro_doc,
                        ]);

    }

    public function list_dashboard(){

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