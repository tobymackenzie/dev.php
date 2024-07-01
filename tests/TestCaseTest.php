<?php
namespace TJM\Dev\Tests;
use Exception;
use TJM\Dev\Test\Expect;
use TJM\Dev\Test\ExpectArgs;
use TJM\Dev\Test\TestCase;
use TJM\Dev\Tests\Src\Reflect;

class TestCaseTest extends TestCase{
	public function testAssertException(){
		$this->assertException(Exception::class, function(){
			throw new Exception('fail');
		});
	}
	public function testDoReflectionMethodTest(){
		return $this->doReflectionMethodTest(Reflect::class, 'getValue', [
			'a'=> 'a++',
			'++'=> '++++',
			new Expect(
				'a',
				'a++'
			),
			new ExpectArgs(
				['a', '--'],
				'a--'
			),

		]);
	}
}
