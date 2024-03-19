<?php
namespace TJM\Dev\Tests\Src;

class Reflect{
	protected function getValue(string $in, string $append = '++'){
		return "{$in}{$append}";
	}
}
