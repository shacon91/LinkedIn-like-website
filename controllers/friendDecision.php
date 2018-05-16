<?php

require '../config/core.php';

$location= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../newsfeed.php";

if (isset($_POST['accept'])) {
	$initialiser_id = $_POST['from_id'];
	$reciever_id = $_POST['to_id'];
	
	$sql = "UPDATE friends SET relationship='1' WHERE initialiser_id={$initialiser_id} AND reciever_id={$reciever_id}";
		if($db->query($sql)){
			$sql="INSERT INTO notifications (from_id,to_id,type,created) 
					VALUES ({$reciever_id},{$initialiser_id},1,NOW())";
			$db->query($sql);
			$sql="DELETE FROM notifications WHERE from_id={$initialiser_id} AND to_id={$reciever_id} AND type=0";	
			$db->query($sql);
			header("Location: ".$location);

		}
}


if (isset($_POST['decline'])) {
	$initialiser_id = $_POST['from_id'];
	$reciever_id = $_POST['to_id'];
	header("Location: ../newsfeed.php");


	$sql = "DELETE FROM friends WHERE initialiser_id={$initialiser_id} AND reciever_id={$reciever_id}";
		if($db->query($sql)){
			$sql="DELETE FROM notifications WHERE (from_id={$initialiser_id} AND to_id={$reciever_id} AND type=0)";
			$db->query($sql);
			header("Location: ".$location);
		}
}


?>