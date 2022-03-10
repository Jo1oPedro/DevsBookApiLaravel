<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Http\Requests\CommentFormRequest;

class PostController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function like($id)
    {
        $array = ['error' => ''];

        $postExists = Post::find($id);
        if(!$postExists) {
            $array['error'] = 'Post não existe';
            return $array;
        }
        $isLiked = PostLike::where('id_post', $id)
                    ->where('id_user', $this->loggedUser['id'])
                    ->count();
        
        if($isLiked)
        {
            $pl = PostLike::where('id_post', $id)
                    ->where('id_user', $this->loggedUser['id'])
                    ->first();
            $pl->delete();

            $array['isLiked'] = false;
        }else {
            PostLike::create([
                'id_post' => $id,
                'id_user' => $this->loggedUser['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $array['isLiked'] = true;

            $likeCount = PostLike::where('id_post', $id)->count();
            $array['likeCount'] = $likeCount;
        }


        return $array;
    }

    public function comment(CommentFormRequest $request, $id)
    {
        $array = ['error' => ''];

        $txt = $request->txt;

        $postExists = Post::find($id);
        if(!$postExists)
        {
            $array['error'] = 'Post não existe';
            return $array;
        }
        $newComment = new PostComment();
        $newComment->id_post = $id;
        $newComment->id_user = $this->loggedUser['id'];
        $newComment->created_at = date('Y-m-d H:i:s');
        $newComment->body = $txt;
        $newComment->save();

        return $array;
    }
}
