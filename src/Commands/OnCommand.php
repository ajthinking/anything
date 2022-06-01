<?php

namespace Anything\Commands;

use Illuminate\Console\Command;
use Archetype\Facades\PHPFile;

class OnCommand extends Command
{
    protected $signature = 'anything:on';
    protected $description = 'Allow anything mode by modifying bootstrap/app.php';
	protected $alreadyInAnythingMode = 'Could not find the defult console kernel in bootstrap/app.php. Already in Anything mode?';
	public $defaultKernel = \App\Console\Kernel::class;
	public $anythingKernel = \Anything\ConsoleKernel::class;

    public function handle()
    {
		PHPFile::load('bootstrap/app.php')
			->astQuery()
			->name()
			->where('parts', explode('\\', $this->defaultKernel))
			->replaceProperty('parts', explode('\\', $this->anythingKernel))			
			->end()
			->save();

		$this->newLine() && $this->info('Using AnythingKernel in bootstrap/app.php');

		$this->call('cache:clear');

		$this->info('Anything mode turned ON 🟢');

        return self::SUCCESS;
    }
}
