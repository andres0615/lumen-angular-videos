<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileService
{
    public function getFileLink($path)
    {
        // Log::info(basename(__FILE__) . ':' . __LINE__);
        // $temporaryLink = $this->dropbox->getTemporaryLink($path);
        // $link = $temporaryLink->getLink();

        Log::info(basename(__FILE__) . ':' . __LINE__);
        Log::info($path);

        // $link = storage_path($path);

        $path = ltrim($path,"/");
        $path = Storage::url($path);

        $url = url($path);
        
        Log::info(basename(__FILE__) . ':' . __LINE__);
        Log::info($url);

        return $url;
    }

    public function saveFile($file, $path)
    {
        return Storage::put($path, $file);
    }

    public function deleteFile($path)
    {
        return Storage::delete($path);
    }

    public function updateFile($file, $path)
    {
        $this->deleteFile($path);
        return $this->saveFile($file, $path);
    }

    public function getFile($path)
    {
        return Storage::get($path);
    }
}
