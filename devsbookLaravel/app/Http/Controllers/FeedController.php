<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Image;
use App\Http\Requests\FeedCreateFormRequest;
use App\Services\ValidadorDeFeed;

class FeedController extends Controller
{

    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function create(FeedCreateFormRequest $request, ValidadorDeFeed $validadorDeFeed)
    {

        $fields = $validadorDeFeed->validarFeed($request);
        $fields['body'] = $request->body;
        if($fields['error'] == "")
        {
            $newPost = new Post();
            $newPost->id_user = $this->loggedUser['id'];
            $newPost->type = $request->type;
            $newPost->created_at = date('Y-m-d H:i:s');
            $newPost->body = $fields['body'];
            $newPost->save();
        }

        $array['error'] = $fields['error'];
        return $array;
    }

}
