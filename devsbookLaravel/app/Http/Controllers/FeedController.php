<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\Http\Requests\FeedCreateFormRequest;
use App\Services\ValidadorDeFeed;
use App\Models\UserRelation;
use App\Models\Post;
use App\Models\User;
use App\Models\PostLike;
use App\Models\PostComment;

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

    public function read(Request $request)
    {
        $array = ['error' => ''];

        $page = intval($request->page);
        $perPage = 2;

        $users = [];
        $userList = UserRelation::where('users_from', $this->loggedUser['id'])->get();
        foreach($userList as $userItem)
        {
            $users[] = $userItem['user_to'];
        }
        $users[] = $this->loggedUser['id'];

        $postList = Post::whereIn('id_user', $users)
                    ->orderBy('created_at', 'desc')
                    ->offset($page * $perPage)
                    ->limit($perPage)
                    ->get();

        $total = Post::whereIn('id_user', $users)->count();
        $pageCount = ceil($total / $perPage);

        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);

        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;
        return $array;
    }

    private function _postListToObject($postList, $loggedId)

    {
        foreach($postList as $postKey => $postItem)
        {
            if($postItem['id_user'] == $loggedId)
            {
                $postList[$postKey]['mine'] = true;
            } else {
                $postList[$postKey]['mine'] = false;
            }

            $userInfo = User::find($postItem['id_user']);
            $userInfo['avatar'] = url('media/avatars/'.$userInfo['avatar']);
            $userInfo['cover'] = url('media/covers/'.$userInfo['cover']);
            $postList[$postKey]['user'] = $userInfo;

            $likes = PostLike::where('id_post', $postItem['id'])->count();
            $postList[$postKey]['likeCount'] = $likes;

            $isLiked = PostLike::where('id_post', $postItem['id'])
                        ->where('id_user', $loggedId)
                        ->count();
            $postList[$postKey]['liked'] = ($isLiked > 0) ? true : false;

            $comments = PostComment::where('id_post', $postItem['id'])->get();
            foreach($comments as $commentKey => $comment)
            {
                $user = User::find($comment['id_user']);
                $user['avatar'] = url('media/avatars/'.$user['avatar']);
                $user['cover'] = url('media/covers/'.$user['cover']);
                $comments[$commentKey]['user'] = $user;
            }

            $postList[$postKey]['comments'] = $comments;
        }

        return $postList;
    }

    public function userFeed(Request $request, $id = false)
    {
        $array = ['error' => ''];
        echo $id;
        if($id == false)
        {
            $id = $this->loggedUser['id'];
        }

        $page = intval($request->input('page'));
        $perPage = 2;
        
        $postList = Post::where('id_user', $id)
                    ->orderBy('created_at', 'desc')
                    ->offset($page * $perPage)
                    ->limit($perPage)
                    ->get();

        $total = Post::where('id_user', $id)->count();
        $pageCount = ceil($total / $perPage);

        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);

        $array['posts'] = $posts;
        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;
        return $array;
    }

}
