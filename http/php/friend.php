<?php

require "../../config/core.php";

//0=requested
//1=friends




if (isset($_POST['data']) && !empty($_POST['data'])) {
	$data = explode(',', $_POST['data']);
	//settype($data[2], "integer");

	if($data[2]==='false' && $data[3]==='false' && $data[4]==='false'){//0 add
		$sql = "INSERT INTO friends (initialiser_id,reciever_id,relationship,created) VALUES ({$data[0]},{$data[1]},0,NOW()) ";
		if($db->query($sql)){
			$sql="INSERT INTO notifications (from_id,to_id,type,created) VALUES ({$data[0]},{$data[1]},0,NOW())";
			$db->query($sql);
			echo 0; //cancel or respond to request
		}
	}else if($data[2]==='true' || $data[4]==='true'){//3 remove
		//NEED 2 CHECKS HERE
		$sql = "DELETE FROM friends WHERE ( initialiser_id={$data[0]} AND reciever_id={$data[1]} ) OR ( initialiser_id={$data[1]} AND reciever_id={$data[0]} )";
		if($db->query($sql)){
			$sql="DELETE FROM notifications WHERE (from_id={$data[1]} AND to_id={$data[0]} AND type=0) OR (from_id={$data[0]} AND to_id={$data[1]} AND type=0)";
			$db->query($sql);
			echo 1;//deleted
		}
	}else if($data[3]==='true'){//2 repond
		$sql = "UPDATE friends SET relationship='1' WHERE initialiser_id={$data[1]} AND reciever_id={$data[0]}";
		if($db->query($sql)){
			$sql="INSERT INTO notifications (from_id,to_id,type,created) 
					VALUES ({$data[0]},{$data[1]},1,NOW())";
			$db->query($sql);
			$sql="DELETE FROM notifications WHERE from_id={$data[1]} AND to_id={$data[0]} AND type=0";	
			$db->query($sql);
			echo 2;//accepted
		}
	}else{
		echo"fail";
	}
}
?>