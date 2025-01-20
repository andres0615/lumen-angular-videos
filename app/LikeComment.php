<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

class LikeComment extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes_comments';

    protected $primaryKey='pkey_likes_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'comment_id', 'type', 'created_at', 'updated_at',
    ];

    public function getCommentLikes($commentId)
    {

        $commentLikes = 0;
        $maxAttempts = 2;
        $intervalSeconds = 1;

        for ($i=0; $i < $maxAttempts; $i++) {
            $result = $this->countLikes($commentId);

            $resultType = $result['type'];
            $resultData = $result['data'];

            if ($resultType === 'success') {
                $commentLikes = $resultData['commentLikes'];
                break;
            } else {
                sleep($intervalSeconds);
            }
        }

        return $commentLikes;
    }

    public function countLikes($commentId)
    {
        $result = [];
        $resultData = [];

        // Log::info('=============== wzzsi22icidgdgr ==============');

        try {
            $commentLikes = LikeComment::where('comment_id', $commentId)
                        ->where('type', true)
                        ->count();

            $resultData['commentLikes'] = $commentLikes;
            $resultType = 'success';

        } catch (Throwable $th) {
            //throw $th;
            $resultType = 'error';
        }

        $result['type'] = $resultType;
        $result['data'] = $resultData;

        return $result;
    }

    public function getCommentDislikes($commentId)
    {

        $commentDislikes = 0;
        $maxAttempts = 2;
        $intervalSeconds = 1;

        for ($i=0; $i < $maxAttempts; $i++) {
            $result = $this->countDislikes($commentId);

            $resultType = $result['type'];
            $resultData = $result['data'];

            if ($resultType === 'success') {
                $commentDislikes = $resultData['commentDislikes'];
                break;
            } else {
                sleep($intervalSeconds);
            }
        }

        return $commentDislikes;
    }

    public function countDislikes($commentId)
    {
        $result = [];
        $resultData = [];

        // Log::info('=============== j486ptbz3q9k8gr ==============');

        try {
            $commentDislikes = LikeComment::where('comment_id', $commentId)
                        ->where('type', false)
                        ->count();

            $resultData['commentDislikes'] = $commentDislikes;
            $resultType = 'success';

        } catch (Throwable $th) {
            //throw $th;
            $resultType = 'error';
        }

        $result['type'] = $resultType;
        $result['data'] = $resultData;

        return $result;
    }

}
