<?php

namespace Yueshang\Sound;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Sound::class, function(){
            return new Sound(config('services.sound.ip'),config('services.sound.port'));

        });

        $this->app->alias(Sound::class, 'sound');
    }

    public function provides()
    {
        return [Sound::class, 'sound'];
    }
}
