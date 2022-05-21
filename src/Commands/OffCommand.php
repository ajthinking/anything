<?php

namespace Anything\Commands;

use Illuminate\Console\Command;
use Archetype\Facades\PHPFile;

class OffCommand extends Command
{
    protected $signature = 'anything:off';
    protected $description = 'Reverts anything mode by restoring bootstrap/app.php';
	public $defaultKernel = \App\Console\Kernel::class;
	public $anythingKernel = \App\AnythingConsoleKernel::class;

    public function handle()
    {
		PHPFile::load('bootstrap/app.php')
			->astQuery()
			->name()
			->where('parts', explode('\\', $this->anythingKernel))
			->replaceProperty('parts', explode('\\', $this->defaultKernel))
			->end()
			->save();
		
		$this->newLine() && $this->info('Restored bootstrap/app.php');
		
		$this->call('cache:clear');
				
		$this->info('Anything mode turned OFF 🔴');		

        return self::SUCCESS;
    }
}
