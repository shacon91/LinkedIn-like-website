<?php  

require "config/core.php";

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{

	if (isset($_POST['submit'])) {



		$orgDesc = secure($_POST['orgDesc']);
		$emp = secure($_POST['employment']);
		$errors = [];

		if (!empty($_FILES['profPic']['name'])) {

			if ($_FILES['profPic']['error']!==0){
				$errors[0]='There is an error with this file.';
				
			}else{
				$allowed_type=["image/jpeg","image/jpg","image/png"];
				$allowed_ext=["jpeg","jpg","png"];
				$allowed_size=4194304;

				$name=$_FILES['profPic']['name'];
				$tmp_name=$_FILES['profPic']['tmp_name'];
				$size=$_FILES['profPic']['size'];
				$type=$_FILES['profPic']['type'];
				$error=$_FILES['profPic']['error'];

				$ext=strtolower(end(explode('.', $name)));	
				$filename=md5_file($tmp_name).time().'.'.$ext;
				$location='images/'.$filename;

				if (!in_array($type, $allowed_type) || !in_array($ext, $allowed_ext) || $size>$allowed_size) {
					$errors[0]='Image must be less than 4MB & be a JPG, JPEG or PNG Image.';
				}
			}	
		}else{
			$filename="default_pic.png";
		}






		
		if(isset($_POST['skill'])){
			$skill = $_POST['skill'] ;
			if (sizeof($skill)===0) {
				$errors[1]='Please pick at least one Skill ';
			}
		}else{
			$errors[1]='Please pick at least one Skill ';
		}

		if (strlen($orgDesc)<1) {
			$errors[2]='Please enter some details of your Organisation';
		}

		if (strlen($emp)<1) {
			$errors[3]='Please enter some details of the Employee you look for';
		}

				
		
		
		if(empty($errors)){

			if (!empty($_FILES['profPic']['name']) && move_uploaded_file($tmp_name, $location)==false) {
					$errors[0]='Image cannot be uploaded at this time.';
			}else{
				$id = $_SESSION['id'];

				foreach( $skill as $key => $value) {
					$db->query("INSERT INTO user_skills (user_id,skill_id)  VALUES ( {$id},{$value})");
		    	}

		    	$sql="INSERT INTO company_details (user_id,emp_desc) VALUES ({$id},'{$emp}')";
		    	$db->query($sql);
		    	
				
				$sql="UPDATE profile_details SET profile_pic='{$filename}',about='{$orgDesc}' WHERE user_id={$id}";
			

				if ($db->query($sql)) {
					if ($db->affected_rows==0) {
						$errors[0]='Unfortunately we cannot add your profile details.';
					}else{
						header('Location: newsfeed.php');
					}
				}else{
					$errors[0]='Unfortunately we cannot add your profile details.';
			}
			}
		}
		

	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Profile Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/profileSetup.css">
</head>
<body>

	<form action="" method="POST" enctype="multipart/form-data" class="container">
		<header>MotorPros</header>
		<div class="sec">
			<h2>Upload a Company Logo or Image</h2>

			<?php if(isset($errors[0])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[0]; ?>
			 	</div>

			 <?php } ?>
			<!--<label for="file">Choose Photo</label>-->
			<input type="file" name="profPic" >
		</div>
		<div class="sec">
			<h2>Please identify the skills you look for</h2>
			<?php if(isset($errors[1])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[1]; ?>
			 	</div>

			 <?php } ?>
			<div class="skillSec">
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
				<span>
					<label><?php echo $value[0];?>:</label>
					<input type="checkbox" name="skill[]" value="<?php echo $key+1;?>">
				</span>
				<?php } ?>
			</div>
		</div>
		<div class="sec">
			<h2>Please describe your Organisation</h2>
			<?php if(isset($errors[2])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[2]; ?>
			 	</div>

			 <?php } ?>
			<textarea name="orgDesc" placeholder="Describe type of Organisation"><?php
				 if(isset($orgDesc)){echo $orgDesc;}
			?></textarea>
		</div>
		<div class="sec">
			<h2>Please outline what sort of employees you are looking for</h2>
			<?php if(isset($errors[3])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[3]; ?>
			 	</div>

			 <?php } ?>
			<textarea name="employment" placeholder="Describe type of Employee"><?php
			 	if(isset($emp)){echo $emp;}
			 ?></textarea>
		</div>

			<input type="submit" name="submit" value="Submit">
	</form>

</body>
</html>
<?php } ?>