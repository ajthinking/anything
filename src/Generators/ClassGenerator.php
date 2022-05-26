<?php

namespace Anything\Generators;

use Anything\AnythingBuilder;
use Archetype\Facades\PHPFile;
use PhpParser\BuilderFactory;

class ClassGenerator
{
	protected string $name;
	protected array $staticAccess;
	protected array $stack;

	public function __construct($name, $staticAccess, $stack)
	{
		$this->name = $name;
		$this->staticAccess = $staticAccess;
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
			->insertStmts([
				...$this->staticMethods(),
				...$this->instanceMethods(),
			])
			->commit()
			->end();
	}

	protected function staticMethods()
	{
		if(!$this->staticAccess) return [];

		return [
			$this->staticMethodAst(
				$this->staticAccess[0],
				array_slice($this->staticAccess, 1)
			)
		];
	}

	protected function instanceMethods()
	{
		return collect($this->stack)
			->map(fn($call) => $this->instanceMethodAst(...$call))
			->toArray();
	}

	protected function staticMethodAst($name, $args)
	{
		return (new BuilderFactory)->method($name)
			->makeStatic()
			->makePublic()
			->addStmt(
				new \PhpParser\Node\Stmt\Return_(
					new \PhpParser\Node\Expr\New_(
						new \PhpParser\Node\Name('static')
					)
				)
			)
			->getNode();
	}

	protected function instanceMethodAst($name, $args)
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