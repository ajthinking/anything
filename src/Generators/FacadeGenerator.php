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
		$this->saveFacade();
		$this->registerFacade();
	}

	protected function saveFacade()
	{
		$this->toFile()->save();
	}

	protected function registerFacade()
	{
		PHPFile::load('app/Providers/AppServiceProvider.php')->astQuery()
			->classMethod()
			->where('name->name->name', 'register')
			->insertStmt($this->appBindingAST())
			->commit()
			->end()
			->save();
	}

	protected function appBindingAST()
	{
		return new \PhpParser\Node\Stmt\Return_(
			new \PhpParser\Node\Expr\Variable('jerryBoi')
		);
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
							new \PhpParser\Node\Scalar\String_($this->name)
						)
					)
					->getNode()				
			)
			->commit()
			->end();		
	}
}