<?php

namespace Anything\Generators;

use Archetype\Facades\PHPFile;
use PhpParser\BuilderFactory;

class FacadeGenerator
{
	protected $stack = [];

	public function __construct(string $name, string $target)
	{
		$this->name = $name;
		$this->target = $target;
	}

	public function build()
	{
		$this->toFile()->save();
	}

	public function toFile()
	{
		return PHPFile::make()->class($this->name)
			->use(\Illuminate\Support\Facades\Facade::class)
			->extends('Facade')
			->astQuery()
			->class()
			->insertStmt(
				(new BuilderFactory)->method('getFacadeAccessor')
					->makeStatic()
					->makeProtected()
					->addStmt(
						new \PhpParser\Node\Stmt\Return_(
							new \PhpParser\Node\Scalar\String_($this->target)
						)
					)
					->getNode()				
			)
			->commit()
			->end();		
	}
}