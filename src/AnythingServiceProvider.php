<?php

namespace Anything;

use Anything\Commands\OffCommand;
use Anything\Commands\OnCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AnythingServiceProvider extends ServiceProvider
{
	public function register()
	{
		
	}

	public function boot()
	{
        $this->commands([
            OffCommand::class,
            OnCommand::class,
        ]);

		Artisan::call('cache:clear');
	}
}