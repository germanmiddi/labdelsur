<?php

namespace App\Http\Controllers\Manager\Faqs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Faq;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Manager/Faqs/List');
    }

    public function list(){
        $result = Faq::query();

        $length = request('length');
        $sort_by = request('sort_by') ?? 'id';
        $sort_order = request('sort_order') ?? 'DESC';

        if(request('favorite') == 'true'){
            $result->where('favorite',true);
        }

        if(request('search')){
            $search = request('search');
            $result->Where('id','LIKE', '%'. $search . '%')
                    ->orWhere('question','LIKE', '%'. $search . '%')
                    ->orWhere('answer','LIKE', '%'. $search . '%');
        }
        
        $result->orderBy($sort_by, $sort_order);
        return  $result->paginate($length)
                        ->withQueryString()
                        ->through(fn ($faq) => [
                            'id'                => $faq->id,
                            'question'          => $faq->question,
                            'answer'            => $faq->answer,
                            'visible'           => $faq->visible,
                            'favorite'          => $faq->favorite,
                        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $newFaq = new Faq();
            $data = $request->form;
            $faq = $newFaq->create($data);
            return response()->json(['message'=>'Pregunta creada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            Faq::where('id', $request->form['id'])->update([
                'question' => $request->form['question'],
                'answer' => $request->form['answer'],
                'visible' => $request->form['visible']
            ]);
            return response()->json(['message'=>'Pregunta Actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_visibilidad(Request $request)
    {
        try {
            $faq = Faq::where('id', $request->id)->first();
            Faq::where('id', $request->id)->update([
                'visible' => !$faq->visible
            ]);
            return response()->json(['message'=>'Pregunta Actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function update_favorite(Request $request)
    {
        try {
            $faq = Faq::where('id', $request->id)->first();
            Faq::where('id', $request->id)->update([
                'favorite' => !$faq->favorite
            ]);
            return response()->json(['message'=>'Pregunta Actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
