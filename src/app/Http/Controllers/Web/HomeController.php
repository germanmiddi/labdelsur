<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

use App\Models\Faq;
use App\Models\Estudio;
use App\Models\ObraSocial;

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
        $faqs = Faq::select('id','answer','question')->where('favorite', true)->where('visible',true)->limit(4)->get();
        $obras = ObraSocial::select('id','url')->where('favorite', true)->where('visible',true)->where('url','!=','')->limit(10)->get();
        return  Inertia::render('Web/Home', [
            'faqs' => $faqs,
            'obras' => $obras,
        ]);
    }

    public function faq()
    {
        $faqs = Faq::select('id','answer','question')->get();
        return  Inertia::render('Web/Faq', [    
            'faqs' => $faqs        
        ]);
    }   
    
    public function estudios()
    {
        $estudios = Estudio::select('id','title','description')->where('visible',true)->get();
        return  Inertia::render('Web/Estudios', [            
            'estudios' => $estudios  
        ]);
    } 

    public function osociales()
    {
        $obras_img = ObraSocial::select('id','url')->where('favorite', true)->where('visible',true)->where('url','!=','')->limit(10)->get();
        $obras = ObraSocial::select('id','title','description')->where('visible',true)->get();
        return  Inertia::render('Web/ObrasSociales', [    
            'obras_img' => $obras_img   ,
            'obras' => $obras      
        ]);
    }    


}