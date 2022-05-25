<?php

namespace Anything;

use Anything\Generators\ClassGenerator;
use Anything\Generators\FacadeGenerator;
use Facades\Illuminate\Support\Str;

class AnythingBuilder
{
	public $stack = [];

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
		(new ClassGenerator($this->trueName(), $this->stack))->build();
		// (new FacadeGenerator($this->trueName(), $this->stack))->build();

		return 'Success!';
	}

	public function trueName()
	{
		return Str::of(get_class($this))
			->replaceFirst('Anything\AnythingBuilder_', '')
			->replace('_', '\\')
			->toString();
	}
}