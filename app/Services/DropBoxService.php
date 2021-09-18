<?php

namespace App\Services;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
  
class DropBoxService
{

	private $app;

    private $dropbox;

    public function __construct(){

    	$this->app = new DropboxApp(env('DROPBOX_KEY'), env('DROPBOX_SECRET'), env('DROPBOX_TOKEN'));

        $this->dropbox = new Dropbox($this->app);

    }

    public function getFileLink($path){

    	return $this->dropbox->getTemporaryLink($path)->getLink();

    }

    public function uploadFile($pathToLocalFile,$path){

        $dropboxFile = new DropboxFile($pathToLocalFile);

        $file = $this->dropbox->upload($dropboxFile, $path, ['autorename' => false]);

        return true;
    }

    public function uploadFileByUrl($url,$path){

        $file = $this->dropbox->saveUrl($path, $url);

        return true;

    }

    public function deleteFile($path){

        $file = $this->dropbox->delete($path);

        return true;

    }

    public function moveFile($pathToLocalFile,$path){

        $file = $this->dropbox->move($pathToLocalFile, $path);

        return true;
    }

    public function downloadFile($path,$pathToLocalFile){

        $file = $this->dropbox->download($path,$pathToLocalFile);

        return $file;
    }

}