<?php

require 'config/core.php';


	$url = $_GET['profile'];

	$sql = " SELECT * FROM user_details WHERE url='{$url}' ";
	$results=$db->query($sql);
	$user_details=$results->fetch_object(); //profile owners user details
	$id = $user_details->id;

	$dob = $user_details->dob;
	$from = new DateTime($dob);
	$to   = new DateTime('today');
	$age = $from->diff($to)->y;
	
		

		$sql2 = "SELECT id FROM banned WHERE user_id={$id}";
	$results2=$db->query($sql2);

	if ($results->num_rows==0 || $results2->num_rows==1 ) {
		header("Location: page-unavailable.php");
	}
		
	mysqli_free_result($results);

		$sql = "SELECT * FROM profile_details WHERE user_id={$id}";

		if ($results=$db->query($sql)) {
			$profile_details=$results->fetch_object();
			mysqli_free_result($results);
		}

		$sql = "SELECT * FROM company_details WHERE user_id={$id}";

		if ($results=$db->query($sql)) {
			$company_details=$results->fetch_object();
			mysqli_free_result($results);
		}

		$sql = "SELECT title FROM skills JOIN user_skills ON skills.id=user_skills.skill_id WHERE user_skills.user_id={$id}";

		if ($results=$db->query($sql)) {
			//$skills=$results->fetch_all();
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$prof_skills[] = $row;
			}
			mysqli_free_result($results);
		}

		if ($user_details->accType == 0) {

		$sql = "SELECT * FROM employment WHERE user_id={$id}";

		if ($results=$db->query($sql)) {
			$employment=$results->fetch_object();
			mysqli_free_result($results);
		}

		

		if (!isset($employment)) {
			$employment->set = false;
			$employment->company = "";
			$employment->title = "";
			$employment->description = "";
			$fromDayOrg = 0;
			$fromMonthOrg = 0;
			$toDayOrg = 0;
			$toMonthOrg = 0;
		}else{
			$employment->set = true;
			$from_date = strtotime($employment->from_date);
			$fromDayOrg = date('d', $from_date);
			$fromMonthOrg = date('m', $from_date);
			$fromYearOrg = date('Y', $from_date);

			$to_date = strtotime($employment->to_date);
			$toDayOrg = date('d', $to_date);
			$toMonthOrg = date('m', $to_date);
			$toYearOrg = date('Y', $to_date);
		}

		

		$sql = "SELECT * FROM qualifications WHERE user_id={$id}";

		if ($results=$db->query($sql)) {
			$qualifications=$results->fetch_object();
			mysqli_free_result($results);
		}

		if (!isset($qualifications)) {
			$qualifications->set = false;
			$qualifications->title = "";
			$qualifications->description = "";
			$qualifications->level = "";
			$obtDayOrg = 0;
			$obtMonthOrg = 0;
		}else{
			$qualifications->set = true;
			$obtained = strtotime($qualifications->obtained);
			$obtDayOrg = date('d', $obtained);
			$obtMonthOrg = date('m', $obtained);
			$obtYearOrg = date('Y', $obtained);
		}

		}
	
	
