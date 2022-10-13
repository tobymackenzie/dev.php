<?php
namespace TJM\Dev\Tests;

class A{
	protected $a = 'aye';
	public function getA(){
		return $this->a;
	}
	static public function stat($val){
		echo 'stat: ' . $val;
	}
}
