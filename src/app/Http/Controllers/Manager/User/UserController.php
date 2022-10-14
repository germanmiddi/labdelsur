<?php

namespace App\Http\Controllers\Manager\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return  Inertia::render('Manager/User/List');
    }

    public function list(){

        $result = User::query();

        $length = request('length');
        $sort_by = request('sort_by') ?? 'id';
        $sort_order = request('sort_order') ?? 'DESC';

        if(request('search')){
            $search = request('search');
            $result->Where('name','LIKE', '%'. $search . '%')
                    ->orWhere('email','LIKE', '%'. $search . '%');
        }
        

        $result->orderBy($sort_by, $sort_order);
        
        return  $result->paginate($length)
                        ->withQueryString()
                        ->through(fn ($user) => [
                            'id'                    => $user->id,
                            'name'                  => $user->name,
                            'email'                 => $user->email,
                        ]);

    }

    public function store(Request $request)
    {
        try {
            $newUser = new CreateNewUser();
            $data = $request->form;
            $user = $newUser->create($data);
            return response()->json(['message'=>'Usuario creado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $newUser = new UpdateUserProfileInformation();
            $data = $request->form;
            $user = User::find($data['id']);
            $user = $newUser->update($user, $data);
            return response()->json(['message'=>'Usuario Actualizado correctamente'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message'=>'Se ha producido un error'], 500);
        }
    }
}
