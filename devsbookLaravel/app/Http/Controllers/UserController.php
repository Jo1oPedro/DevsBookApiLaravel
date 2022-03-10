<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRelation;
use App\Models\Post;
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

    public function read($id = false)
    {
        $array = ['error' => ''];
        $user['isFollowing'] = false;
        $user = $this->loggedUser;
        if($id)
        {
            if(!$user)
            {
                $array['error'] = 'Usuario inexistente';
                return $array;
            }
            $user = User::find($id);
            $hasRelation = UserRelation::where('users_from', $this->loggedUser['id'])
                                    ->where('users_to', $user['id'])
                                    ->count();
            $user['isFollowing'] = ($hasRelation > 0) ? true : false;
        }

        $user['avatar'] = url('media/avatars'.$user['avatar']);
        $user['cover'] = url('media/covers'.$user['cover']);

        $user['me'] = ($user['id'] == $this->loggedUser['id']) ? true : false;

        $dateFrom = new \DateTime($user['birthdate']);
        $dateTo = new \DateTime('today');
        $user['age'] = $dateFrom->diff($dateTo)->y;

        $user['followers'] = UserRelation::where('users_to', $user['id'])->count();
        $user['following'] = UserRelation::where('users_from', $user['id'])->count();

        $user['photoCount'] = Post::where('id_user', $user['id'])
                                ->where('type', 'photo')
                                ->count();

        
        $array['data'] = $user;
        return $array;
    }
}
