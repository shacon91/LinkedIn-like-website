<?php  

require "config/core.php";

if (compBan()) {
	header("Location: banned.php");
}else if(loggedIn()){
	header("Location: newsfeed.php");
}else{

	if (isset($_POST['submit'])) {

		$firstName = nameFix(secure($_POST['firstName']));
		$lastName = nameFix(secure($_POST['lastName']));
		$email = secure($_POST['email']);
		$day = $_POST['day'];
		$month = $_POST['month'];
		$year = secure($_POST['year']);
		$dob = $year.'-'.$month.'-'.$day;
		$hometown = nameFix(secure($_POST['hometown']));
		$country = nameFix(secure($_POST['country']));
		$password = secure($_POST['password']);
		$password2 = secure($_POST['password2']);
		$errors = [];
		$required_fields=['firstName','lastName','email','day','month','year','hometown','country','password','password2'];



		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors[0]='All required fields must be filled in' ;
				break 1;
			}
		}


		if(empty($errors[0])){

			if (strlen($firstName)>25) {
				$errors[1]='The first name cannot be longer then 25 characters';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
			  $errors[1] = "Only letters and white space allowed"; 
			}

			if (strlen($lastName)>25) {
				$errors[2]='The last name cannot be longer then 25 characters';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
			  $errors[2] = "Only letters and white space allowed";  
			}

			if (strlen($email)>50) {
				$errors[3]='This email is too long please you a shorter email';
			}elseif (filter_var($email, FILTER_VALIDATE_EMAIL)===false) {
				$errors[3]='Please use a correct email address';
			}elseif (emailCheck($email)===false) {
				$errors[3]='This email is already in use';
			}

			if ($day === 0 || $month === 0) {
				$errors[4]='Please enter a correct Date';
			}elseif ($year<1940 || $year>2018) {
				$errors[4]='Please enter a correct Date';
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

			if(isset($_POST['gender'])){
				$gender = $_POST['gender'];
			}else{
				$errors[7]='Please choose a gender';
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
			$comp_ip = get_client_ip();
			$sql="INSERT INTO user_details (accType,email,password,first_name,last_name,comp_ip, dob, hometown,country,gender,online,last_login,created) 
			VALUES (0,'{$email}','{$password}','{$firstName}','{$lastName}','{$comp_ip}','{$dob}','{$hometown}','{$country}','{$gender}',1,NOW(),NOW())";

			if ($db->query($sql)) {

				if ($db->affected_rows==0) {
					$errors[0]='Unfortunately we cannot register you at this time.';
				}else{
					$id=mysqli_insert_id($db);
					$url=strtolower($firstName).".".strtolower($lastName).".".$id;
					$sql="UPDATE user_details SET url='{$url}' WHERE id={$id}";
					if ($db->query($sql)) {
						if ($db->affected_rows==0) {
							$errors[0]='Unfortunately we cannot register you at this time.';
						}else{
							$_SESSION['id']=$id;

							//fill in details for profile here

							$sql="INSERT INTO profile_details (user_id) VALUES ({$id})";
							$db->query($sql);

							header('Location: individual-setup.php');
						}	
					}
					
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
			
		
			<h2>Register Individual</h2>
				
			

			<?php if(isset($errors[0])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[0]; ?>
			 	</div>

			 <?php } ?>

			
			<!--<label for="firstName">First Name:</label>-->
			<input type="text" name="firstName" placeholder="First Name" value="<?php if(isset($firstName)){echo $firstName;}?>">
			
			<?php if(isset($errors[1])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[1]; ?>
			 	</div>

			 <?php } ?>


			
			<input type="text" name="lastName" placeholder="Surname" value="<?php if(isset($lastName)){echo $lastName;}?>">
		
			<?php if(isset($errors[2])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[2]; ?>
			 	</div>

			 <?php } ?>


			<input type="email" name="email" placeholder="Email" value="<?php if(isset($email)){echo $email;}?>">
			
			<?php if(isset($errors[3])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[3]; ?>
			 	</div>

			 <?php } ?>


			<div class="regInput">
				<span style="margin-left: 0;">Date of Birth:</span>
				<select  name="day">
		                <option value="0">--</option>
	                    <option value="01" <?php if(isset($day) && $day==01){ echo "selected"; } ?>>1</option>
	                    <option value="02" <?php if(isset($day) && $day==02){ echo "selected"; } ?>>2</option>
	                    <option value="03" <?php if(isset($day) && $day==03){ echo "selected"; } ?>>3</option>
	                    <option value="04" <?php if(isset($day) && $day==04){ echo "selected"; } ?>>4</option>
	                    <option value="05" <?php if(isset($day) && $day==05){ echo "selected"; } ?>>5</option>
	                    <option value="06" <?php if(isset($day) && $day==06){ echo "selected"; } ?>>6</option>
	                    <option value="07" <?php if(isset($day) && $day==07){ echo "selected"; } ?>>7</option>
	                    <option value="08" <?php if(isset($day) && $day==8){ echo "selected"; } ?>>8</option>
	                    <option value="09" <?php if(isset($day) && $day==9){ echo "selected"; } ?>>9</option>
	                    <option value="10" <?php if(isset($day) && $day==10){ echo "selected"; } ?>>10</option>
	                    <option value="11" <?php if(isset($day) && $day==11){ echo "selected"; } ?>>11</option>
	                    <option value="12" <?php if(isset($day) && $day==12){ echo "selected"; } ?>>12</option>
	                    <option value="13" <?php if(isset($day) && $day==13){ echo "selected"; } ?>>13</option>
	                    <option value="14" <?php if(isset($day) && $day==14){ echo "selected"; } ?>>14</option>
	                    <option value="15" <?php if(isset($day) && $day==15){ echo "selected"; } ?>>15</option>
	                    <option value="16" <?php if(isset($day) && $day==16){ echo "selected"; } ?>>16</option>
	                    <option value="17" <?php if(isset($day) && $day==17){ echo "selected"; } ?>>17</option>
	                    <option value="18" <?php if(isset($day) && $day==18){ echo "selected"; } ?>>18</option>
	                    <option value="19" <?php if(isset($day) && $day==19){ echo "selected"; } ?>>19</option>
	                    <option value="20" <?php if(isset($day) && $day==20){ echo "selected"; } ?>>20</option>
	                    <option value="21" <?php if(isset($day) && $day==21){ echo "selected"; } ?>>21</option>
	                    <option value="22" <?php if(isset($day) && $day==22){ echo "selected"; } ?>>22</option>
	                    <option value="23" <?php if(isset($day) && $day==23){ echo "selected"; } ?>>23</option>
	                    <option value="24" <?php if(isset($day) && $day==24){ echo "selected"; } ?>>24</option>
	                    <option value="25" <?php if(isset($day) && $day==25){ echo "selected"; } ?>>25</option>
	                    <option value="26" <?php if(isset($day) && $day==26){ echo "selected"; } ?>>26</option>
	                    <option value="27" <?php if(isset($day) && $day==27){ echo "selected"; } ?>>27</option>
	                    <option value="28" <?php if(isset($day) && $day==28){ echo "selected"; } ?>>28</option>
	                    <option value="29" <?php if(isset($day) && $day==29){ echo "selected"; } ?>>29</option>
	                    <option value="30" <?php if(isset($day) && $day==30){ echo "selected"; } ?>>30</option>
	                    <option value="31" <?php if(isset($day) && $day==31){ echo "selected"; } ?>>31</option>
           		</select>
                <select  name="month" style="width: 100px;">
                	<option value="0">--</option>
                    <option value="01" <?php if(isset($month) && $month==01){ echo "selected"; } ?>>January</option>
                    <option value="02" <?php if(isset($month) && $month==02){ echo "selected"; } ?>>February</option>
                    <option value="03" <?php if(isset($month) && $month==03){ echo "selected"; } ?>>March</option>
                    <option value="04" <?php if(isset($month) && $month==04){ echo "selected"; } ?>>April</option>
                    <option value="05" <?php if(isset($month) && $month==05){ echo "selected"; } ?>>May</option>
                    <option value="06" <?php if(isset($month) && $month==06){ echo "selected"; } ?>>June</option>
                    <option value="07" <?php if(isset($month) && $month==07){ echo "selected"; } ?>>July</option>
                    <option value="08" <?php if(isset($month) && $month==8){ echo "selected"; } ?>>August</option>
                    <option value="09" <?php if(isset($month) && $month==9){ echo "selected"; } ?>>September</option>
                    <option value="10" <?php if(isset($month) && $month==10){ echo "selected"; } ?>>October</option>
                    <option value="11" <?php if(isset($month) && $month==11){ echo "selected"; } ?>>November</option>
                    <option value="12" <?php if(isset($month) && $month==12){ echo "selected"; } ?>>December</option>
                </select>
				<input type="number" name="year" placeholder="Year" min="1940" max="2018" value="<?php if(isset($year)){echo $year;}?>">
			</div>
			<?php if(isset($errors[4])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[4]; ?>
			 	</div>

			 <?php } ?>



			
			<input type="text" name="hometown" placeholder="Hometown" value="<?php if(isset($hometown)){echo $hometown;}?>">
			
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

			<div class="regInput">
				<span style="margin-left: 0;">Male:</span> <input type="radio" name="gender" value="0" <?php if(isset($_POST['gender']) && $_POST['gender']==0){ echo "checked"; }?>>
				<span>Female:</span> <input type="radio" name="gender" value="1" <?php if(isset($_POST['gender']) && $_POST['gender']==1){ echo "checked"; }?>>
				<span>Other:</span> <input type="radio" name="gender" value="2" <?php if(isset($_POST['gender']) && $_POST['gender']==2){ echo "checked"; }?>>
			</div>
			<?php if(isset($errors[7])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[7]; ?>
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
				<a href="index.php">Sign In?</a>
				<a href="register-org.php">Organisation Sign Up</a>
			</div>
		</form>
		</div>
		<div class="side2">
		</div>
	</div>

</body>
</html>
<?php } ?>