<?php

namespace App\Services;
use App\Models\User;
use Image;

class ValidadorDeFeed 
{
    public function validarFeed($request)
    {
        $type = $request->type;
        $body = $request->body;
        $photo = $request->file('photo');
    
        if($type == 'photo')
        {
            if(!$photo)
            {
                $array['error'] = 'Arquivo não enviado';
                return $array;
            }
            $filename = md5(time().rand(0,9999)).'.jpg';
        
            $destPath = public_path('/media/uploads');
        
            $img = Image::make($photo->path())
                    ->resize(800, null, function($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($destPath.'/'.$filename);
        
            $body = $filename;
            $array['body'] = $body;
        } else if($type == 'text')
        {
            if(!$body)
            {
                $array['error'] = 'Texto não enviado';
                return $array;
            }
        } else {
            $array['error'] = 'Tipo de postagem inexistente';
            return $array;
        }
        $array['error'] = "";
        return $array;
    }
}
