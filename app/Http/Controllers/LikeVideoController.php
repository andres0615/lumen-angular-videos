<?php

namespace App\Http\Controllers;

use App\LikeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeVideoController extends Controller
{
    public function get($userId, $videoId)
    {
        $likeVideo = LikeVideo::where('user_id', $userId)
                        ->where('video_id', $videoId)
                        ->first();

        return response()->json($likeVideo);
    }

    public function store(Request $request)
    {
        $likeVideo = new LikeVideo();
        $likeVideo->type = $request->type;
        $likeVideo->user_id = $request->user_id;
        $likeVideo->video_id = $request->video_id;

        $likeVideo->save();

        return response()->json($likeVideo);
    }

    public function delete($userId, $videoId)
    {
        LikeVideo::where('user_id', $userId)
                        ->where('video_id', $videoId)
                        ->delete();

        return;
    }
}
