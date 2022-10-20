<?php
use TJM\Dev;

if(!function_exists('d')){
	function d(...$args){
		Dev::dump(...$args);
	}
}else{
	trigger_error("d() function is already defined, and will not be an alias for TJM\Dev::dump().  This might affect expected behavior.");
}
