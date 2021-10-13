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

    public function getVideoLikes($videoId)
    {
        $videoLikes = LikeVideo::where('video_id', $videoId)
                        ->where('type', true)
                        ->count();

        $data = ['likes' => $videoLikes];

        return response()->json($data);
    }

    public function getVideoDislikes($videoId)
    {
        $videoDislikes = LikeVideo::where('video_id', $videoId)
                        ->where('type', false)
                        ->count();

        $data = ['dislikes' => $videoDislikes];

        return response()->json($data);
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
