<?php
namespace TJM;
use TJM\Dev\DumpValue;

class Dev{
	static public function getDump(){
		$result = '';
		foreach(func_get_args() as $arg){
			$outputArg = $arg;
			if(is_string($arg) && class_exists($arg)){
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
			ob_start();
			if(function_exists('dump')){
				dump($outputArg);
			}else{
				var_dump($outputArg);
			}
			$result .= ob_get_contents();
			ob_end_clean();
		}
		return $result;
	}
	static public function dump(){
		echo call_user_func_array(Dev::class . '::getDump', func_get_args());
	}
	static private function getLineString($start, $end){
		if($start === $end){
			return $start;
		}else{
			return "{$start}-{$end}";
		}
	}
}
