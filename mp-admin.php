<?php  

require "config/core.php";

if (compBan()==true) {
	header("Location: banned.php");
}else if(loggedIn() && !admin()){
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
	<?php
}else if(loggedIn() && admin()){
	header('Location: admin.php');
}else{
	if (isset($_POST['submit'])) {
		

		if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])) {
			$email = secure($_POST['email']);
			$password = md5(secure($_POST['password']));

			$sql="SELECT id FROM user_details WHERE email='{$email}' AND password='{$password}' AND admin=1";
			if ($results=$db->query($sql)) {
				if ($results->num_rows===1) {

					if ($row=$results->fetch_object()) {
						$id = $row->id;
						
						$sql="UPDATE user_details SET online=1 WHERE id={$id}";

						if ($db->query($sql)) {

							if ($db->affected_rows==0) {
								$error='Unfortunately we cannot register you at this time.';
							}else{	
								$_SESSION['id']= $id;
								if(userBan()==true ){
									session_destroy();
									$error='You have been banned';
								}else{
									header('Location: admin.php');
								}
							}
						}
							
					}
					
				}else{
					$error="These credentials do not have admin access";
				}
			}

		}else{
			$error = "Please fill in both fields";
		}
	}
		
	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/login.css">
</head>
<body>

	<div class="container">

		<h1>MotorPros</h1>


		<form action="" method="POST">
			<h2>Admin Sign in</h2>

			<?php if(isset($error)){ ?>

			 	<div class="warning">
			 		<?php echo $error; ?>
			 	</div>

			 <?php } ?>
			
			<label for="email">Email:</label><input type="email" name="email" placeholder="Email" value="<?php if(isset($email)){echo $email;}?>"><br>
			<label for="password">Password:</label><input type="password" name="password" placeholder="Password" ><br>
			<input type="submit" name="submit" value="Sign In"><br>

		</form>

	</div>

</body>
</html>
<?php } ?>