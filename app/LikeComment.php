<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'user_id', 'comment_id', 'type', 'created_at', 'updated_at', 'pkey_likes_comments',
    ];
}
