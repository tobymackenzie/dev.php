<?php
namespace TJM\Dev;
/*
Starts php-S dev server at $path.  Will try configured port:  If not available, will increment until open port found.
 */
class Srv{
	//==conf
	protected string $address = 'localhost';
	protected bool $interactive;
	protected string $message = "opening dev server on %s\n";
	protected string $path = '';
	protected int $port = 8080;
	//==internal
	protected $proc;
	protected array $procPipes = [];

	public function __construct($opts = []){
		if(is_array($opts)){
			foreach($opts as $key=> $val){
				$this->$key = $val;
			}
		}elseif(is_string($opts)){
			$this->path = $opts;
		}
		if(!isset($this->interactive)){
			$this->interactive = php_sapi_name() === 'cli';
		}
	}
	public function run(){
		$found = false;
		while(!$found){
			$foundCount = (int) trim(shell_exec("netstat -anp tcp | grep '\.{$this->port}\s' | wc -l"));
			if($foundCount === 1){
				++$this->port;
			}else{
				$found = true;
			}
		}
		$fullAddress = "{$this->address}:{$this->port}";
		if($this->message && $this->interactive){
			printf($this->message, $fullAddress);
		}
		$cmd = "php -S {$fullAddress}";
		if($this->path && is_dir($this->path)){
			$cmd .= " -t {$this->path}";
		}else{
			$cmd .= " {$this->path}";
		}
		if($this->interactive){
			passthru($cmd);
		}else{
			$this->proc = proc_open($cmd, [STDIN, STDOUT, STDERR], $this->procPipes);
		}
	}
	public function __invoke(){
		return $this->run();
	}
	public function end(){
		if($this->proc){
			proc_close($this->proc);
		}
	}
}
