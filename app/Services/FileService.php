<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileService
{
    public function getFileLink($path)
    {
        $path = ltrim($path,"/");
        $path = Storage::url($path);

        $url = url($path);

        return $url;
    }

    public function uploadFile($pathToLocalFile, $path)
    {
        Storage::disk('public')->put($path, $pathToLocalFile);
        
        return true;
    }

    public function deleteFile($path)
    {
        return Storage::delete($path);
    }

    public function updateFile($file, $path)
    {
        $this->deleteFile($path);
        return $this->uploadFile($file, $path);
    }

    public function getFile($path)
    {
        return Storage::get($path);
    }
}
