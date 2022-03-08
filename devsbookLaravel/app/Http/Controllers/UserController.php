<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Requests\AvatarFormRequest;
use App\Http\Requests\CoverFormRequest;
use Illuminate\Support\Facades\Hash;
use Image;
use App\Services\CriadorDeImagem;

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

    public function updateAvatar(AvatarFormRequest $request, CriadorDeImagem $criadorDeImagem)
    {
        $infos = [
            'width' => '200',
            'height' => '200',
            'type' => 'avatar',
            'loggedUser' => $this->loggedUser,
            'request' => $request,
            'public_path' => 'avatars',
        ];
        return $criadorDeImagem->criarImagem($infos);
    }

    public function updateCover(CoverFormRequest $request, CriadorDeImagem $criadorDeImagem)
    {
        $infos = [
            'width' => '850',
            'height' => '310',
            'type' => 'cover',
            'loggedUser' => $this->loggedUser,
            'request' => $request,
            'public_path' => 'covers',
        ];
        return $criadorDeImagem->criarImagem($infos);
    }
}
