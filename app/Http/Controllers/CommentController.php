<?php

namespace App\Http\Controllers;

use App\User;
use App\Comment;
use App\LikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function all()
    {
        $comments = Comment::all();

        return response()->json($comments);
    }

    public function get($id)
    {
        $comment = Comment::find($id);

        return response()->json($comment);
    }

    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = $request->user_id;
        $comment->video_id = $request->video_id;

        $comment->save();

        return response()->json($comment);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->comment = $request->comment;

        $comment->touch();

        $comment->save();

        return response()->json($comment);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);

        LikeComment::where('comment_id', $comment->id)
            ->delete();

        $comment->delete();

        return;
    }
}
