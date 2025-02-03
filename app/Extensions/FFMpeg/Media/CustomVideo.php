<?php

namespace App\Extensions\FFMpeg\Media;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video;
// use FFMpeg\Media\Frame;
use App\Extensions\FFMpeg\Media\CustomFrame;
use Illuminate\Support\Facades\Log;

class CustomVideo extends Video
{
    /**
     * Gets the frame at timecode.
     *
     * @param  TimeCode $at
     * @return Frame
     */
    public function frame(TimeCode $at)
    {
        Log::info(basename(__FILE__) . ':' . __LINE__);
        Log::info('usa el frame del CustomVideo');
        return new CustomFrame($this, $this->driver, $this->ffprobe, $at);
    }
}