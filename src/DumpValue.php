<?php
namespace TJM\Dev;

class DumpValue{
	public function __construct($arg){
		foreach($arg as $key=> $value){
			$this->$key = $value;
		}
	}
}
