<?php
namespace TJM\Dev\Test;
use ReflectionMethod;
use PHPUnit\Framework\TestCase as Base;

abstract class TestCase extends Base{
	public function doReflectionMethodTest(string $objectClass, string $method, array $expects, array $objectArgs = []){
		$object = new $objectClass(...$objectArgs);
		$rmethod = new ReflectionMethod($objectClass, $method);
		foreach($expects as $arg=> $expect){
			if($expect instanceof ExpectArgs){
				$arg = json_encode($expect->getArgs());
				$result = $rmethod->invokeArgs($object, $expect->getArgs());
				$expect = $expect->getExpect();
			}elseif($expect instanceof Expect){
				$arg = json_encode($expect->getValue());
				$result = $rmethod->invoke($object, $expect->getValue());
				$expect = $expect->getExpect();
			}else{
				$result = $rmethod->invoke($object, $arg);
			}
			$this->assertEquals($expect, $result, "{$objectClass}::{$method}({$arg}) should result in {$expect}");
		}
	}
}
