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
        Log::info('=============== mt4cf91cfs9qfhe ==============');
        $fileContents = file_get_contents($pathToLocalFile);
        // Log::info($fileContents);
        
        $result = Storage::disk('public')->put($path, $fileContents);
        
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
