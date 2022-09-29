<?php

namespace App\Http\Controllers\Manager\Contacts;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Contact;

class ContactsController extends Controller
{

    public function list(){
        return Contact::with('messages')->get();
    }

}