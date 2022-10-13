<?php
namespace TJM\Dev\Tests;
use TJM\Dev\Tests\A;
use TJM\Dev;
use TJM\Dev\DumpValue;
use PHPUnit\Framework\TestCase;

class Test extends TestCase{
	// public function setUp(){ //-! uncomment for PHP < 7.0
	public function setUp(): void{
		require_once(__DIR__ . '/inc/A.php');
	}

	//==class
	public function testDumpBuiltInClass(){
		$this->assertEquals('string(15) "ReflectionClass"' . PHP_EOL, Dev::getDump(\ReflectionClass::class));
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
		$this->assertEquals('string(8) "var_dump"' . PHP_EOL, Dev::getDump('var_dump'));
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
		$this->assertEquals("bool(true)\n", Dev::getDump(true));
		$this->assertEquals("bool(false)\n", Dev::getDump(false));
	}
	public function testDumpInt(){
		$this->assertEquals("int(-123)\n", Dev::getDump(-123));
		$this->assertEquals("int(0)\n", Dev::getDump(0));
		$this->assertEquals("int(123)\n", Dev::getDump(123));
	}
	public function testDumpString(){
		$this->assertEquals("string(3) \"foo\"\n", Dev::getDump('foo'));
		$this->assertEquals("string(6) \"return\"\n", Dev::getDump('return'));
	}

	/*=====
	==helpers
	=====*/
	protected function runDumpValueTest($input, $expectFile){
		$dump = Dev::getDump($input);
		preg_match('/^object\(TJM\\\Dev\\\DumpValue\)#([\d]+)/', $dump, $matches);
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
