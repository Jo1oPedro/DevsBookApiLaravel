<?php

namespace App\Services;
use App\Models\User;
use Image;

class CriadorDeImagem 
{
    public function criarImagem($infos) 
    {
        $array = ['error' => ''];

        $image = $infos['request']->file($infos['type']);

        $filename = md5(time().rand(0,9999)).'.jpg';
        $destPath = public_path("/media/{$infos['public_path']}");
            
        $img = Image::make($image->path())
                ->fit($infos['width'],$infos['height'])
                ->save($destPath.'/'.$filename);
        $user = User::find($infos['loggedUser']['id']);
        $user->{$infos['type']} = $filename;
        $user->save();
        $array['url'] = url("/media/{$infos['public_path']}/".$filename);

        return $array;
    }
}