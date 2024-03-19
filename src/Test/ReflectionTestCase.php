<?php
namespace TJM\Dev\Test;

abstract class ReflectionTestCase extends TestCase{
	protected array $expect;
	protected string $method;
	protected string $objectClass;
	public function testReflection(){
		return $this->doReflectionMethodTest($this->objectClass, $this->method, $this->expect);
	}
}
