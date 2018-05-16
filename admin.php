<?php
require 'config/core.php';

if(loggedIn() && admin()){

	if (isset($_POST['submit'])) {
		$email=secure($_POST['email']);
		$error;
		$success;

		if (empty($email)) {
			$error='Please enter an email';
		}else{
			if (emailCheck($email)==false) {
				 $sql="UPDATE user_details SET admin=1 WHERE email='{$email}' ";
	             $db->query($sql);
	             $success = "Successfully asigned ".$email." as an admin";
			}else{
				$error = "This email is not asigned to a user";
			}
		}
	}

	$sql = "SELECT user_details.id,user_details.url,user_details.first_name,user_details.last_name,profile_details.profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE user_details.admin=1";
	if ($results=$db->query($sql)) {
			//$skills=$results->fetch_all();
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
				$administrators[] = $row;
			}
			mysqli_free_result($results);
		}

	if (isset($_POST['submitRemoveAdmin'])) {
		$adminId=$_POST['adminId'];

		$sql="UPDATE user_details SET admin=0 WHERE id={$adminId} ";
		$db->query($sql);
		header("Location: admin.php");
	}



	if (isset($_POST['submitBanLift'])) {
		$userId=$_POST['userId'];

		$sql=" DELETE FROM banned WHERE user_id={$userId}"; 
		$db->query($sql);
		header("Location: admin.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/admin.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">

			<form action="" method="POST">
				<h2>Add an Administrator</h2>
				<p>Please enter their email</p>
				 	 <?php if(isset($error)){ ?>

		                <div class="warning">
		                    <?php echo $error; ?>
		                </div>

            		<?php } ?>
            		<?php if(isset($success)){ ?>

		                <div class="success">
		                    <?php echo $success; ?>
		                </div>

            		<?php } ?>
				<input type="email" name="email" placeholder="Email">
				<input type="submit" name="submit" value="Add">
			</form>

			<div id="removeAdmin">
				<h2>Remove an Administrator</h2>
				<span>
					<?php foreach($administrators as$key => $value){?>
					<form class="userRemoveAdmin" action="" method="POST">
						<img src="images/<?php echo $administrators[$key]['profile_pic'];?>">
						<a href="<?php echo $administrators[$key]['url'];?>"><?php echo $administrators[$key]['first_name']." ".$administrators[$key]['last_name'];?></a>
						<input type="hidden" name="adminId" value="<?php echo $administrators[$key]['id'];?>">
						<input type="submit" name="submitRemoveAdmin" value="Remove">
					</form>
					<?php } ?>
					
				</span>
			</div>

			<div id="bannedUsers">
				<h2>List of Banned Users</h2>
				<span>
					<?php 
					$sql = "SELECT user_details.id,user_details.email FROM user_details RIGHT JOIN banned ON banned.user_id = user_details.id ";
					if ($results=$db->query($sql)) {
							//$skills=$results->fetch_all();
							while ($row = $results->fetch_array(MYSQLI_BOTH)) {
								$bannedUsers[] = $row;
							}
							mysqli_free_result($results);
					}

					if (empty($bannedUsers)) {
						echo "There are currently no banned users";
					}else{

					foreach($bannedUsers as$key => $value){?>
					<form class="userLiftBan" action="" method="POST">
						<span><?php echo $bannedUsers[$key]['email'];?></span>
						<input type="hidden" name="userId" value="<?php echo $bannedUsers[$key]['id'];?>">
						<input type="submit" name="submitBanLift" value="Lift Ban">
					</form>
					<?php } } ?>
				</span>
			</div>
			


		</div>
		</div>
	</div>

</body>
</html>

<?php
}else{
?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>No Access</title>
		<link rel="stylesheet" type="text/css" href="stylesheets/login.css">
	</head>
	<body>

		<div class="warning">
			You are not allowed access to this page.
		</div>
	
	</body>
	</html>
<?php } ?>