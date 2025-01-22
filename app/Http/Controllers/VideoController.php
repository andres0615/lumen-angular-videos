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
use App\Services\FileService;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public $dropBoxService;
    public $fileService;
    public $thumbnailFolder;
    public $videoFolder;

    public function __construct(DropBoxService $dropBoxService, FileService $fileService)
    {
        $this->dropBoxService = $dropBoxService;
        $this->fileService = $fileService;
        $this->thumbnailFolder = '/thumbnail/';
        $this->videoFolder = '/video/';
    }

    public function all()
    {
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

            // $videoThumbnail = $this->dropBoxService->getFileLink($video->thumbnail);
            $videoThumbnail = $this->fileService->getFileLink($video->thumbnail);

            $record->thumbnail = $videoThumbnail;

            $data[] = $record;
        }

        return response()->json($data);
    }

    public function get($id)
    {
        $video = Video::where('videos.id', $id)
                    ->leftJoin('users', 'user_id', 'users.id')
                    ->select(
                        'videos.*',
                        'users.username',
                        'users.photo as user_photo'
                    )
                    ->first();

        $data = $video->toArray();

        // $videoUrl = $this->dropBoxService->getFileLink($video->video);
        $videoUrl = $this->fileService->getFileLink($video->video);

        $data['video'] = $videoUrl;

        // $userPhoto = $this->dropBoxService->getFileLink($video->user_photo);
        $userPhoto = $this->fileService->getFileLink($video->user_photo);

        $data['user_photo'] = $userPhoto;

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $video = new Video();

        $video->title = $request->title;
        $video->description = $request->description;
        $video->user_id = $request->user_id;

        if ($request->exists('video')) {
            $thumbnailStoragePath = $this->storeThumbnail($request->video->path());

            $videoName = $this->getVideoName($request->video->extension());

            $videoStoragePath = $this->videoFolder.$videoName;

            // $this->dropBoxService->uploadFile($request->video->path(), $dropBoxPath);
            $this->fileService->uploadFile($request->video->path(), $videoStoragePath);

            $video->video = $videoStoragePath;
            $video->thumbnail = $thumbnailStoragePath;
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

        $data = $video->toArray();

        // $videoUrl = $this->dropBoxService->getFileLink($video->video);
        $videoUrl = $this->fileService->getFileLink($video->video);

        $data['video'] = $videoUrl;

        return response()->json($data);
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
        $thumbnailName = $this->getThumbnailName();
        $thumbnailStoragePath = $this->thumbnailFolder . $thumbnailName;

        // some filesystem services require temporal file storage
        $thumbnailTmpPath = storage_path('app/tmp/' . $thumbnailName);

        exec('where ffmpeg',$ffmpegCommandOutput);
        $ffmpegPath = $ffmpegCommandOutput[0];

        exec('where ffprobe',$ffprobeCommandOutput);
        $ffprobePath = $ffprobeCommandOutput[0];

        // $ffmpeg = FFMpeg::create([
        //     'ffmpeg.binaries'  => exec('which ffmpeg'),
        //     'ffprobe.binaries' => exec('which ffprobe')
        // ]);

        // $ffprobe = FFProbe::create([
        //     'ffmpeg.binaries'  => exec('which ffmpeg'),
        //     'ffprobe.binaries' => exec('which ffprobe')
        // ]);

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $ffmpegPath,
            'ffprobe.binaries' => $ffprobePath
        ]);

        $ffprobe = FFProbe::create([
            'ffmpeg.binaries'  => $ffmpegPath,
            'ffprobe.binaries' => $ffprobePath
        ]);

        $videoDuration = $ffprobe
            ->format($videoPath)
            ->get('duration');

        $sec = (int)($videoDuration / 2);

        $video = $ffmpeg->open($videoPath);
        $frame = $video->frame(TimeCode::fromSeconds($sec));
        $frame->save($thumbnailTmpPath);

        // $this->dropBoxService->uploadFile($thumbnailTmpPath, $thumbnailStoragePath);
        $this->fileService->uploadFile($thumbnailTmpPath, $thumbnailStoragePath);

        return $thumbnailStoragePath;
    }

    public function getVideosByUserId($userId, $withVideoUrl = false)
    {
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
                     ->where('videos.user_id', $userId)
                     ->limit(10)
                     ->get()
                     ->shuffle();

        $data = [];

        foreach ($videos as $video) {
            $record = $video;

            if ($withVideoUrl == true) {
                // $videoUrl = $this->dropBoxService->getFileLink($video->video);
                $videoUrl = $this->fileService->getFileLink($video->video);

                $record->video = $videoUrl;
            }

            // $videoThumbnail = $this->dropBoxService->getFileLink($video->thumbnail);
            $videoThumbnail = $this->fileService->getFileLink($video->thumbnail);

            $record->thumbnail = $videoThumbnail;

            $data[] = $record;
        }

        return response()->json($data);
    }

    public function getThumbnailName()
    {
        $unique = false;

        $usedPaths = Video::select('thumbnail')
        ->get()
        ->pluck('thumbnail')
        ->toArray();

        $name = null;

        while (!$unique) {
            $name = str_random(8) . '.png';

            $path = $this->thumbnailFolder . $name;

            //Si el path no ha sido usado antes quiere decir que es unico

            $unique = !in_array($path, $usedPaths);
        }

        return $name;
    }

    public function getVideoName($ext)
    {
        $unique = false;

        $usedPaths = Video::select('video')
        ->get()
        ->pluck('video')
        ->toArray();

        $name = null;

        while (!$unique) {
            $name = str_random(8) . '.' . $ext;

            $path = $this->videoFolder . $name;

            //Si el path no ha sido usado antes quiere decir que es unico

            $unique = !in_array($path, $usedPaths);
        }

        return $name;
    }

    public function search(Request $request)
    {
        $keyword = '%' . $request->keyword . '%';

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
                     ->where('title', 'like', $keyword)
                     /*->groupBy('status')*/
                     ->limit(10)
                     ->get();

        $data = [];

        foreach ($videos as $video) {
            $record = $video;

            //$videoUrl = $this->dropBoxService->getFileLink($video->video);

            //$record['video'] = $videoUrl;

            // $videoThumbnail = $this->dropBoxService->getFileLink($video->thumbnail);
            $videoThumbnail = $this->fileService->getFileLink($video->thumbnail);

            $record->thumbnail = $videoThumbnail;

            $data[] = $record;
        }

        return response()->json($data);
    }
}
