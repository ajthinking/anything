<?php

namespace Anything;

use Illuminate\Foundation\Console\Kernel;

class ConsoleKernel extends Kernel
{
    protected function commands()
    {
        $this->load(app_path('Console/Commands'));
        require base_path('routes/console.php');
    }

    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Anything\AnythingRegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\SetRequestForConsole::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];
}
