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
use DB;

class VideoController extends Controller
{
    public $dropBoxService;

    public function __construct(DropBoxService $dropBoxService)
    {
        $this->dropBoxService = $dropBoxService;
    }

    public function all()
    {
        //$videos = Video::take(10)->get();
        $videos = Video::all()->shuffle()->take(10);

        $videos = DB::table('videos')
                    ->leftJoin('users', 'videos.user_id', '=', 'users.id')
                    ->select(
                        'videos.id',
                        'videos.title',
                        'videos.description',
                        'videos.video',
                        'videos.thumbnail',
                        'videos.user_id',
                        'videos.created_at',
                        'videos.updated_at',
                        'users.username as username'
                    )
                     /*->where('status', '<>', 1)
                     ->groupBy('status')*/
                     ->limit(10)
                     ->get()
                     ->shuffle();

        $data = [];

        foreach ($videos as $video) {
            $record = $video;

            //$videoUrl = $this->dropBoxService->getFileLink($video->video);

            //$record['video'] = $videoUrl;

            $videoThumbnail = $this->dropBoxService->getFileLink($video->thumbnail);

            $record->thumbnail = $videoThumbnail;

            $data[] = $record;
        }

        return response()->json($data);
    }

    public function get($id)
    {
        $video = Video::find($id);

        $data = $video->toArray();

        $videoUrl = $this->dropBoxService->getFileLink($video->video);

        $data['video'] = $videoUrl;

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $video = new Video();
        $video->title = $request->title;
        $video->description = $request->description;
        $video->thumbnail = $request->thumbnail;
        $video->user_id = $request->user_id;

        if ($request->exists('video')) {
            $thumbnailPath = $this->storeThumbnail($request->video->path());

            $dropBoxPath = '/video/'.$request->video->getClientOriginalName();

            $this->dropBoxService->uploadFile($request->video->path(), $dropBoxPath);

            $video->video = $dropBoxPath;
            $video->thumbnail = $thumbnailPath;
        }

        $video->save();

        return response()->json($video);
    }

    public function update(Request $request, $id)
    {
        $video = Video::find($id);
        $video->title = $request->title;
        $video->description = $request->description;

        $video->touch();

        $video->save();

        return response()->json($video);
    }

    public function delete($id)
    {
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

    public function storeThumbnail($videoPath)
    {
        $sec = 10;
        $name = 'thumbnail.png';
        $thumbnailPath = storage_path($name);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($videoPath);
        $frame = $video->frame(TimeCode::fromSeconds($sec));
        $frame->save($thumbnailPath);

        $dropBoxPath = '/thumbnail/' . $name;

        $this->dropBoxService->uploadFile($thumbnailPath, $dropBoxPath);

        return $dropBoxPath;
    }
}
