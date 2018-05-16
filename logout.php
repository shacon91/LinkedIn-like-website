<?php
require "config/core.php";

$id = $_SESSION['id'];
$sql="UPDATE user_details SET online=0 WHERE id={$id}";

if ($db->query($sql)) {

	if ($db->affected_rows===0) {
		header('Location: newsfeed.php');
	}else{
		session_destroy();
		header('Location: index.php');
	}
}else{
	session_destroy();
	header('Location: index.php');
}
?>