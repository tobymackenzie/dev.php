<?php
namespace TJM\Dev;

class DumpValue{
	public $content;
	public $file;
	public $name;
	public $type;
	public function __construct($arg){
		foreach($arg as $key=> $value){
			$this->$key = $value;
		}
	}
}
