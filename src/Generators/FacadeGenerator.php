<?php

namespace Anything\Generators;

use Archetype\Facades\PHPFile;
use PhpParser\BuilderFactory;

class FacadeGenerator
{
	protected $stack = [];

	public function __construct($name, $stack)
	{
		$this->name = $name;
		$this->stack = $stack;
	}

	public function build()
	{
		$this->toFile()->save();
	}

	public function toFile()
	{
		return PHPFile::make()->class($this->name);
	}
}

// namespace App\Repositories;

// use Illuminate\Support\Facades\Facade;

// class TestFacades extends Facade
// {
//     protected static function getFacadeAccessor()
//     {
//         return 'check';
//     }
// }