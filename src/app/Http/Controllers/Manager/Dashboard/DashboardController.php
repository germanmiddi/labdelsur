<?php

namespace App\Http\Controllers\Manager\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Contact;

class DashboardController extends Controller
{

    public function index(){
        return Inertia::render('Manager/Dashboard/Index', 
        [
            'contacts' => Contact::all(),
        ]);
    }

}