<?php

use Anything\AnythingBuilder;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

it('will instanciate using another name', function () {
	$template = AnythingBuilder::class;
	$alias = $template . '_App_Support_Git';

    class_alias($template, $alias);
	$instance = $alias::someMethod();

	assertInstanceOf($alias, $instance);
});

it('can get true name', function () {
	// Mock incoming class name
	\Facades\Illuminate\Support\Str::shouldReceive('of')->once()->andReturn(
		\Illuminate\Support\Str::of(AnythingBuilder::class . '_App_Support_Git')
	);

	assertEquals(
		AnythingBuilder::anything()->trueName(),
		'App\Support\Git'
	);
});

it('accumulates method calls and property access', function () {
	$instance = AnythingBuilder::a()->b(123)->c;

	assertEquals(
		$instance->stack,
		[
			['a',[]],
			['b',[123]],
			'c',
		]
	);
});