if(!loggedIn()){
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $user_details->first_name." ".$user_details->last_name ;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/profile.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/vacancy.css">
	<script type="text/javascript" src="javascript/profile.js"></script>
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/vacancy.js"></script>	
	<script type="text/javascript" src="javascript/vacancy.js"></script>
</head>
<body>

	<div class="container">
	
		<?php include "templates/headerLoggedOut.php";?>

		<div class="main">
			<div class="mainScroll">	

				<div class="headPic" style="background-image: url('images/<?php echo $profile_details->cover_pic; ?>');">
					<img src="images/<?php echo $profile_details->profile_pic;?>">
					<div class="headInfo">
						<h2><?php echo $user_details->first_name." ".$user_details->last_name ;?></h2>
						<div><span>Account:</span> <?php 
							if ($user_details->accType == 0) {
								echo "Individual" ;
							}else{
								echo "Organisation" ;
							}
						?></div>
						<div><span>Hometown:</span> <?php echo $user_details->hometown ;?></div>
						<div><span>Country:</span> <?php echo $user_details->country ;?></div>
						<?php 
							if ($user_details->accType == 0) {
								?>		
							<div><span>Age:</span> <?php echo $age;?></div>
								<?php
							}
						?>
						<div><span>Online:</span> <?php 
							if ($user_details->online == 0) {
								echo "Offline" ;
							}else{
								echo "Online" ;
							}
						?></div>
					</div>
				</div>

				<nav>
					
					<a href="#" class="highlight" id="profAbout">About</a>

					<?php 
						if ($user_details->accType == 1) {
							?>		
						<a href="#" id="profVacancy">Career</a>
							<?php
						}
					?>
				</nav>


				<div id="profInput">
					<?php
						include "templates/prof_about.php";
						if ($user_details->accType == 1) {
							include "templates/prof_vacancy.php";
						}		
					?>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

<?php

}elseif(userBan()){
	header('Location: logout.php');
}else{ 

	

	$sql1 = " SELECT id FROM blocked WHERE user_id={$id} AND blocked_id={$_SESSION['id']}";
	$results1=$db->query($sql1);


	if ($results1->num_rows==1 ) {
		header("Location: page-unavailable.php");
	}else{
		$sql =  " SELECT id FROM blocked WHERE user_id={$_SESSION['id']} AND blocked_id={$id}";

		if ($results=$db->query($sql)) {
			if ($results->num_rows==1) {
				$blocked=true;
			}else{
				$blocked=false;
			}
			mysqli_free_result($results);	
		}

			$sql =  " SELECT id FROM friends WHERE (initialiser_id={$id} AND relationship=1) OR (reciever_id={$id} AND relationship=1)";

			if ($results=$db->query($sql)) {
				$numFriends=mysqli_num_rows($results);
				mysqli_free_result($results);	
			}

		$sql =  " SELECT * FROM friends WHERE (initialiser_id={$_SESSION['id']} AND reciever_id={$id}) OR ( initialiser_id={$id} AND reciever_id={$_SESSION['id']} )";

		if ($results=$db->query($sql)) {
			if ($results->num_rows==1) {
				$row=$results->fetch_object();
				$relationship=$row->relationship;
			}else{
				$relationship=false;
			}
			mysqli_free_result($results);	
		}
	}


	
	
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $user_details->first_name." ".$user_details->last_name ;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/profile.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/vacancy.css">
	<script type="text/javascript" src="javascript/profile.js"></script>
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
	<script type="text/javascript" src="http/js/friend.js"></script>
	<script type="text/javascript" src="http/js/block.js"></script>
	<script type="text/javascript" src="http/js/vacancy.js"></script>	
	<script type="text/javascript" src="javascript/vacancy.js"></script>
</head>
<body>

	<div class="container">
	
		<?php include "templates/header.php";?>

		<div class="main">
			<div class="mainScroll">	

				<div class="headPic" style="background-image: url('images/<?php echo $profile_details->cover_pic; ?>');">
					<img src="images/<?php echo $profile_details->profile_pic;?>">
					<div class="headInfo">
						<h2><?php echo $user_details->first_name." ".$user_details->last_name ;?></h2>
						<div><span>Account:</span> <?php 
							if ($user_details->accType == 0) {
								echo "Individual" ;
							}else{
								echo "Organisation" ;
							}
						?></div>
						<div><span>Hometown:</span> <?php echo $user_details->hometown ;?></div>
						<div><span>Country:</span> <?php echo $user_details->country ;?></div>
						<?php 
							if ($user_details->accType == 0) {
								?>		
							<div><span>Age:</span> <?php echo $age;?></div>
								<?php
							}
						?>
						<div><span>Online:</span> <?php 
							if ($user_details->online == 0) {
								echo "Offline" ;
							}else{
								echo "Online" ;
							}
						?></div>
					</div>
				</div>

				<nav>
				
					<?php if($_SESSION['id']!=$id){ 
							if($relationship!==false){ 
							switch ($relationship){
								case '1' : //REMOVE friend
								?>
								<button id="addFriend" value="3">Remove Friend</button>
								<?php
									break;
								case '0' : // cancel request
										if($row->initialiser_id==$_SESSION['id']){
								?>
								<button id="addFriend" value="1">Cancel Request</button>
								<?php
										}else{
								?>
								<button id="addFriend" value="2">Respond to request</button>
								<?php
										}
									break;
							};
						?><?php }else{?>
						<button id="addFriend" value="0">Add Friend</button>
					<?php }}?>

					
					<a href="#" class="highlight" id="profAbout">About</a>

					
					<a href="#" id="profFriends">Friends <span>(<?php echo $numFriends;?>)</span></a>

					<?php 
						if ($user_details->accType == 1) {
							?>		
						<a href="#" id="profVacancy">Career</a>
							<?php
						}
					?>

					<?php if($_SESSION['id']==$id){ ?>
						<a href="#" id="profNotif">Notifications</a>
					<?php } ?>

					<?php if($_SESSION['id']==$id || admin()==true){ ?>
						<a href="#" id="profEdit">Edit Profile</a>
					<?php } ?>



					<?php if($_SESSION['id']!=$id){ if($blocked){ ?>
						<button id="block" value="1">Unblock</button>
					<?php }else{?>
						<button id="block" value="0">Block</button>
					<?php }}?>
					
					
					


					
					<input type="hidden" id="initialiser" value="<?php echo $_SESSION['id']?>">
					<input type="hidden" id="reciever" value="<?php echo $user_details->id?>">
				</nav>


				<div id="profInput">
					<?php
						include "templates/prof_about.php";
						include "templates/prof_friends.php";
						if ($user_details->accType == 1) {
							include "templates/prof_vacancy.php";
						}
						include "templates/prof_notif.php";
						include "templates/prof_edit.php";

						
					?>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

<?php } ?>