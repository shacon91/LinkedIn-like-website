<?php

require "../../config/core.php";



$search = $_GET['search'];
/*
$sql = "SELECT id,first_name,last_name,url FROM user_details WHERE first_name LIKE '{$search}%' OR last_name LIKE '{$search}%' LIMIT 10";

if ($results=$db->query($sql)) {
	//$data=$results->fetch_all();
	while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$data[] = $row;
			}
			
	
	$newData=array();

	foreach ($data as $value) {
		if (blocked($value[0])==false) {
			$newData[]= $value;
		}
	}

		echo  json_encode(
					array(
						'data' => $newData
						)
				);
		
	
}*/

$sql = "SELECT id,first_name,last_name,url FROM user_details WHERE first_name LIKE '{$search}%' OR last_name LIKE '{$search}%' LIMIT 10";

if ($results=$db->query($sql)) {
	$newData=array();
	while ($row = $results->fetch_array(MYSQLI_NUM)) {
		if (blocked($row[0])==false && banned($row[0])==false) {
			$newData[]= $row;	
		}
	}

	echo  json_encode(
					array(
						'data' => $newData
						)
				);
		
	
}

?>