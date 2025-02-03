<?php

namespace App\Extensions\FFMpeg\Media;

use FFMpeg\Media\Frame;
use FFMpeg\Media\Video;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Log;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Exception\RuntimeException;

class CustomFrame extends Frame
{

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $timecode)
    {
        Log::info('se usa el CustomFrame');
        parent::__construct($video, $driver, $ffprobe, $timecode);
    }

    /**
     * Saves the frame in the given filename.
     *
     * Uses the `unaccurate method by default.`
     *
     * @param string $pathfile
     * @param bool $accurate
     * @param bool $returnBase64
     *
     * @return Frame
     *
     * @throws RuntimeException
     */
    public function save($pathfile, $accurate = false, $returnBase64 = false)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $outputFormat = $returnBase64 ? "image2pipe" : "image2";
        if (!$accurate) {
            $commands = array(
                '-y', '-ss', (string) $this->getTimeCode(),
                '-i', $this->pathfile,
                '-vframes', '1',
                '-threads', '1',
                '-f', $outputFormat
            );
        } else {
            $commands = array(
                '-y', '-i', $this->pathfile,
                '-vframes', '1', '-ss', (string) $this->getTimeCode(),
                '-threads', '1',
                '-f', $outputFormat
            );
        }
        
        if($returnBase64) {
            array_push($commands, "-");
        }

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        if (!$returnBase64) {
            $commands = array_merge($commands, array($pathfile));
        }

        try {
            if(!$returnBase64) {
                $this->driver->command($commands);
                return $this;
            }
            else {
                return $this->driver->command($commands);
            }
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save frame', $e->getCode(), $e);
        }
    }
}