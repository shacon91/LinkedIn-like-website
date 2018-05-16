<?php  

require "config/core.php";

if (compBan()==true) {
	header("Location: banned.php");
}else if(loggedIn()){
	header("Location: newsfeed.php");
}else{
	if (isset($_POST['submit'])) {
		

		if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])) {
			$email = secure($_POST['email']);
			$password = md5(secure($_POST['password']));

			$sql="SELECT id FROM user_details WHERE email='{$email}' AND password='{$password}'";
			if ($results=$db->query($sql)) {
				if ($results->num_rows===1) {

					if ($row=$results->fetch_object()) {
						$id = $row->id;
						
						$sql="UPDATE user_details SET online=1 WHERE id={$id}";

						if ($db->query($sql)) {

							
								$_SESSION['id']= $id;
								if(userBan()==true ){
									session_destroy();
									$error='You have been banned';
								}else{
									header('Location: newsfeed.php');
								}
							
						}
						
					}
					
				}else{
					$error="Please enter a correct email & password.";
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
	<title>Sign In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/login.css">
	<script type="text/javascript" src="javascript/login.js"></script>
</head>
<body>

	<div class="container">

		<div class="side1">

			<h1>MotorPros</h1>


			<form action="" method="POST">
				<h2>Sign in</h2>

				<?php if(isset($error)){ ?>

				 	<div class="warning">
				 		<?php echo $error; ?>
				 	</div>

				 <?php } ?>
				
				<label for="email">Email:</label><input type="email" name="email" placeholder="Email" value="<?php if(isset($email)){echo $email;}?>"><br>
				<label for="password">Password:</label><input type="password" name="password" placeholder="Password" ><br>
				<input type="submit" name="submit" value="Sign In"><br>
				
				<div class="loginLinks">
					<a href="register-user.php">Sign Up?</a>
					<a href="#" id="loginAbout">About Us</a>
				</div>
			</form>
			
			<div id="loginAboutBox">
				We are a social networking platform where companys can sign up with us and display vacancies of jobs they  have to offer and then can recruit users of the site. Top companies on our site currently are <a href="ferrari">Ferrari</a>, <a href="audi">Audi</a> and <a href="mclaren">McLaren</a>. 
				<br><br>
				On the other side of the coin users can sign up and display their information while applying and connecting with companies. They are suggest jobs based off the skills they identify as having. Our top users include <a href="tony.kanaan.17" style="display: inline;">Tony Kanaan</a>, <a href="fernando.alonso.18" style="display: inline;">fernando Alonso</a> and <a href="leena.gade.19" style="display: inline;">Leena Gade</a>. 
			</div>
			
		</div>


	</div>

</body>
</html>
<?php } ?>