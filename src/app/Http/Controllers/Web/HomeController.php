<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

// use App\Models\Competencia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Web/Home');
    }

    public function faq()
    {
        return  Inertia::render('Web/Faq', [            
        ]);
    }   
    
    public function estudios()
    {
        return  Inertia::render('Web/Estudios', [            
        ]);
    } 

    public function osociales()
    {
        return  Inertia::render('Web/ObrasSociales', [            
        ]);
    }    


}