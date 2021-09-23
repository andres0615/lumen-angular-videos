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
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class VideoController extends Controller {

    public $dropBoxService;

    public function __construct(DropBoxService $dropBoxService) {
        $this->dropBoxService = $dropBoxService;
    }

    public function all() {

        $videos = Video::all();

        return response()->json($videos);
    }

    public function get($id) {

        $video = Video::find($id);

        return response()->json($video);
    }

    public function store(Request $request) {

        $video = new Video();
        $video->title = $request->title;
        $video->description = $request->description;
        $video->thumbnail = $request->thumbnail;
        $video->user_id = $request->user_id;

        if($request->exists('video')) {

            $thumbnailPath = $this->storeThumbnail($request->video->path());

            $dropBoxPath = '/video/'.$request->video->getClientOriginalName();

            $this->dropBoxService->uploadFile($request->video->path(),$dropBoxPath);

            $video->video = $dropBoxPath;
            $video->thumbnail = $thumbnailPath;

        }

        $video->save();

        return response()->json($video);
    }

    public function update(Request $request, $id) {

        $video = Video::find($id);
        $video->title = $request->title;
        $video->description = $request->description;
        //$video->thumbnail = $request->thumbnail;
        //$video->user_id = $request->user_id;

        //if($request->exists('video')) {

            //$thumbnailPath = $this->storeThumbnail($request->video->path());

            //$dropBoxPath = '/video/'.$request->video->getClientOriginalName();

            //$this->dropBoxService->uploadFile($request->video->path(),$dropBoxPath);

            //$video->video = $dropBoxPath;
            //$video->thumbnail = $thumbnailPath;

        //}

        $video->touch();

        $video->save();

        return response()->json($video);
    }

    public function delete($id) {

        $video = Video::find($id);

        LikeVideo::where('video_id', $video->id)
        ->delete();

        $comments = Comment::where('video_id', $video->id)->get();

        foreach ($comments as $comment) {

            LikeComment::where('comment_id', $comment->id)
            ->delete();

            $comment->delete();
        }

        $video->delete();

        return;
    }

    public function storeThumbnail($videoPath) {

        $sec = 10;
        $name = 'thumbnail.png';
        $thumbnailPath = storage_path($name);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($videoPath);
        $frame = $video->frame(TimeCode::fromSeconds($sec));
        $frame->save($thumbnailPath);

        $dropBoxPath = '/thumbnail/' . $name;

        $this->dropBoxService->uploadFile($thumbnailPath,$dropBoxPath);

        return $dropBoxPath;

    }

}
