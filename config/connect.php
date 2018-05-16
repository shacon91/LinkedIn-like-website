<?php
	
	$db = new mysqli('*','*','*','*');

	if ($db->connect_errno) {
		die('The website is currently expierencing difficulties try again later');
	}

?>