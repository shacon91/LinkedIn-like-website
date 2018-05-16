<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ 

if (isset($_GET['searchUser'])){

	$userName = secure($_GET['userName']);
	$skills = $_GET['skills'];
	$userCompany = secure($_GET['userCompany']);

	if (!empty($userName) && $skills!='null' && !empty($userCompany)) {
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id JOIN employment ON user_skills.user_id = employment.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND user_skills.skill_id = {$skills} AND employment.company LIKE '{$userCompany}%' LIMIT 10";


		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}

	}else if(!empty($userName) && $skills!='null' && empty($userCompany)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND user_skills.skill_id = {$skills}  LIMIT 10";

		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}else if(empty($userName) && $skills!='null' && !empty($userCompany) ){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id JOIN employment ON user_skills.user_id = employment.user_id WHERE  accType=0 AND user_skills.skill_id = {$skills} AND (employment.company LIKE '{$userCompany}%') LIMIT 10";

		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userName) && !empty($userCompany) && $skills=='null'){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON user_details.id = profile_details.user_id JOIN employment ON user_details.id = employment.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND employment.company LIKE '{$userCompany}%' LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userName) && $skills=='null' && empty($userCompany)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%')  LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}else if($skills!='null' && empty($userName) && empty($userCompany)){
		$sql="SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE accType=0 AND user_skills.skill_id = {$skills} LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userCompany) && $skills=='null' && empty($userName)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM employment JOIN user_details ON employment.user_id = user_details.id JOIN profile_details ON employment.user_id = profile_details.user_id WHERE employment.company LIKE '{$userCompany}%' LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$user = $row;
			}
			mysqli_free_result($results);
		}
	}


}




/*
$sql = "
	SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=0 AND (first_name LIKE '{$_GET['userName']}%' OR last_name LIKE '{$_GET['userName']}%')  LIMIT 10
	UNION
	SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE accType=0 AND user_skills.skill_id = 1 LIMIT 10
	UNION
	SELECT first_name,last_name,url,profile_pic FROM employment JOIN user_details ON employment.user_id = user_details.id JOIN profile_details ON employment.user_id = profile_details.user_id WHERE employment.company LIKE '{$_GET['userCompany']}%' LIMIT 10
	";

*/
	



if (isset($_GET['searchOrg']) && !empty($_GET['company'])) {
	
	$sql = "SELECT first_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=1 AND first_name LIKE '{$_GET['company']}%' LIMIT 20";

	if ($results=$db->query($sql)) {
		//$company=$results->fetch_all(MYSQL_ASSOC);
		while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$company = $row;
			}
		mysqli_free_result($results);
	}
}







?>

<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/search.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<!--<script type="text/javascript" src="javascript/search.js"></script>-->
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">	

			<div id="searchType">
				<a href="search-user.php">Search User</a>
				<a href="search-org.php">Search Organisation</a>
			</div>


		
	</div>
	</div>

</body>
</html>

<?php } ?>