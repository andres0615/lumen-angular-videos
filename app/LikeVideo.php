<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeVideo extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes_videos';

    protected $primaryKey='pkey_likes_videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'video_id', 'type', 'created_at', 'updated_at', 'pkey_likes_videos',
    ];
}
