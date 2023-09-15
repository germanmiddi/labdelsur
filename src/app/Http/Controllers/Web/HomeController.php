<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Faq;
use App\Models\Estudio;
use App\Models\ObraSocial;
use App\Models\Setting;
use App\Models\Booking;
use App\Models\BookingContact;

use Mail;
use \Illuminate\Support\Facades\URL;

use App\Http\Controllers\Manager\Booking\BookingController;
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
        $faqs   = Faq::select('id','answer','question')->where('favorite', true)->where('visible',true)->orderby('favorite')->limit(4)->orderBy('favorite','DESC')->orderBy('question','ASC')->get();
        $obras  = ObraSocial::select('id','url')->where('favorite', true)->where('visible',true)->where('url','!=','')->limit(10)->orderBy('favorite','DESC')->orderBy('title','ASC')->get();
        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }

        return  Inertia::render('Web/Home', [
            'faqs'  => $faqs,
            'obras' => $obras,
            'links' => $links,
        ]);
    }

    public function faq()
    {
        $faqs = Faq::select('id','answer','question')->where('visible',true)->orderBy('favorite','DESC')->orderBy('question','ASC')->get();
        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }        

        return  Inertia::render('Web/Faq', [    
            'faqs' => $faqs,
            'links' => $links,            
        ]);
    }   
    
    public function estudios()
    {
        $estudios = Estudio::select('id','title','description')->where('visible',true)->orderBy('favorite','DESC')->orderBy('title','ASC')->get();
        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }  

        return  Inertia::render('Web/Estudios', [            
            'estudios' => $estudios,
            'links' => $links,                        
        ]);
    } 

    public function osociales()
    {
        $obras_img = ObraSocial::select('id','url')->where('favorite', true)->where('visible',true)->where('url','!=','')->orderBy('favorite','DESC')->orderBy('title','ASC')->limit(10)->get();
        $obras = ObraSocial::select('id','title','description')->where('visible',true)->orderBy('favorite','DESC')->orderBy('title','ASC')->get();
        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }         
        
        return  Inertia::render('Web/ObrasSociales', [    
            'obras_img' => $obras_img   ,
            'obras' => $obras,
            'links' => $links,                  
        ]);
    }    

    public function form_turno(){
        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }         
        
        $bookingController = new BookingController();
        $bookings = $bookingController->days_available();

        return  Inertia::render('Web/Booking',[
            'links' => $links, 
            'bookings' => $bookings['data'],
        ]);

    }

    public function turno_post(Request $request){

        try{
            DB::beginTransaction();
            $contact = BookingContact::where('email',$request->email)->first();
            
            if(!$contact){
                $contact = $this->_store_contact($request);
            }
    
            if(!$contact) {
                return response()->json(['message' => 'No fue posible agendar el turno'], 500);
            }

            $booking = new Booking();
            $booking->date = $request->date;        
            $booking->status_id = 1;       
            $booking->contact_id = $contact->id;
            $booking->save();

            DB::commit();
            $this->_send_email($booking, $contact);
            return response()->json(['message' => 'Turno reservado con éxito'], 200);

        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['message' => 'No fue posible agendar el turno'], 500);

        }

    }

    private function _store_contact($request){

        $contact = new BookingContact();
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->nro_afiliado = $request->nro_afiliado;
        $contact->save();

        return $contact;

    }


    private function _send_email($booking, $contact){
        $url = URL::signedRoute(
            'turno.cancelar',
            $booking->id
        );

        $data = [
            'booking' => $booking,
            'contact' => $contact,
            'url' => $url
        ];
        
        $mailFromAddress = 'send@onmedia.com.ar';
        $mailFromTitle = 'Laboratorio del Sur';
        $mailSubject = 'Turno Reservado';
    
        Mail::send('email.booking', $data, function($message) use ($data, $mailFromAddress, $mailFromTitle, $mailSubject) {
            $message->to($data['contact']->email, $data['contact']->first_name.' '.$data['contact']->last_name)
                    ->subject($mailSubject)
                    ->from($mailFromAddress, $mailFromTitle);
        });
    }

    public function turno_cancelar($id){

        $booking = Booking::where('id',$id)->with('bookingContact')->first();
        $booking->status_id = 2;
        $booking->save();

        // dd($booking->bookingContact);

        $url = route('form-turno'); 

        $data = [
            'booking' => $booking,
            'contact' => $booking->bookingContact,
            'url' => $url
        ];
        
        $mailFromAddress = 'send@onmedia.com.ar';
        $mailFromTitle = 'Laboratorio del Sur';
        $mailSubject = 'Turno Cancelado';
    
        Mail::send('email.bookingCancelled', $data, function($message) use ($data, $mailFromAddress, $mailFromTitle, $mailSubject) {
            $message->to($data['contact']->email, $data['contact']->first_name.' '.$data['contact']->last_name)
                    ->subject($mailSubject)
                    ->from($mailFromAddress, $mailFromTitle);
        });

        $settings  = Setting::where('module','EXTERNAL_URL')->get();

        $links = [];
        foreach($settings as $row){
            $links[$row['key']] = $row['value'];
        }         
        

        return  Inertia::render('Web/BookingCancelled',[
            'links' => $links, 
            
        ]);

    }

}