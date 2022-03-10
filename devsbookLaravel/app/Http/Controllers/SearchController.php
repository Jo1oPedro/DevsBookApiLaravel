<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentFormRequest;
use App\Models\User;

class SearchController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function search(CommentFormRequest $request)
    {
        $array = ['error' => '', 'users' => [] ];

        $txt = $request->txt;

        $userList = User::where('name', 'like', '%'.$txt.'%')->get();

        foreach($userList as $userItem) {
            $array['users'][] = [
                'id' => $userItem['id'],
                'name' => $userItem['name'],
                'avatar' => url('media/avatars/'.$userItem['avatar']),
            ];
        }
        
        return $array;
    }

}
