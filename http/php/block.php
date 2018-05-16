<?php

require "../../config/core.php";




if (isset($_POST['data']) && !empty($_POST['data'])) {
	$data = explode(',', $_POST['data']);
	//settype($data[2], "integer");

	if($data[2]==='true'){//0 block
		$sql = "INSERT INTO blocked (user_id,blocked_id,created) VALUES ({$data[0]},{$data[1]},NOW()) ";
		if($db->query($sql)){
			echo 0; //cancel or respond to request
		}
	}else if($data[2]==='false'){//1 unblock
		$sql = "DELETE FROM blocked WHERE user_id={$data[0]} AND blocked_id={$data[1]}";
		if($db->query($sql)){
			echo 1;// canceled or deleted
		}
	}
}
?>