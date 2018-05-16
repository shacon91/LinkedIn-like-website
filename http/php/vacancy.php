<?php

require "../../config/core.php";


if (isset($_POST['vac_id'])) {
$vac_id = $_POST['vac_id'];

$sql="DELETE FROM vacancy WHERE id={$vac_id}";
$db->query($sql);

$sql="DELETE FROM vac_skills WHERE vac_id={$vac_id}";
$db->query($sql);

$sql=" DELETE FROM applications WHERE vac_id={$vac_id}"; 
$db->query($sql);

$sql=" DELETE FROM notifications WHERE vac_id={$vac_id}";
$db->query($sql);

$location= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../../newsfeed.php";
header("Location: ".$location);


}

/*
friend req = 0
friend acc = 1
vacany apply = 2
*/

if (isset( $_GET['vac_info'] )) {

$vac_info = explode("z", $_GET['vac_info'] );

$vac_id = $vac_info[0];
$org_id = $vac_info[1];
$user_id = $vac_info[2];
	


$sql="SELECT id FROM applications WHERE vac_id={$vac_id} AND user_id={$user_id}";
$results=$db->query($sql);
if ($results->num_rows==0) {	
	$sql="INSERT INTO applications (vac_id,user_id,org_id,created) 
			VALUES ({$vac_id},{$user_id},{$org_id},NOW())";
	if ($results=$db->query($sql)) {	
		$sql="INSERT INTO notifications (from_id,to_id,type,vac_id,created) 
				VALUES ({$user_id},{$org_id},2,{$vac_id},NOW())";
		$db->query($sql);
		echo $vac_info[0];
	}else{
		echo 'false';
	}
}else{
	echo 'false';
}


}
?>