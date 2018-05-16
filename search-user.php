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

	if (empty($userName) && $skills=='null' && empty($userCompany)) {
		$error = "Please fill in at least one search criteria box.";
	}else if (!empty($userName) && $skills!='null' && !empty($userCompany)) {
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id JOIN employment ON user_skills.user_id = employment.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND user_skills.skill_id = {$skills} AND employment.company LIKE '{$userCompany}%' LIMIT 10";


		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);

		}

	}else if(!empty($userName) && $skills!='null' && empty($userCompany)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND user_skills.skill_id = {$skills}  LIMIT 10";

		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}else if(empty($userName) && $skills!='null' && !empty($userCompany) ){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id JOIN employment ON user_skills.user_id = employment.user_id WHERE  accType=0 AND user_skills.skill_id = {$skills} AND (employment.company LIKE '{$userCompany}%') LIMIT 10";

		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userName) && !empty($userCompany) && $skills=='null'){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON user_details.id = profile_details.user_id JOIN employment ON user_details.id = employment.user_id WHERE  accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%') AND employment.company LIKE '{$userCompany}%' LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userName) && $skills=='null' && empty($userCompany)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE accType=0 AND (first_name LIKE '{$userName}%' OR last_name LIKE '{$userName}%')  LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}else if($skills!='null' && empty($userName) && empty($userCompany)){
		$sql="SELECT first_name,last_name,url,profile_pic FROM user_skills JOIN user_details ON user_skills.user_id = user_details.id JOIN profile_details ON user_skills.user_id = profile_details.user_id WHERE accType=0 AND user_skills.skill_id = {$skills} LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}else if(!empty($userCompany) && $skills=='null' && empty($userName)){
		$sql = "SELECT first_name,last_name,url,profile_pic FROM employment JOIN user_details ON employment.user_id = user_details.id JOIN profile_details ON employment.user_id = profile_details.user_id WHERE employment.company LIKE '{$userCompany}%' LIMIT 10";
		if ($results=$db->query($sql)) {
			//$user=$results->fetch_all(MYSQL_ASSOC);
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$user[] = $row;
			}
			mysqli_free_result($results);
		}
	}

	if (empty($user)) {
		$error2 = "No user matches the search criteria.";
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

			<div id="searchUser">
				<form action="" method="GET">
					<div><h2>Search User</h2><a href="search.php" id="userBackLink"><--Back</a></div>
					<div><label>Name: </label><input type="text" name="userName" placeholder="Name" value="<?php if(isset($_GET['userName'])){echo $_GET['userName'];}?>"></div>
					<div>
						<label>Skill:</label>
						<select  name="skills" >
		                    <option value="null">--</option>

		                    <?php 
								$sql = "SELECT title FROM skills";	

								if ($results=$db->query($sql)) {
									//$skillSet=$results->fetch_all();
									while ($row = $results->fetch_array(MYSQLI_BOTH)) {
										$skillSet[] = $row;
									}
									mysqli_free_result($results);
								}
									
								foreach ($skillSet as $key => $value) { 
							?>
								<option value="<?php echo $key+1;?>" <?php if(isset($skills) && $skills==($key+1)){ echo "selected"; }?>><?php echo $value[0];?></option>
							<?php } ?>
		                    
	                	</select>
					</div>
					<div><label>Company Worked for: </label><input type="text" name="userCompany" placeholder="Company" value="<?php if(isset($_GET['userCompany'])){echo $_GET['userCompany'];}?>"></div>
					<div><input type="submit" name="searchUser" value="search"></div>
				</form>
				<?php if(isset($user) || isset($error) || isset($error2)){   ?>
					<div class="searchReturns">

						<?php  if(!empty($user)){ 
							foreach ($user as $key => $value) {		
						?>
							<div class="retItem">
								<img src="images/<?php echo $value['profile_pic'];?>">
								<a href="<?php echo $value['url'];?>"><?php echo $value['first_name']." ".$value['last_name'];?></a>
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