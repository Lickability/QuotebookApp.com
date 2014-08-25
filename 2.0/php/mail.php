<?php
	
	error_reporting(-1);
	
	include('wufoo.php');	
	$wufoo = new ApiExample();
	$functionName = 'submitForm';
	$wufoo->$functionName();
	
?>