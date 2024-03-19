<?php
namespace TJM\Dev\Tests;
use TJM\Dev\Test\Expect;
use TJM\Dev\Test\ExpectArgs;
use TJM\Dev\Test\ReflectionTestCase;
use TJM\Dev\Tests\Src\Reflect;

class ReflectionTestCaseTest extends ReflectionTestCase{
	protected array $expect = [
		'a'=> 'a++',
		'++'=> '++++',
	];
	protected string $method = 'getValue';
	protected string $objectClass = Reflect::class;
}
