<?php

namespace App\Http\Controllers;

use App\User;
use App\Video;
use App\Comment;
use App\LikeComment;
use App\LikeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\DropBoxService;
use Illuminate\Support\Facades\Hash;
use App\Services\FileService;

class UserController extends Controller
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
        $users = User::all();

        return response()->json($users);
    }

    public function get($id)
    {
        $user = User::find($id);

        $data = $user->toArray();

        // $photoUrl = $this->dropBoxService->getFileLink($user->photo);
        $photoUrl = $this->fileService->getFileLink($user->photo);

        $data['photo'] = $photoUrl;

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        if ($request->exists('photo')) {
            $dropBoxPath = '/'.$request->photo->getClientOriginalName();

            $this->dropBoxService->uploadFile($request->photo->path(), $dropBoxPath);

            $user->photo = $dropBoxPath;
        }

        $user->save();

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $this->logObject($request->all(), '------aqui------');

        if ($request->exists('username')) {
            $user->username = $request->username;
        }

        if ($request->exists('password')) {
            $user->password = $request->password;
        }

        if ($request->exists('photo')) {
            $dropBoxPath = '/'.$request->photo->getClientOriginalName();

            $this->dropBoxService->uploadFile($request->photo->path(), $dropBoxPath);

            $user->photo = $dropBoxPath;
        }

        $user->touch();

        $user->save();

        $data = $user->toArray();

        // $photoUrl = $this->dropBoxService->getFileLink($user->photo);
        $photoUrl = $this->fileService->getFileLink($user->photo);

        $data['photo'] = $photoUrl;

        $this->logObject($data, '------- update user --------');

        return response()->json($data);
    }

    public function delete($id)
    {
        $user = User::find($id);

        $videos = Video::where('user_id', $user->id)->get();

        foreach ($videos as $video) {
            LikeVideo::where('video_id', $video->id)
        ->delete();

            $video->delete();
        }

        $comments = Comment::where('user_id', $user->id)->get();

        foreach ($comments as $comment) {
            LikeComment::where('comment_id', $comment->id)
            ->delete();

            $comment->delete();
        }

        LikeVideo::where('user_id', $user->id)
        ->delete();

        LikeComment::where('user_id', $user->id)
            ->delete();

        $user->delete();

        return;
    }

    public function logObject($object, $msg = null)
    {
        Log::info($msg);

        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        Log::info($contents);
        return;
    }
}
