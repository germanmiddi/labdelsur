<?php

namespace App\Http\Controllers\Manager\ObrasSociales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ObraSocial;

class ObrasSocialesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Manager/Obras-sociales/List');
    }

    public function list(){

        $result = ObraSocial::query();

        $length = request('length');
        $sort_by = request('sort_by') ?? 'id';
        $sort_order = request('sort_order') ?? 'DESC';

        if(request('search')){
            $search = request('search');
            $result->Where('id','LIKE', '%'. $search . '%')
                    ->orWhere('title','LIKE', '%'. $search . '%')
                    ->orWhere('description','LIKE', '%'. $search . '%');
        }
        
        $result->orderBy($sort_by, $sort_order);
        return  $result->paginate($length)
                        ->withQueryString()
                        ->through(fn ($obra) => [
                            'id'                    => $obra->id,
                            'title'                 => $obra->title,
                            'description'           => $obra->description,
                            'visible'               => $obra->visible,
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
            $newObraSocial = new ObraSocial();
            $data = $request->form;
            $user = $newObraSocial->create($data);
            return response()->json(['message'=>'Obra Social creada correctamente'], 200);
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
            ObraSocial::where('id', $request->form['id'])->update([
                'title' => $request->form['title'],
                'description' => $request->form['description'],
                'visible' => $request->form['visible']
            ]);
            return response()->json(['message'=>'Obra Social Actualizada correctamente'], 200);
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
            $obra = ObraSocial::where('id', $request->id)->first();
            ObraSocial::where('id', $request->id)->update([
                'visible' => !$obra->visible
            ]);
            return response()->json(['message'=>'Obra Social Actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
