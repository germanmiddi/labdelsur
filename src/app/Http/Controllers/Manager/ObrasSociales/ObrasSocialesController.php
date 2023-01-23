<?php

namespace App\Http\Controllers\Manager\ObrasSociales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        
        if(request('favorite') == 'true'){
            $result->where('favorite',true);
        }

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
                            'favorite'              => $obra->favorite,
                            'url'                   => $obra->url,
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
            if (is_file($request->file)) {
                
                $nombre = Str::slug($request->title, '_').'.'.$request->file->getClientOriginalExtension();
                $path = Storage::disk('public')->put($nombre,file_get_contents($request->file->getPathName()));
                
                if($path){
                    ObraSocial::create([
                        'title' => $request->title ?? '',
                        'description' => $request->description ?? '',
                        'url' => $nombre,
                    ]);
                }else{
                    return redirect()->route('calidad.documento')->with('error', 'No se ha podido procesar el documento!');
                }             
            }else{
                ObraSocial::create([
                    'title' => $request->title ?? '',
                    'description' => $request->description ?? ''
                ]);
            }
            return response()->json(['message'=>'Obra Social creada correctamente'], 200);
        } catch (\Throwable $th) {
            dd("ERROR --".$th);
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
            if (is_file($request->file)) {
                $file_os = ObraSocial::where('id', $request->id)->first();
                    if($file_os->url){
                        Storage::disk('public')->delete($file_os->url);
                    }
                $nombre = Str::slug($request->title, '_').'.'.$request->file->getClientOriginalExtension();
                $path = Storage::disk('public')->put($nombre,file_get_contents($request->file->getPathName()));
                
                ObraSocial::where('id', $request->id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'favorite' => $request->favorite,
                    'url' => $nombre
                ]);
            }else{
                ObraSocial::where('id', $request->id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'favorite' => $request->favorite,
                ]);
            }
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
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function update_favorite(Request $request)
    {
        try {
            $obra = ObraSocial::where('id', $request->id)->first();
            ObraSocial::where('id', $request->id)->update([
                'favorite' => !$obra->favorite
            ]);
            return response()->json(['message'=>'Obra Social Actualizada correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
