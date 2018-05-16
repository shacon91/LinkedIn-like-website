<?php  

require "config/core.php";

if (compBan()) {
	header("Location: banned.php");
}else if(loggedIn()){
	header("Location: newsfeed.php");
}else{

	if (isset($_POST['submit'])) {

		$firstName = nameFix(secure($_POST['firstName']));
		$email = secure($_POST['email']);
		$hometown = nameFix(secure($_POST['hometown']));
		$country = nameFix(secure($_POST['country']));
		$password = secure($_POST['password']);
		$password2 = secure($_POST['password2']);
		$errors = [];
		$required_fields=['firstName','email','hometown','country','password','password2'];

		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors[0]='All required fields must be filled in' ;
				break 1;
			}
		}


		if(empty($errors[0])){

			if (strlen($firstName)>25) {
				$errors[1]='The first name cannot be longer then 25 characters';
			}elseif (orgCheck($firstName)===false) {
				$errors[1]='This organisation already has an account';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
			  $errors[1] = "Only letters and white space allowed"; 
			}

			if (strlen($email)>50) {
				$errors[3]='This email is too long please you a shorter email';
			}elseif (filter_var($email, FILTER_VALIDATE_EMAIL)===false) {
				$errors[3]='Please use a correct email address';
			}elseif (emailCheck($email)===false) {
				$errors[3]='This email is already in use';
			}


			if (strlen($hometown)>50) {
				$errors[5]='Invalid Hometown';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$hometown)) {
			  $errors[5] = "Invalid Hometown"; 
			}

			if (strlen($country)>50) {
				$errors[6]='Invalid Country';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$country)) {
			  $errors[6] = "Invalid Country"; 
			}


			if (strlen($password)>100) {
				$errors[8]='The password cannot be longer then 100 characters';
			}elseif(strlen($password)<6){
				$errors[8]='The password cannot be shorter then 6 characters';
			}elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,}$/', $password)){
				$errors[8]='Must contain atleast 1 number, 1 letter and 1 of these !@#$% ';
			}

			if ($password2 !== $password) {
				$errors[9]='Passwords must match';
			}

		}
		
		if(empty($errors)){
			$password = md5($password);
			$url = strtolower($firstName);
			$comp_ip = get_client_ip();
			$sql="INSERT INTO user_details (accType,email,password,first_name,url,comp_ip,hometown,country,online,last_login,created) 
			VALUES (1,'{$email}','{$password}','{$firstName}','{$url}','{$comp_ip}','{$hometown}','{$country}',1,NOW(),NOW())";

			if ($db->query($sql)) {

				if ($db->affected_rows==0) {	
					$errors[0]='Unfortunately we cannot register you at this time.';
				}else{
					$id=mysqli_insert_id($db);
					$_SESSION['id']=$id;

					$sql="INSERT INTO profile_details (user_id) VALUES ({$id})";
					$db->query($sql);

					header('Location: org-setup.php');
				}
			}
			
		}
		

	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/register.css">
</head>
<body>

	<div class="container">
		<div class="side1">
		<h1>MotorPros</h1>


		<form action="" method="POST">
			
			
			<h2>Register Organisation</h2>
			

			<?php if(isset($errors[0])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[0]; ?>
			 	</div>

			 <?php } ?>

			
			<input type="text" name="firstName" placeholder="Organisation Name" value="<?php if(isset($firstName)){echo $firstName;}?>">
			
			<?php if(isset($errors[1])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[1]; ?>
			 	</div>

			 <?php } ?>




			
			<input type="email" name="email" placeholder="Email" value="<?php if(isset($email)){echo $email;}?>">
			
			<?php if(isset($errors[3])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[3]; ?>
			 	</div>

			 <?php } ?>





			
			<input type="text" name="hometown" placeholder="Headquarters" value="<?php if(isset($hometown)){echo $hometown;}?>">
			
			<?php if(isset($errors[5])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[5]; ?>
			 	</div>

			 <?php } ?>


			
			<input type="text" name="country" placeholder="Country" value="<?php if(isset($country)){echo $country;}?>">
			
			<?php if(isset($errors[6])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[6]; ?>
			 	</div>

			 <?php } ?>

		

			
			<input type="password" name="password" placeholder="Password" >
			
			<?php if(isset($errors[8])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[8]; ?>
			 	</div>

			 <?php } ?>

			
			<input type="password" name="password2" placeholder="Re-Enter Password" >
			
			<?php if(isset($errors[9])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[9]; ?>
			 	</div>

			 <?php } ?>

			<input type="submit" name="submit" value="Register"><br>

			<div class="signIn">
				<a href="index.php">Sign In</a>
				<a href="register-user.php" class="regOrg">Individual Sign Up</a>
			</div>
		</form>
		</div>

	</div>

</body>
</html>
<?php } ?>