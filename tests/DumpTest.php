<?php
namespace TJM\Dev\Tests;
use TJM\Dev\Tests\A;
use TJM\Dev;
use TJM\Dev\DumpValue;
use PHPUnit\Framework\TestCase;

class DumpTest extends TestCase{
	static public function setUpBeforeClass(): void{
		require_once(__DIR__ . '/inc/A.php');
	}

	//==empty
	public function testDumpNothing(){
		$this->assertEquals('"' . __FILE__ . ":15\"\n", Dev::getDump());
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

	//==array
	public function testDumpArray(){
		$this->assertEquals(file_get_contents(__DIR__ . '/inc/array.txt'), Dev::getDump([
			'zero',
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		]));
	}
	public function testDumpMapArray(){
		$this->assertEquals(file_get_contents(__DIR__ . '/inc/mapArray.txt'), Dev::getDump([
			'a'=> 'apple',
			'b'=> 'banana',
			'c'=> 'clam',
			'd'=> 'date',
			'e'=> 'elephant',
			'f'=> 'fungi',
			'g'=> 'grape',
			'h'=> 'hippo',
			'i'=> 'iguana',
			'j'=> 'jelly fish',
			'k'=> 'koala',
			'l'=> 'lizard',
			'm'=> 'monkey',
		]));
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
