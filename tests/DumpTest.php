<?php
namespace TJM\Dev\Tests;
use TJM\Dev\Tests\A;
use TJM\Dev;
use TJM\Dev\DumpValue;
use PHPUnit\Framework\TestCase;

class DumpTest extends TestCase{
	// public function setUp(){ //-! uncomment for PHP < 7.0
	public function setUp(): void{
		require_once(__DIR__ . '/inc/A.php');
	}

	//==class
	public function testDumpBuiltInClass(){
		$this->assertEquals('"ReflectionClass"' . PHP_EOL, Dev::getDump(\ReflectionClass::class));
	}
	public function testDumpClass(){
		$this->runDumpValueTest(A::class, __DIR__ . '/inc/a.txt');
	}
	public function testDumpStaticMethod(){
		$this->runDumpValueTest([A::class, 'stat'], __DIR__ . '/inc/Astat.txt');
	}
	public function testDumpNonExistantStaticMethod(){
		$this->assertEquals(file_get_contents(__DIR__ . '/inc/AgetA.txt'), Dev::getDump([A::class, 'getA']));
	}

	//==function
	public function testBuiltInFunction(){
		$this->assertEquals('"var_dump"' . PHP_EOL, Dev::getDump('var_dump'));
	}
	public function testAnonymousFunction(){
		$this->runDumpValueTest(require(__DIR__ . '/inc/anonFn.php'), __DIR__ . '/inc/anonFn.txt');
	}

	//==object
	public function testDumpObjectMethod(){
		$this->runDumpValueTest([new A(), 'getA'], __DIR__ . '/inc/objGetA.txt');
	}

	//==scalar
	public function testDumpBool(){
		$this->assertEquals("true\n", Dev::getDump(true));
		$this->assertEquals("false\n", Dev::getDump(false));
	}
	public function testDumpInt(){
		$this->assertEquals("-123\n", Dev::getDump(-123));
		$this->assertEquals("0\n", Dev::getDump(0));
		$this->assertEquals("123\n", Dev::getDump(123));
	}
	public function testDumpString(){
		$this->assertEquals('"foo"' . PHP_EOL, Dev::getDump('foo'));
		$this->assertEquals('"return"' . PHP_EOL, Dev::getDump('return'));
	}

	/*=====
	==helpers
	=====*/
	protected function runDumpValueTest($input, $expectFile){
		$dump = Dev::getDump($input);
		preg_match('/^TJM\\\Dev\\\DumpValue \{#([\d]+)/', $dump, $matches);
		$expect = file_get_contents($expectFile);
		foreach([
			'{{id}}'=> $matches[1],
			'{{testsPath}}'=> __DIR__,
		] as $from=> $to){
			$expect = str_replace($from, $to, $expect);
		}
		$this->assertEquals($expect, $dump);
	}
}
