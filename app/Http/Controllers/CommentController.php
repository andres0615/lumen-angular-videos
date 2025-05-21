<?php

namespace App\Http\Controllers;

use App\User;
use App\Comment;
use App\LikeComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use App\Services\DropBoxService;
use Auth;
use Error;
use Exception;
use App\Services\FileService;

class CommentController extends Controller
{
    public $dropBoxService;
    public $fileService;

    public function __construct(DropBoxService $dropBoxService, FileService $fileService)
    {
        $this->dropBoxService = $dropBoxService;
        $this->fileService = $fileService;
    }

    public function all()
    {
        // $comments = Comment::all();
        $commentModel = new Comment();
        $comments = $commentModel->getAll();

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

        $data = $comment->toArray();

        // $userPhoto = $this->dropBoxService->getFileLink(Auth::user()->photo);
        $userPhoto = $this->fileService->getFileLink(Auth::user()->photo);

        $data['user_photo'] = $userPhoto;
        $data['username'] = Auth::user()->username;
        $data['comment_ago'] = $comment->getDateAgo();

        return response()->json($data);
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

        try{

            $commentModel = new Comment();
            $comments = $commentModel->getCommentsByVideoId($id);

            return response()->json($comments);
        } catch(Exception $e) {
            
            $exceptionData = [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ];
            
            // echo "<pre>";
            // print_r($exceptionData);
            // echo "</pre>";
            throw new Exception($e->getMessage());
        } catch(Error $e) {
            throw new Exception($e->getMessage());
        }
    }
}
