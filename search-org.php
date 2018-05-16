<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ 

/*
$sql = "
	SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=0 AND (first_name LIKE '{$_GET['userName']}%' OR last_name LIKE '{$_GET['userName']}%')  LIMIT 10
	UNION
	SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE accType=0 AND user_skills.skill_id = 1 LIMIT 10
	UNION
	SELECT first_name,last_name,url,profile_pic FROM employment JOIN user_details ON employment.user_id = user_details.id JOIN profile_details ON employment.user_id = profile_details.user_id WHERE employment.company LIKE '{$_GET['userCompany']}%' LIMIT 10
	";

*/

if (isset($_GET['searchOrg'])) {

	
	if (!empty($_GET['company'])) {
		
		$sql = "SELECT first_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=1 AND first_name LIKE '{$_GET['company']}%' LIMIT 20";

		if ($results=$db->query($sql)) {
			//$company=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
					$company[] = $row;
				}
			mysqli_free_result($results);

		}
	}else if (empty($_GET['company'])) {
			$error = "Please fill in some search criteria .";
		}

	if (empty($company)) {
			$error2 = "No company matches the search criteria.";
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
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">	

			

		<div id="searchOrg">
				<form action="" method="GET">
					<div><h2>Search Company</h2><a href="search.php" id="orgBackLink"><--Back</a></div>
					<div><label>Company Name: </label><input type="text" name="company" placeholder="Company" value="<?php if(isset($_GET['company'])){echo $_GET['company'];}?>"></div>
					<div><input type="submit" name="searchOrg" value="search"></div>
				</form>
				<?php if(isset($company)|| isset($error) || isset($error2)){   ?>
					<div class="searchReturns">
						<?php if(!empty($company)){ 
							foreach ($company as $key => $value) {		
						?>
							<div class="retItem">
								<img src="images/<?php echo $value['profile_pic'];?>">
								<a href="<?php echo $value['url'];?>"><?php echo $value['first_name'];?></a>
							</div>
						<?php } }else if(isset($error)) { ?>
							<div class="retItem">
								<?php echo $error;?>
							</div>
						<?php } else if(isset($error2)) {?>
							<div class="retItem">
								<?php echo $error2;?>
							</div>
						<?php }?>
					</div>
				<?php } ?>
			</div>


		</div>
	</div>
	</div>

</body>
</html>

<?php } ?>