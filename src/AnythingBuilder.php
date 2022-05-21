<?php

namespace App;

use Archetype\Facades\PHPFile;
use Illuminate\Support\Str;
use PhpParser\BuilderFactory;

class AnythingBuilder
{
	protected $stack = [];

	public static function __callStatic($method, $args)
	{
		$instance = new static;
		array_push($instance->stack, [$method, $args]);

		return $instance;
	}	

	public function __get($key)
	{
		array_push($this->stack, $key);
		return $this;
	}

	public function __call($method, $args)
	{
		array_push($this->stack, [$method, $args]);
		return $this;
	}

	public function build()
	{
		$file = PHPFile::make()->class($this->trueName())
			->astQuery()
			->class();
		
		foreach($this->stack as $call) {
			$file->insertStmt(
				(new BuilderFactory)->method($call[0])
					->makePublic()
					->setReturnType('self')
					->getNode()
			);
		}

		$file->commit()->end()->render();
	}

	public function trueName()
	{
		return Str::of(get_class($this))
			->replaceFirst('App\AnythingBuilder_', '')
			->replace('_', '\\')
			->toString();
	}

	public function __toString()
	{
		$file = PHPFile::make()->class($this->trueName())
			->astQuery()
			->class();
		
		foreach($this->stack as $call) {
			$file->insertStmt(
				(new BuilderFactory)->method($call[0])
					->makePublic()
					->setReturnType('self')
					->getNode()
			);
		}

		return $file->commit()->end()->render();
	}
}