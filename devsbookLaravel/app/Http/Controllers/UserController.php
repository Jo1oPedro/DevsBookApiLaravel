<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Requests\AvatarFormRequest;
use Illuminate\Support\Facades\Hash;
use Image;

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

    public function updateAvatar(AvatarFormRequest $request)
    {
        $array = ['error' => ''];

        $image = $request->file('avatar');

        $filename = md5(time().rand(0,9999)).'.jpg';
        $destPath = public_path('/media/avatars');
            
        $img = Image::make($image->path())
                ->fit(200,200)
                ->save($destPath.'/'.$filename);
        $user = User::find($this->loggedUser['id']);
        $user->avatar = $filename;
        $user->save();
        $array['url'] = url('/media/avatars/'.$filename);

        return $array;
    }
}
