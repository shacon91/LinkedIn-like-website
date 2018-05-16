<?php
session_start();
require "connect.php";
ob_start();

function secure($value){
	global $db;
	$trim=trim($value);
	$html=htmlentities($trim);
	$secured=mysqli_real_escape_string($db,$html);

	return $secured;
};

function loggedIn(){
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
		return true;
	}else{
		return false;
	}
};

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function compBan(){
	global $db;
	$comp_id = get_client_ip();
	$sql = "SELECT * FROM banned WHERE comp_id='{$comp_id}' ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function userBan(){
	global $db;
	$sql = "SELECT * FROM banned WHERE user_id={$_SESSION['id']} ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function blocked($user_id){
	global $db;
	$sql = "SELECT * FROM blocked WHERE user_id={$user_id} AND blocked_id={$_SESSION['id']}";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function banned($user_id){
	global $db;
	$sql = "SELECT * FROM banned WHERE user_id={$user_id}";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function admin(){
	global $db;
	$sql = "SELECT id FROM user_details WHERE id={$_SESSION['id']} AND admin=1 ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function organisation(){
	global $db;
	$sql = "SELECT id FROM user_details WHERE id={$_SESSION['id']} AND accType=1 ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return false;
			}else{
				return true;
			}
	}else{
		return false;
	}
};

function emailCheck($email){
	global $db;
	$sql = "SELECT id FROM user_details WHERE email='{$email}' ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return true;
			}else{
				return false;
			}
	}else{
		return false;
	}
}

function orgCheck($firstName){
	global $db;
	$sql = "SELECT id FROM user_details WHERE first_name='{$firstName}' ";
	if ($results=$db->query($sql)) {
			if ($results->num_rows===0) {
				return true;
			}else{
				return false;
			}
	}else{
		return false;
	}
}

function nameFix($value){
	return ucwords(strtolower($value));

}



?>