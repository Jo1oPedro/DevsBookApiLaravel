<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserFormRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function update(UpdateUserFormRequest $request) {
        $array = ['error' => ''];
        $arrayUpdate = [];
        $user = User::find($this->loggedUser['id']);
        if($request->password != null)
        {
            $request->merge(['password' => hash::make($request->password)]);
        }
        foreach($request->input() as $dados => $dado) 
        {
            if($dado != null && $dados != 'password_confirmation')
            {
                $user->{$dados} = $dado;
            }
        }
        $user->save();
        return $array;
    }
}
