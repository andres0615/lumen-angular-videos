<?php

namespace App\Extensions\FFMpeg;

use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;
use FFMpeg\FFMpeg;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Media\Audio;
use App\Extensions\FFMpeg\Media\CustomVideo;

class CustomFFMpeg extends FFMpeg
{
    public function __construct(FFMpegDriver $ffmpeg, FFProbe $ffprobe)
    {
        Log::info('se usa el custom ffmpeg');
        parent::__construct($ffmpeg, $ffprobe);
    }

    /**
     * Opens a file in order to be processed.
     *
     * @param string $pathfile A pathfile
     *
     * @return Audio|Video
     *
     * @throws InvalidArgumentException
     */
    public function open($pathfile)
    {
        Log::info(basename(__FILE__) . ':' . __LINE__);
        Log::info('se usa el open de CustomFFMpeg');
        if (null === $streams = $this->getFFProbe()->streams($pathfile)) {
            throw new RuntimeException(sprintf('Unable to probe "%s".', $pathfile));
        }

        if (0 < count($streams->videos())) {
            return new CustomVideo($pathfile, $this->getFFMpegDriver(), $this->getFFProbe());
        } elseif (0 < count($streams->audios())) {
            return new Audio($pathfile, $this->getFFMpegDriver(), $this->getFFProbe());
        }

        throw new InvalidArgumentException('Unable to detect file format, only audio and video supported');
    }

}