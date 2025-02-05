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
        $commentLikes = LikeComment::where('comment_id', $commentId)
                        ->where('type', true)
                        ->count();

        return $commentLikes;
    }

    // public function countLikes($commentId)
    // {
    // }

    public function getCommentDislikes($commentId)
    {
        $commentDislikes = LikeComment::where('comment_id', $commentId)
                        ->where('type', false)
                        ->count();

        return $commentDislikes;
    }

    // public function countDislikes($commentId)
    // {
    // }

}
