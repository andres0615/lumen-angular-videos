<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Services\FileService;

class Comment extends Model
{
    public $fileService;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    protected $primaryKey='id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'comment', 'user_id', 'video_id', 'created_at', 'updated_at',
    ];

    public function __construct()
    {
        $this->fileService = new FileService();
    }

    public function getAll()
    {
        $comments = self::all();

        $comments = $comments->map(function ($comment) {
            $commentAgo = $this->getDateAgo($comment->created_at);
            // $commentAgo = $comment->created_at->diff();
            $comment->comment_ago = $commentAgo . ' minutes ago';
            return $comment;
        });


        return $comments;
    }

    public function getDateAgo($commentDate)
    {
        $now = new \DateTime();
        $commentDate = new \DateTime($commentDate);
        $commentAgo = $now->diff($commentDate);
        Log::info(json_encode($commentAgo));

        if ($commentAgo->y > 0) {
            $unit = ($commentAgo->y > 1) ? 'años' : 'año';
            $commentAgo = 'hace ' . $commentAgo->y . ' ' . $unit;
        } elseif ($commentAgo->m > 0) {
            $unit = ($commentAgo->m > 1) ? 'meses' : 'mes';
            $commentAgo = 'hace ' . $commentAgo->m . ' ' . $unit;
        } elseif ($commentAgo->d > 0) {
            $unit = ($commentAgo->d > 1) ? 'días' : 'día';
            $commentAgo = 'hace ' . $commentAgo->d . ' ' . $unit;
        } elseif ($commentAgo->h > 0) {
            $unit = ($commentAgo->h > 1) ? 'horas' : 'hora';
            $commentAgo = 'hace ' . $commentAgo->h . ' ' . $unit;
        } elseif ($commentAgo->i > 0) {
            $unit = ($commentAgo->i > 1) ? 'minutos' : 'minuto';
            $commentAgo = 'hace ' . $commentAgo->i . ' ' . $unit;
        } else {
            $commentAgo = "justo ahora";
        }

        return $commentAgo;
    }

    public function getCommentsByVideoId($videoId)
    {
        $comments = self::query()
                ->leftJoin('users', 'comments.user_id', 'users.id')
                ->select('comments.*', 'users.username', 'users.photo as user_photo')
                ->where('video_id', $videoId)
                ->orderBy('updated_at', 'DESC')
                ->get();

        $comments = $comments->map(function ($comment) {
            $comment->user_photo = $this->fileService->getFileLink($comment->user_photo);

            $commentAgo = $this->getDateAgo($comment->created_at);
            // $commentAgo = $comment->created_at->diff();
            $comment->comment_ago = $commentAgo;
            return $comment;
        });

        return $comments;
    }
}
