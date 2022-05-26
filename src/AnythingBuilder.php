<?php

namespace Anything;

use Anything\Generators\ClassGenerator;
use Anything\Generators\FacadeGenerator;
use Facades\Illuminate\Support\Str;

class AnythingBuilder
{
	public $staticAccess;

	public $stack = [];

	public static function __callStatic($method, $args)
	{
		$instance = new static;

		$instance->staticAccess = [$method, $args];

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
		return $this->isFacadeAccess()
			? $this->buildFacadeAndClass()
			: $this->buildClass();
	}

	protected function isFacadeAccess()
	{
		return $this->staticAccess && str_contains($this->trueName(), 'Facades\\');
	}

	protected function buildClass()
	{
		(new ClassGenerator(
			$this->trueName(),
			$this->staticAccess,
			$this->stack
		))->build();

		return 'Success!';
	}

	protected function buildFacadeAndClass()
	{
		(new FacadeGenerator($this->trueName(), $this->guessFacadeTargetName()))->build();

		(new ClassGenerator(
			$this->guessFacadeTargetName(),
			[],
			[$this->staticAccess, ...$this->stack]
		))->build();
	}

	protected function guessFacadeTargetName()
	{
		return str_replace('Facades\\', '', $this->trueName());
	}

	public function trueName()
	{
		return Str::of(get_class($this))
			->replaceFirst('Anything\AnythingBuilder_', '')
			->replace('_', '\\')
			->toString();
	}
}