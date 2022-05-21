<?php

namespace Anything\Generators;

use Archetype\Facades\PHPFile;
use PhpParser\BuilderFactory;

class ClassGenerator
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
		return PHPFile::make()->class($this->name)
			->astQuery()
			->class()
			->insertStmts(
				collect($this->stack)
					->map(fn($call) => $this->methodAst(...$call))
					->toArray()
			)
			->commit()
			->end();
	}

	protected function methodAst($name, $args)
	{
		return (new BuilderFactory)->method($name)
			->makePublic()
			->addStmt(
				new \PhpParser\Node\Stmt\Return_(
					new \PhpParser\Node\Expr\Variable('this')
				)				
			)
			->getNode();
	}
}