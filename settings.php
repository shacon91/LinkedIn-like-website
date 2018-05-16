<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ 


$id=$_SESSION['id'];
$sql = " SELECT * FROM user_details WHERE id={$id} ";
$results=$db->query($sql);
$user_details=$results->fetch_object(); //profile owners user details
$time = strtotime($user_details->dob);
$dayOrg = date('d', $time);
$monthOrg = date('m', $time);
$yearOrg = date('Y', $time);

if (isset($_POST['submitGen'])) {
	if(organisation()==false){ 
		$firstName = nameFix(secure($_POST['firstName']));
		$lastName = nameFix(secure($_POST['lastName']));
		$day = $_POST['day'];
		$month = $_POST['month'];
		$year = secure($_POST['year']);
		$dob = $year.'-'.$month.'-'.$day;
		$hometown = nameFix(secure($_POST['hometown']));
		$country = nameFix(secure($_POST['country']));
		$gender = $_POST['gender'];
		$errors = [];
		$required_fields=['firstName','lastName','day','month','year','hometown','country'];
		$original_values=[ $user_details->first_name, $user_details->last_name, $dayOrg, $monthOrg, $yearOrg, $user_details->hometown, $user_details->country,$user_details->gender];


		$updated=0;
		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors[0]='All required fields must be filled in' ;
				break 1;
			}else if (in_array($value, $original_values)) {
				$updated++;
			}		
		}
		

		if (empty($errors[0])) {
			if ($updated==8) {
				$errors[6]='Please choose a value to update' ;
			}
		}

		if(empty($errors[0]) && empty($errors[7])){

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

			if ($day === 0 || $month === 0) {
				$errors[3]='Please enter a correct Date';
			}elseif ($year<1940 || $year>2018) {
				$errors[3]='Please enter a correct Date';
			}

			if (strlen($hometown)>50) {
				$errors[4]='Invalid Hometown';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$hometown)) {
			  $errors[4] = "Invalid Hometown"; 
			}

			if (strlen($country)>50) {
				$errors[5]='Invalid Country';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$country)) {
			  $errors[5] = "Invalid Country"; 
			}
		}
		
		if(empty($errors)){
			$url=strtolower($firstName).".".strtolower($lastName).".".$id;
			$sql="UPDATE user_details SET first_name='{$firstName}',last_name='{$lastName}', url='{$url}',dob='{$dob}', hometown='{$hometown}',country='{$country}', gender='{$gender}' WHERE id={$id}";
			
			if ($db->query($sql)) {
				if ($db->affected_rows==0) {
					$errors[0]='Unfortunately we cannot update your information at this time.';
				}else{
					$success = "Updated Successfully";
					header("Location: settings.php");
				}
			}	
		}
	}else{
		$firstName = nameFix(secure($_POST['firstName']));
		$hometown = nameFix(secure($_POST['hometown']));
		$country = nameFix(secure($_POST['country']));
		$errors = [];
		$required_fields=['firstName','hometown','country'];
		$original_values=[ $user_details->first_name, $user_details->hometown, $user_details->country];


		$updated=0;
		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors[0]='All required fields must be filled in' ;
				break 1;
			}else if (in_array($value, $original_values)) {
				$updated++;
			}		
		}
		

		if (empty($errors[0])) {
			if ($updated==3) {
				$errors[6]='Please choose a value to update' ;
			}
		}


		if(empty($errors[0]) && empty($errors[7])){

			if (strlen($firstName)>25) {
				$errors[1]='The first name cannot be longer then 25 characters';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
			  $errors[1] = "Only letters and white space allowed"; 
			}

			if (strlen($hometown)>50) {
				$errors[4]='Invalid Hometown';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$hometown)) {
			  $errors[5] = "Invalid Hometown"; 
			}

			if (strlen($country)>50) {
				$errors[5]='Invalid Country';
			}else if (!preg_match("/^[a-zA-Z ]*$/",$country)) {
			  $errors[5] = "Invalid Country"; 
			}
		}
		
		if(empty($errors)){
			$url=strtolower($firstName);
			$sql="UPDATE user_details SET first_name='{$firstName}', url='{$url}', hometown='{$hometown}',country='{$country}' WHERE id={$id}";
			
			if ($db->query($sql)) {
				if ($db->affected_rows==0) {
					$errors[0]='Unfortunately we cannot update your information at this time.';
				}else{
					$success = "Updated Successfully";
					header("Location: settings.php");
				}
			}	
		}
	}
}

	if (isset($_POST['submitEmail'])) {

		
		$newEmail = secure($_POST['newEmail']);
		$password = secure($_POST['emailPassword']);
		$errors2 = [];
		$required_fields=['oldEmail','newEmail','emailPassword'];



		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors2[0]='All required fields must be filled in' ;
				break 1;
			}
		}


		if(empty($errors2[0])){
			if (md5($password) != $user_details->password) {
				$errors2[1]='The password you entered is incorrect';
			}

			if (strlen($newEmail)>50) {
				$errors2[2]='This email is too long please you a shorter email';
			}
			if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)===false) {
				$errors2[3]='Please use a correct email address';
			}
			if (emailCheck($newEmail)===false) {
				$errors2[4]='This email is already in use';
			}
		}
		
		if(empty($errors2)){
			
			$sql="UPDATE user_details SET email='{$newEmail}' WHERE id={$id}";
			
			if ($db->query($sql)) {
				if ($db->affected_rows==0) {
					$errors2[0]='Unfortunately we cannot update your information at this time.';
				}else{
					$success2 = "Updated Successfully";
					header("Location: settings.php");
				}
			}
			
		}
	}

	if (isset($_POST['submitPassword'])) {

		$oldPassword = secure($_POST['oldPassword']);
		$newPassword = secure($_POST['newPassword']);
		$password2 = secure($_POST['password2']);
		$errors3 = [];
		$required_fields=['oldPassword','newPassword','password2'];



		foreach ($_POST as $key => $value) {
			if (empty($value) && in_array($key, $required_fields)) {
				$errors3[0]='All required fields must be filled in' ;
				break 1;
			}
		}


		if(empty($errors3[0])){
			if (md5($oldPassword) != $user_details->password) {
				$errors3[1]='The old password you entered is incorrect';
			}
			if (strlen($newPassword)>100) {
				$errors3[2]='The password cannot be longer then 100 characters';
			}
			if(strlen($newPassword)<6){
				$errors3[3]='The password cannot be shorter then 6 characters';
			}
			if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,}$/', $password)){
				$errors3[4]='Must contain atleast 1 letter and 1 number';
			}
			if ($password2 !== $newPassword) {
				$errors3[5]='Passwords must match';
			}
		}
		
		if(empty($errors3)){
			$newPassword = md5($newPassword);
			$sql="UPDATE user_details SET password='{$newPassword}' WHERE id={$id}";
			
			if ($db->query($sql)) {
				if ($db->affected_rows==0) {
					$errors3[0]='Unfortunately we cannot update your information at this time.';
				}else{
					$success3 = "Updated Successfully";
					header("Location: settings.php");
				}
			}
			
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/settings.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
	<script type="text/javascript" src="javascript/settings.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">	

			<a href="#" class="settingsPageLinks" id="genInfoLink">General Info</a>
			<form action="" method="post" class="settingsForms" id="genInfoForm">
				<?php if(!empty($errors)){ ?>

				 	<div class="warning">
				 		<ul>
					 	<?php 
					 	foreach ($errors as $key => $value) {

					 		echo "<li>".$errors[$key]."</li>";
					 	}
					 		 ?>
					 	</ul>
				 	</div>

				 <?php }else if(isset($success)){ ?>

				 	<div class="success">
					 	<?php echo $success; ?>
				 	</div>

				 <?php } ?>
				<div class="regInput" >
					<input type="text" name="firstName" placeholder="First Name" class="inputText" value="<?php  
					if(isset($firstName)){echo $firstName;}else if(isset($user_details->first_name)){echo $user_details->first_name;}
					  ?>">
				<?php if(organisation()==false){ ?>
					<input type="text" name="lastName" placeholder="Last Name" class="inputText" value="<?php  
					if(isset($lastName)){echo $lastName;}else if(isset($user_details->last_name	)){echo $user_details->last_name;}
					  ?>">
				<?php } ?>
					<input type="text" name="hometown" placeholder="Hometown" class="inputText" value="<?php 
					if(isset($hometown)){echo $hometown;}else if(isset($user_details->hometown	)){echo $user_details->hometown;}
					?>">	
					<input type="text" name="country" placeholder="Country" class="inputText" value="<?php 
					if(isset($country)){echo $country;}else if(isset($user_details->country	)){echo $user_details->country;}
					?>">
				</div>
				<?php if(organisation()==false){ ?>
				<div class="regInput">

					<span style="margin-left: 0;">Date of Birth:</span>
					<select  name="day">
	                    <option value="0">--</option>
	                    <option value="01" <?php if(date('d', $time) == 01){echo "selected";} ?>>1</option>
	                    <option value="02" <?php if(date('d', $time) == 02){echo "selected";} ?>>2</option>
	                    <option value="03" <?php if(date('d', $time) == 03){echo "selected";} ?>>3</option>
	                    <option value="04" <?php if(date('d', $time) == 04){echo "selected";} ?>>4</option>
	                    <option value="05" <?php if(date('d', $time) == 05){echo "selected";} ?>>5</option>
	                    <option value="06" <?php if(date('d', $time) == 06){echo "selected";} ?>>6</option>
	                    <option value="07" <?php if(date('d', $time) == 07){echo "selected";} ?>>7</option>
	                    <option value="08" <?php if(date('d', $time) == 08){echo "selected";} ?>>8</option>
	                    <option value="09" <?php if(date('d', $time) == 09){echo "selected";} ?>>9</option>
	                    <option value="10" <?php if(date('d', $time) == 10){echo "selected";} ?>>10</option>
	                    <option value="11" <?php if(date('d', $time) == 11){echo "selected";} ?>>11</option>
	                    <option value="12" <?php if(date('d', $time) == 12){echo "selected";} ?>>12</option>
	                    <option value="13" <?php if(date('d', $time) == 13){echo "selected";} ?>>13</option>
	                    <option value="14" <?php if(date('d', $time) == 14){echo "selected";} ?>>14</option>
	                    <option value="15" <?php if(date('d', $time) == 15){echo "selected";} ?>>15</option>
	                    <option value="16" <?php if(date('d', $time) == 16){echo "selected";} ?>>16</option>
	                    <option value="17" <?php if(date('d', $time) == 17){echo "selected";} ?>>17</option>
	                    <option value="18" <?php if(date('d', $time) == 18){echo "selected";} ?>>18</option>
	                    <option value="19" <?php if(date('d', $time) == 19){echo "selected";} ?>>19</option>
	                    <option value="20" <?php if(date('d', $time) == 20){echo "selected";} ?>>20</option>
	                    <option value="21" <?php if(date('d', $time) == 21){echo "selected";} ?>>21</option>
	                    <option value="22" <?php if(date('d', $time) == 22){echo "selected";} ?>>22</option>
	                    <option value="23" <?php if(date('d', $time) == 23){echo "selected";} ?>>23</option>
	                    <option value="24" <?php if(date('d', $time) == 24){echo "selected";} ?>>24</option>
	                    <option value="25" <?php if(date('d', $time) == 25){echo "selected";} ?>>25</option>
	                    <option value="26" <?php if(date('d', $time) == 26){echo "selected";} ?>>26</option>
	                    <option value="27" <?php if(date('d', $time) == 27){echo "selected";} ?>>27</option>
	                    <option value="28" <?php if(date('d', $time) == 28){echo "selected";} ?>>28</option>
	                    <option value="29" <?php if(date('d', $time) == 29){echo "selected";} ?>>29</option>
	                    <option value="30" <?php if(date('d', $time) == 30){echo "selected";} ?>>30</option>
	                    <option value="31" <?php if(date('d', $time) == 31){echo "selected";} ?>>31</option>
	           		</select>
	                <select  name="month" >
	                    <option value="0">--</option>
	                    <option value="01" <?php if(date('m', $time) == 01){echo "selected";} ?>>January</option>
	                    <option value="02" <?php if(date('m', $time) == 02){echo "selected";} ?>>February</option>
	                    <option value="03" <?php if(date('m', $time) == 03){echo "selected";} ?>>March</option>
	                    <option value="04" <?php if(date('m', $time) == 04){echo "selected";} ?>>April</option>
	                    <option value="05" <?php if(date('m', $time) == 05){echo "selected";} ?>>May</option>
	                    <option value="06" <?php if(date('m', $time) == 06){echo "selected";} ?>>June</option>
	                    <option value="07" <?php if(date('m', $time) == 07){echo "selected";} ?>>July</option>
	                    <option value="08" <?php if(date('m', $time) == 08){echo "selected";} ?>>August</option>
	                    <option value="09" <?php if(date('m', $time) == 09){echo "selected";} ?>>September</option>
	                    <option value="10" <?php if(date('m', $time) == 10){echo "selected";} ?>>October</option>
	                    <option value="11" <?php if(date('m', $time) == 11){echo "selected";} ?>>November</option>
	                    <option value="12" <?php if(isset($month) && $month==12){ echo "selected"; }else if(date('m', $time) == 12){echo "selected";} ?>>December</option>
	                </select>
					<input type="number" name="year" placeholder="Year" min="1940" max="2018" value="<?php if(isset($year)){ echo $year; }else {echo date('Y', $time);} ?>">
				</div>
					
				

				<div class="regInput" >
					<span style="margin-left: 0;">Male:</span> 
					<input type="radio" name="gender" value="0" 
					<?php if(isset($gender) && $gender==0){ echo "checked"; }elseif($user_details->gender == 0){echo "checked";} ?>>
					<span>Female:</span> 
					<input type="radio" name="gender" value="1" 
					<?php if(isset($gender) && $gender==1){ echo "checked"; }elseif($user_details->gender == 1){ echo "checked";} ?>>
					<span>Other:</span> 
					<input type="radio" name="gender" value="2" 
					<?php if(isset($gender) && $gender==2){ echo "checked"; }elseif($user_details->gender == 2){ echo "checked";} ?>>
				</div>
				<?php } ?>

				<input type="submit" name="submitGen" value="Update">
			</form>


			<a href="#" class="settingsPageLinks" id="newEmailLink">Edit Email</a>
			<form action="" method="post" class="settingsForms" id="newEmailForm">
				<?php if(!empty($errors2)){ ?>

				 	<div class="warning">
				 		<ul>
					 	<?php 
					 	foreach ($errors2 as $key => $value) {
					 		echo "<li>".$errors2[$key]."</li>";
					 	}
					 		 ?>
					 	</ul>
				 	</div>

				 <?php }else if(isset($success2)){ ?>

				 	<div class="success">
					 	<?php echo $success2; ?>
				 	</div>

				 <?php } ?>
				<div class="regInput" >
					<label>Current Email: </label><span class="email"><?php echo $user_details->email;?></span>
				</div>
				<div class="regInput" >
					<label>New Email: </label><input type="email" name="newEmail" placeholder="New Email" value="<?php if(isset($newEmail)){echo $newEmail;}?>">
				</div>
				<div class="regInput" >
					<label>Password: </label><input type="password" name="emailPassword" placeholder="Password">
				</div>
				
				<input type="submit" name="submitEmail" value="Update">
			</form>


			<a href="#" class="settingsPageLinks" id="newPassLink">Edit Password</a>
			<form action="" method="post" class="settingsForms" id="newPassForm">
				<?php if(!empty($errors3)){ ?>

				 	<div class="warning">
				 		<ul>
					 	<?php 
					 	foreach ($errors3 as $key => $value) {
					 		echo "<li>".$errors3[$key]."</li>";
					 	}
					 		 ?>
					 	</ul>
				 	</div>

				 <?php }else if(isset($success3)){ ?>

				 	<div class="success">
					 	<?php echo $success3; ?>
				 	</div>

				 <?php } ?>
				
				<div class="regInput" >
					<label>New Password: </label><input type="password" name="newPassword" placeholder="New Password">
				</div>
				<div class="regInput" >
					<label>Re-enter New Password: </label><input type="password" name="password2" placeholder="Re-enter Password" >
				</div>
				<div class="regInput" >
					<label>Current Password: </label><input type="password" name="oldPassword" placeholder="Current Password">
				</div>
				
				<input type="submit" name="submitPassword" value="Update">
			</form>


		</div>
	</div>
	</div>

</body>
</html>

<?php } ?>