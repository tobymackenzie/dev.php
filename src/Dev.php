<?php
namespace TJM;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use TJM\Dev\DumpValue;

class Dev{
	static public $directDump;
	static protected $dumper;
	static protected $vCloner;

	static public function getDump(...$args){
		$result = '';
		//--if no args add special arg to trigger special output
		if(func_num_args() === 0){
			$args[0] = null;
		}
		foreach($args as $arg){
			//--default output
			$outputArg = $arg;
			//--special output for no arg
			if(func_num_args() === 0){
				$trace = debug_backtrace();
				$thisDir = __DIR__;
				$dirLength = strlen(__DIR__);
				while($trace){
					$traceI = array_shift($trace);
					if(substr($traceI['file'], 0, $dirLength) !== __DIR__){
						break;
					}
				}
				$outputArg = $traceI['file'] . ':' . $traceI['line'];
			//--nice output for user defined class
			}elseif(is_string($arg) && class_exists($arg)){
				$data = new \ReflectionClass($arg);
				if($data->isUserDefined()){
					$content = '';
					foreach(file($data->getFileName()) as $i=> $line){
						if($i >= $data->getStartLine() - 1){
							$content .= $line;
						}
						if($i >= $data->getEndLine() - 1){
							break;
						}
					}
					$outputArg = new DumpValue([
						'type'=> 'class',
						'name'=> $arg,
						'file'=> $data->getFileName() . ':' . static::getLineString($data->getStartLine(), $data->getEndLine()),
						'content'=> $content,
					]);
				}
			//--nice output for user defined callable
			}elseif(is_callable($arg)){
				if(is_array($arg)){
					$data = is_object($arg) ? new \ReflectionObject($arg[0]) : new \ReflectionClass($arg[0]);
					$data = $data->getMethod($arg[1]);
				}else{
					$data = new \ReflectionFunction($arg);
				}
				if(is_object($arg) || $data->isUserDefined()){
					if(is_object($arg)){
						$name = get_class($arg);
					}elseif(is_array($arg)){
						$name = (is_object($arg[0]) ? get_class($arg[0]) . '->' : $arg[0] . '::') . $arg[1] . '()';
					}else{
						$name = $arg;
					}
					$content = '';
					if($data->getFileName()){
						foreach(file($data->getFileName()) as $i=> $line){
							if($i >= $data->getStartLine() - 1){
								$content .= $line;
							}
							if($i >= $data->getEndLine() - 1){
								break;
							}
						}
					}
					$outputArg = new DumpValue([
						'type'=> 'callable',
						'name'=> $name,
						'file'=> $data->getFileName() . ':' . static::getLineString($data->getStartLine(), $data->getEndLine()),
						'content'=> $content,
					]);
				}
			}
			if(static::$directDump && function_exists('dump')){
				dump($outputArg);
			}elseif(class_exists(CliDumper::class)){
				if(empty(static::$dumper)){
					static::$dumper = PHP_SAPI ? new CliDumper() : new HtmlDumper();
				}
				if(empty(static::$vCloner)){
					static::$vCloner = new VarCloner();
				}
				$result .= static::$dumper->dump(static::$vCloner->cloneVar($outputArg), true);
			}else{
				ob_start();
				var_dump($outputArg);
				$result .= ob_get_contents();
				ob_end_clean();
			}
		}
		return $result;
	}
	static public function dump(...$args){
		if(!isset(static::$directDump)){
			static::$directDump = true;
			static::getDump(...$args);
			static::$directDump = null;
		}else{
			static::getDump(...$args);
		}
	}
	static private function getLineString($start, $end){
		if($start === $end){
			return $start;
		}else{
			return "{$start}-{$end}";
		}
	}
}
