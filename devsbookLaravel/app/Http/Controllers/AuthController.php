<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthFormRequest;
use App\Http\Requests\LoginFormRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login', 
                'create', 
                'unauthorized'
            ]
        ]);
        //$this->middleware('auth:api')->except('login', 'create', 'unauthorized');
    }
    
    public function unauthorized()
    {
        return response()->json(['error'=>'Não autorizado'], 401);
    }

    public function login(LoginFormRequest $request)
    {
        $array = ['error' => ''];

        $email = $request->email;
        $password = $request->password;

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);
        /*if ($token = $this->guard('api')->attempt(['email' => $email, 'password' => $password])) {
            return $this->respondWithToken($token);
        }*/
        if(!$token)
        {
            $array['error'] = 'E-mail e/ou senha errados!';
        }

        $array['token'] = $token;
        return $array;
    }

    public function logout()
    {
        auth()->logout();
        return ['error' => ''];
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return [
            'error' => '',
            'token' => $token
        ];
    }

    public function create(AuthFormRequest $request)
    {
        $array = ['error' => ''];

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
        ]);

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if(!$token)
        {
            $array['error'] = 'Ocorreu um erro';
            return $array;
        }

        $array['token'] = $token;
        return $array;
    }
}
