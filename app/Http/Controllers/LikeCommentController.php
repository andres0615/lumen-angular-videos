<?php

namespace App\Http\Controllers;

use App\LikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeCommentController extends Controller
{
    public function get($userId, $commentId)
    {
        $likeComment = LikeComment::where('user_id', $userId)
                        ->where('comment_id', $commentId)
                        ->first();

        return response()->json($likeComment);
    }

    public function store(Request $request)
    {
        $likeComment = new LikeComment();
        $likeComment->type = $request->type;
        $likeComment->user_id = $request->user_id;
        $likeComment->comment_id = $request->comment_id;

        $likeComment->save();

        return response()->json($likeComment);
    }

    public function delete($userId, $commentId)
    {
        LikeComment::where('user_id', $userId)
                        ->where('comment_id', $commentId)
                        ->delete();

        return;
    }
}
