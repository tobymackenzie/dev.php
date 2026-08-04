<?php
$path = $_SERVER['REQUEST_URI'];
if(substr($path, 0, 1) === '/'){
	$path = substr($path, 1);
}
if(empty($path)){
	$path = 'world';
}
$message = 'Hello ' . htmlspecialchars($path);
?>
<title><?=$message?></title>
<h1><?=$message?></h1>
<p>This is a page served by a TJM\Dev dev server using a PHP router file.</p>
