<?php

namespace Anything;

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
		$this->toPHPFile()->save();
	}

	public function trueName()
	{
		return Str::of(get_class($this))
			->replaceFirst('Anything\AnythingBuilder_', '')
			->replace('_', '\\')
			->toString();
	}

	public function toPHPFile()
	{
		return PHPFile::make()->class($this->trueName())
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

	public function __toString()
	{
		return $this->toPHPFile()->render();
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