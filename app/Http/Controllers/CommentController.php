<?php

namespace App\Http\Controllers;

use App\User;
use App\Comment;
use App\LikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use App\Services\DropBoxService;

class CommentController extends Controller
{

    public $dropBoxService;

    public function __construct(DropBoxService $dropBoxService) {
        $this->dropBoxService = $dropBoxService;
    }

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

    /**
     * Obtener los comentarios de un video.
     *
     * @param integer $id id del video.
     */

    public function getCommentsByVideoId($id)
    {
        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', 'users.id')
            ->select('comments.*', 'users.username', 'users.photo as user_photo')
            ->where('video_id', $id)
            ->get();

        $data = [];

        foreach($comments as $comment) {
            $record = $comment;
            $record->user_photo = $this->dropBoxService->getFileLink($comment->user_photo);

            $data = $record;
        }

        return response()->json($comments);
    }
}
