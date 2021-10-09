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

class UserController extends Controller
{
    public function __construct(DropBoxService $dropBoxService)
    {
        $this->dropBoxService = $dropBoxService;
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

        $photoUrl = $this->dropBoxService->getFileLink($user->photo);

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

    public function update(Request $request, DropBoxService $dropBoxService, $id)
    {
        $user = User::find($id);
        $user->username = $request->username;
        $user->password = $request->password;

        if ($request->exists('photo')) {
            $dropBoxPath = '/'.$request->photo->getClientOriginalName();

            $dropBoxService->uploadFile($request->photo->path(), $dropBoxPath);

            $user->photo = $dropBoxPath;
        }

        $user->touch();

        $user->save();

        return response()->json($user);
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

    //public function getObjectContent($object) {
        //ob_start();
        //var_dump($object);
        //$contents = ob_get_contents();
        //ob_end_clean();
        //return $contents;
    //}
}
