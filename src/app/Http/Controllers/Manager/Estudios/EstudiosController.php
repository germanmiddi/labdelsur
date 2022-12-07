<?php

namespace App\Http\Controllers\Manager\Estudios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Estudio;

class EstudiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Inertia::render('Manager/Estudios/List');
    }

    public function list(){

        $result = Estudio::query();

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
                        ->through(fn ($estudio) => [
                            'id'                    => $estudio->id,
                            'title'                 => $estudio->title,
                            'description'           => $estudio->description,
                            'visible'               => $estudio->visible,
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
            $newEstudio = new Estudio();
            $data = $request->form;
            $estudio = $newEstudio->create($data);
            return response()->json(['message'=>'Estudio creado correctamente'], 200);
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
            Estudio::where('id', $request->form['id'])->update([
                'title' => $request->form['title'],
                'description' => $request->form['description'],
                'visible' => $request->form['visible']
            ]);
            return response()->json(['message'=>'Estudio Actualizado correctamente'], 200);
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
            $estudio = Estudio::where('id', $request->id)->first();
            Estudio::where('id', $request->id)->update([
                'visible' => !$estudio->visible
            ]);
            return response()->json(['message'=>'Estudio Actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
