<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use FFMpeg\FFMpeg;
// use FFMpeg\Media\Video;
// use FFMpeg\Media\Frame;
// use App\Extensions\FFMpeg\CustomFFMpeg;
// use App\Extensions\FFMpeg\Media\CustomVideo;
// use App\Extensions\FFMpeg\Media\CustomFrame;
// use Alchemy\BinaryDriver\ProcessBuilderFactoryInterface;
// use Alchemy\BinaryDriver\ProcessBuilderFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
            $this->app->register('STS\Fixer\FixerServiceProvider');
            $this->app->register('Thedevsaddam\LumenRouteList\LumenRouteListServiceProvider');
        }

        $this->app->register('\Tymon\JWTAuth\Providers\LumenServiceProvider');
        
        $this->app->register('App\Services\DropBoxService');

        // $this->app->bind(ProcessBuilderFactoryInterface::class, ProcessBuilderFactory::class);
        // $this->app->bind(FFMpeg::class, CustomFFMpeg::class);
        // $this->app->bind(Video::class, CustomVideo::class);
        // $this->app->bind(Frame::class, CustomFrame::class);
    }
}
