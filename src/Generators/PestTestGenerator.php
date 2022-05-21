<?php

namespace Anything\Generators;

use Archetype\Facades\PHPFile;
use PhpParser\BuilderFactory;

class PestTestGenerator
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
		return PHPFile::make()->file($this->name);
	}
}