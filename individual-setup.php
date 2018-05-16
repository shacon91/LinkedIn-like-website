<?php  

require "config/core.php";

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{

	if (isset($_POST['submit'])) {


		$aboutDesc = secure($_POST['aboutDesc']);
		$qualTitle = nameFix(secure($_POST['qualTitle']));
		$qualLevel = nameFix(secure($_POST['qualLevel']));
		$qualDay = $_POST['qualDay'];
		$qualMonth = $_POST['qualMonth'];
		$qualYear = secure($_POST['qualYear']);
		$qualDate = $qualYear.'-'.$qualMonth.'-'.$qualDay;
		$qualDesc = secure($_POST['qualDesc']);
		$empCompany = nameFix(secure($_POST['empCompany']));
		$empTitle = nameFix(secure($_POST['empTitle']));
		$empDesc = secure($_POST['empDesc']);
		$fromEmpDay = $_POST['fromEmpDay'];
		$fromEmpMonth = $_POST['fromEmpMonth'];
		$fromEmpYear = secure($_POST['fromEmpYear']);
		$fromDate = $fromEmpYear.'-'.$fromEmpMonth.'-'.$fromEmpDay;
		$toEmpDay = $_POST['toEmpDay'];
		$toEmpMonth = $_POST['toEmpMonth'];
		$toEmpYear = secure($_POST['toEmpYear']);
		$toDate = $toEmpYear.'-'.$toEmpMonth.'-'.$toEmpDay;
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

		if (strlen($aboutDesc)<1) {
			$errors[2]='Please enter some details about yourself';
		}

		if (strlen($qualTitle)<1) {
			$errors[3]='Please enter the title of your Qualification';
		}else if (strlen($qualLevel)<1) {
			$errors[3]='Please enter the Level of your Qualification';
		}else if ($qualDay === 0 || $qualMonth === 0) {
			$errors[3]='Please enter a correct end Date';
		}else if ( $qualYear<1900 || $qualYear>2018) {
			$errors[3]='Please enter the date you obtained your Qualification';
		}else if (strlen($qualDesc)<1) {
			$errors[3]='Please enter some details of your Qualification';
		}

		if (strlen($empCompany)<1) {
			$errors[4]='Please enter the Company';
		}else if (strlen($empTitle)<1) {
			$errors[4]='Please enter the title of your Role';
		}else if ($fromEmpDay === 0 || $fromEmpMonth === 0) {
			$errors[4]='Please enter a correct start Date';
		}else if ($fromEmpYear<1900 || $fromEmpYear>2018) {
			$errors[4]='Please enter a correct start Date';
		}else if ($toEmpDay === 0 || $toEmpMonth === 0) {
			$errors[4]='Please enter a correct end Date';
		}else if ( $toEmpYear<1900 || $toEmpYear>2018) {
			$errors[4]='Please enter a correct end Date';
		}else if ( $toDate<$fromDate) {
			$errors[4]='Invalid Dates';
		}else if (strlen($empDesc)<1) {
			$errors[4]='Please enter some details of your Role';
		}

				
		
		
		if(empty($errors)){

			if (!empty($_FILES['profPic']['name']) && move_uploaded_file($tmp_name, $location)==false) {
					$errors[0]='Image cannot be uploaded at this time.';
			}else{
				$id = $_SESSION['id'];

				foreach( $skill as $key => $value) {
					$db->query("INSERT INTO user_skills (user_id,skill_id)  VALUES ({$id},{$value})");
		    	}

		    	$sql="UPDATE profile_details SET profile_pic='{$filename}',about='{$aboutDesc}' WHERE user_id={$id}";
				$db->query($sql);

				$sql="INSERT INTO qualifications (user_id,title,description,level,obtained) VALUES ({$id},'{$qualTitle}','{$qualDesc}','{$qualLevel}','{$qualDate}')";
				$db->query($sql);

				$sql="INSERT INTO employment (user_id,company,from_date,to_date,title,description) VALUES ({$id},'{$empCompany}','{$fromDate}','{$toDate}','{$empTitle}','{$empDesc}')";

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
			<h2>Upload a Profile Picture</h2>

			<?php if(isset($errors[0])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[0]; ?>
			 	</div>

			 <?php } ?>
			<!--<label for="file">Choose Photo</label>-->
			<input type="file" name="profPic" >
		</div>
		<div class="sec">
			<h2>Please identify your skills</h2>
			<?php if(isset($errors[1])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[1]; ?>
			 	</div>

			 <?php } ?>
			<div class="skillSec">

				<?php 
					$sql = "SELECT title FROM skills";	

					if ($results=$db->query($sql)) {
						//$skillSet=$results->fetch_array();
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
			<h2>Please descibe yourself</h2>
			<?php if(isset($errors[2])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[2]; ?>
			 	</div>

			 <?php } ?>
			<textarea name="aboutDesc" placeholder="About you"><?php 
				if(isset($aboutDesc)){echo $aboutDesc;}
			?></textarea>
		</div>
		<div class="sec">
			<h2>Please outline your Qualifications</h2>
			<?php if(isset($errors[3])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[3]; ?>
			 	</div>

			 <?php } ?>
			 <div class="info"><label>Title of Qualification: </label><input type="text" name="qualTitle" placeholder="Title" value="<?php if(isset($qualTitle)){echo $qualTitle;}?>"></div>
			 <div class="info"><label>Level of Qualification:</label><input type="text" name="qualLevel" placeholder="Level" value="<?php if(isset($qualLevel)){echo $qualLevel;}?>"></div>
			<div class="info"><label>Year you obtained Qualification:</label> 
				<select  name="qualDay">
                    <option value="0">--</option>
	                    <option value="01" <?php if(isset($qualDay) && $qualDay==01){ echo "selected"; } ?>>1</option>
	                    <option value="02" <?php if(isset($qualDay) && $qualDay==02){ echo "selected"; } ?>>2</option>
	                    <option value="03" <?php if(isset($qualDay) && $qualDay==03){ echo "selected"; } ?>>3</option>
	                    <option value="04" <?php if(isset($qualDay) && $qualDay==04){ echo "selected"; } ?>>4</option>
	                    <option value="05" <?php if(isset($qualDay) && $qualDay==05){ echo "selected"; } ?>>5</option>
	                    <option value="06" <?php if(isset($qualDay) && $qualDay==06){ echo "selected"; } ?>>6</option>
	                    <option value="07" <?php if(isset($qualDay) && $qualDay==07){ echo "selected"; } ?>>7</option>
	                    <option value="08" <?php if(isset($qualDay) && $qualDay==8){ echo "selected"; } ?>>8</option>
	                    <option value="09" <?php if(isset($qualDay) && $qualDay==9){ echo "selected"; } ?>>9</option>
	                    <option value="10" <?php if(isset($qualDay) && $qualDay==10){ echo "selected"; } ?>>10</option>
	                    <option value="11" <?php if(isset($qualDay) && $qualDay==11){ echo "selected"; } ?>>11</option>
	                    <option value="12" <?php if(isset($qualDay) && $qualDay==12){ echo "selected"; } ?>>12</option>
	                    <option value="13" <?php if(isset($qualDay) && $qualDay==13){ echo "selected"; } ?>>13</option>
	                    <option value="14" <?php if(isset($qualDay) && $qualDay==14){ echo "selected"; } ?>>14</option>
	                    <option value="15" <?php if(isset($qualDay) && $qualDay==15){ echo "selected"; } ?>>15</option>
	                    <option value="16" <?php if(isset($qualDay) && $qualDay==16){ echo "selected"; } ?>>16</option>
	                    <option value="17" <?php if(isset($qualDay) && $qualDay==17){ echo "selected"; } ?>>17</option>
	                    <option value="18" <?php if(isset($qualDay) && $qualDay==18){ echo "selected"; } ?>>18</option>
	                    <option value="19" <?php if(isset($qualDay) && $qualDay==19){ echo "selected"; } ?>>19</option>
	                    <option value="20" <?php if(isset($qualDay) && $qualDay==20){ echo "selected"; } ?>>20</option>
	                    <option value="21" <?php if(isset($qualDay) && $qualDay==21){ echo "selected"; } ?>>21</option>
	                    <option value="22" <?php if(isset($qualDay) && $qualDay==22){ echo "selected"; } ?>>22</option>
	                    <option value="23" <?php if(isset($qualDay) && $qualDay==23){ echo "selected"; } ?>>23</option>
	                    <option value="24" <?php if(isset($qualDay) && $qualDay==24){ echo "selected"; } ?>>24</option>
	                    <option value="25" <?php if(isset($qualDay) && $qualDay==25){ echo "selected"; } ?>>25</option>
	                    <option value="26" <?php if(isset($qualDay) && $qualDay==26){ echo "selected"; } ?>>26</option>
	                    <option value="27" <?php if(isset($qualDay) && $qualDay==27){ echo "selected"; } ?>>27</option>
	                    <option value="28" <?php if(isset($qualDay) && $qualDay==28){ echo "selected"; } ?>>28</option>
	                    <option value="29" <?php if(isset($qualDay) && $qualDay==29){ echo "selected"; } ?>>29</option>
	                    <option value="30" <?php if(isset($qualDay) && $qualDay==30){ echo "selected"; } ?>>30</option>
	                    <option value="31" <?php if(isset($qualDay) && $qualDay==31){ echo "selected"; } ?>>31</option>
           		</select>
                <select  name="qualMonth" >
                     <option value="0">--</option>
                    <option value="01" <?php if(isset($qualMonth) && $qualMonth==01){ echo "selected"; } ?>>January</option>
                    <option value="02" <?php if(isset($qualMonth) && $qualMonth==02){ echo "selected"; } ?>>February</option>
                    <option value="03" <?php if(isset($qualMonth) && $qualMonth==03){ echo "selected"; } ?>>March</option>
                    <option value="04" <?php if(isset($qualMonth) && $qualMonth==04){ echo "selected"; } ?>>April</option>
                    <option value="05" <?php if(isset($qualMonth) && $qualMonth==05){ echo "selected"; } ?>>May</option>
                    <option value="06" <?php if(isset($qualMonth) && $qualMonth==06){ echo "selected"; } ?>>June</option>
                    <option value="07" <?php if(isset($qualMonth) && $qualMonth==07){ echo "selected"; } ?>>July</option>
                    <option value="08" <?php if(isset($qualMonth) && $qualMonth==8){ echo "selected"; } ?>>August</option>
                    <option value="09" <?php if(isset($qualMonth) && $qualMonth==9){ echo "selected"; } ?>>September</option>
                    <option value="10" <?php if(isset($qualMonth) && $qualMonth==10){ echo "selected"; } ?>>October</option>
                    <option value="11" <?php if(isset($qualMonth) && $qualMonth==11){ echo "selected"; } ?>>November</option>
                    <option value="12" <?php if(isset($qualMonth) && $qualMonth==12){ echo "selected"; } ?>>December</option>
                </select>
				<input type="number" name="qualYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($qualYear)){echo $qualYear;}?>">
			</div>
			<textarea name="qualDesc" placeholder="Description of Qualification"><?php
				 if(isset($qualDesc)){echo $qualDesc;}
			?></textarea>
		</div>
		<div class="sec">
			<h2>Please List your Employment History</h2>
			<?php if(isset($errors[4])){ ?>

			 	<div class="warning">
			 		<?php echo $errors[4]; ?>
			 	</div>

			 <?php } ?>
			 <div class="info"><label>Company: </label><input type="text" name="empCompany" placeholder="Company" value="<?php if(isset($empCompany)){echo $empCompany;}?>"></div>
			 <div class="info"><label>Title of Job: </label><input type="text" name="empTitle" placeholder="Title" value="<?php if(isset($empTitle)){echo $empTitle;}?>"></div>
			<div class="info"><label>Started the role in:</label> 
				<select  name="fromEmpDay">
						<option value="0">--</option>
	                    <option value="01" <?php if(isset($fromEmpDay) && $fromEmpDay==01){ echo "selected"; } ?>>1</option>
	                    <option value="02" <?php if(isset($fromEmpDay) && $fromEmpDay==02){ echo "selected"; } ?>>2</option>
	                    <option value="03" <?php if(isset($fromEmpDay) && $fromEmpDay==03){ echo "selected"; } ?>>3</option>
	                    <option value="04" <?php if(isset($fromEmpDay) && $fromEmpDay==04){ echo "selected"; } ?>>4</option>
	                    <option value="05" <?php if(isset($fromEmpDay) && $fromEmpDay==05){ echo "selected"; } ?>>5</option>
	                    <option value="06" <?php if(isset($fromEmpDay) && $fromEmpDay==06){ echo "selected"; } ?>>6</option>
	                    <option value="07" <?php if(isset($fromEmpDay) && $fromEmpDay==07){ echo "selected"; } ?>>7</option>
	                    <option value="08" <?php if(isset($fromEmpDay) && $fromEmpDay==8){ echo "selected"; } ?>>8</option>
	                    <option value="09" <?php if(isset($fromEmpDay) && $fromEmpDay==9){ echo "selected"; } ?>>9</option>
	                    <option value="10" <?php if(isset($fromEmpDay) && $fromEmpDay==10){ echo "selected"; } ?>>10</option>
	                    <option value="11" <?php if(isset($fromEmpDay) && $fromEmpDay==11){ echo "selected"; } ?>>11</option>
	                    <option value="12" <?php if(isset($fromEmpDay) && $fromEmpDay==12){ echo "selected"; } ?>>12</option>
	                    <option value="13" <?php if(isset($fromEmpDay) && $fromEmpDay==13){ echo "selected"; } ?>>13</option>
	                    <option value="14" <?php if(isset($fromEmpDay) && $fromEmpDay==14){ echo "selected"; } ?>>14</option>
	                    <option value="15" <?php if(isset($fromEmpDay) && $fromEmpDay==15){ echo "selected"; } ?>>15</option>
	                    <option value="16" <?php if(isset($fromEmpDay) && $fromEmpDay==16){ echo "selected"; } ?>>16</option>
	                    <option value="17" <?php if(isset($fromEmpDay) && $fromEmpDay==17){ echo "selected"; } ?>>17</option>
	                    <option value="18" <?php if(isset($fromEmpDay) && $fromEmpDay==18){ echo "selected"; } ?>>18</option>
	                    <option value="19" <?php if(isset($fromEmpDay) && $fromEmpDay==19){ echo "selected"; } ?>>19</option>
	                    <option value="20" <?php if(isset($fromEmpDay) && $fromEmpDay==20){ echo "selected"; } ?>>20</option>
	                    <option value="21" <?php if(isset($fromEmpDay) && $fromEmpDay==21){ echo "selected"; } ?>>21</option>
	                    <option value="22" <?php if(isset($fromEmpDay) && $fromEmpDay==22){ echo "selected"; } ?>>22</option>
	                    <option value="23" <?php if(isset($fromEmpDay) && $fromEmpDay==23){ echo "selected"; } ?>>23</option>
	                    <option value="24" <?php if(isset($fromEmpDay) && $fromEmpDay==24){ echo "selected"; } ?>>24</option>
	                    <option value="25" <?php if(isset($fromEmpDay) && $fromEmpDay==25){ echo "selected"; } ?>>25</option>
	                    <option value="26" <?php if(isset($fromEmpDay) && $fromEmpDay==26){ echo "selected"; } ?>>26</option>
	                    <option value="27" <?php if(isset($fromEmpDay) && $fromEmpDay==27){ echo "selected"; } ?>>27</option>
	                    <option value="28" <?php if(isset($fromEmpDay) && $fromEmpDay==28){ echo "selected"; } ?>>28</option>
	                    <option value="29" <?php if(isset($fromEmpDay) && $fromEmpDay==29){ echo "selected"; } ?>>29</option>
	                    <option value="30" <?php if(isset($fromEmpDay) && $fromEmpDay==30){ echo "selected"; } ?>>30</option>
	                    <option value="31" <?php if(isset($fromEmpDay) && $fromEmpDay==31){ echo "selected"; } ?>>31</option>
           		</select>
                <select  name="fromEmpMonth" >
                    <option value="0">--</option>
                    <option value="01" <?php if(isset($fromEmpMonth) && $fromEmpMonth==01){ echo "selected"; } ?>>January</option>
                    <option value="02" <?php if(isset($fromEmpMonth) && $fromEmpMonth==02){ echo "selected"; } ?>>February</option>
                    <option value="03" <?php if(isset($fromEmpMonth) && $fromEmpMonth==03){ echo "selected"; } ?>>March</option>
                    <option value="04" <?php if(isset($fromEmpMonth) && $fromEmpMonth==04){ echo "selected"; } ?>>April</option>
                    <option value="05" <?php if(isset($fromEmpMonth) && $fromEmpMonth==05){ echo "selected"; } ?>>May</option>
                    <option value="06" <?php if(isset($fromEmpMonth) && $fromEmpMonth==06){ echo "selected"; } ?>>June</option>
                    <option value="07" <?php if(isset($fromEmpMonth) && $fromEmpMonth==07){ echo "selected"; } ?>>July</option>
                    <option value="08" <?php if(isset($fromEmpMonth) && $fromEmpMonth==8){ echo "selected"; } ?>>August</option>
                    <option value="09" <?php if(isset($fromEmpMonth) && $fromEmpMonth==9){ echo "selected"; } ?>>September</option>
                    <option value="10" <?php if(isset($fromEmpMonth) && $fromEmpMonth==10){ echo "selected"; } ?>>October</option>
                    <option value="11" <?php if(isset($fromEmpMonth) && $fromEmpMonth==11){ echo "selected"; } ?>>November</option>
                    <option value="12" <?php if(isset($fromEmpMonth) && $fromEmpMonth==12){ echo "selected"; } ?>>December</option>
                </select>
				<input type="number" name="fromEmpYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($fromEmpYear)){echo $fromEmpYear;}?>"></div>
			<div class="info"><label>Finished the role in:</label> 
				<select  name="toEmpDay">
                    <option value="0">--</option>
                    <option value="01" <?php if(isset($toEmpDay) && $toEmpDay==01){ echo "selected"; } ?>>1</option>
                    <option value="02" <?php if(isset($toEmpDay) && $toEmpDay==02){ echo "selected"; } ?>>2</option>
                    <option value="03" <?php if(isset($toEmpDay) && $toEmpDay==03){ echo "selected"; } ?>>3</option>
                    <option value="04" <?php if(isset($toEmpDay) && $toEmpDay==04){ echo "selected"; } ?>>4</option>
                    <option value="05" <?php if(isset($toEmpDay) && $toEmpDay==05){ echo "selected"; } ?>>5</option>
                    <option value="06" <?php if(isset($toEmpDay) && $toEmpDay==06){ echo "selected"; } ?>>6</option>
                    <option value="07" <?php if(isset($toEmpDay) && $toEmpDay==07){ echo "selected"; } ?>>7</option>
                    <option value="08" <?php if(isset($toEmpDay) && $toEmpDay==8){ echo "selected"; } ?>>8</option>
                    <option value="09" <?php if(isset($toEmpDay) && $toEmpDay==9){ echo "selected"; } ?>>9</option>
                    <option value="10" <?php if(isset($toEmpDay) && $toEmpDay==10){ echo "selected"; } ?>>10</option>
                    <option value="11" <?php if(isset($toEmpDay) && $toEmpDay==11){ echo "selected"; } ?>>11</option>
                    <option value="12" <?php if(isset($toEmpDay) && $toEmpDay==12){ echo "selected"; } ?>>12</option>
                    <option value="13" <?php if(isset($toEmpDay) && $toEmpDay==13){ echo "selected"; } ?>>13</option>
                    <option value="14" <?php if(isset($toEmpDay) && $toEmpDay==14){ echo "selected"; } ?>>14</option>
                    <option value="15" <?php if(isset($toEmpDay) && $toEmpDay==15){ echo "selected"; } ?>>15</option>
                    <option value="16" <?php if(isset($toEmpDay) && $toEmpDay==16){ echo "selected"; } ?>>16</option>
                    <option value="17" <?php if(isset($toEmpDay) && $toEmpDay==17){ echo "selected"; } ?>>17</option>
                    <option value="18" <?php if(isset($toEmpDay) && $toEmpDay==18){ echo "selected"; } ?>>18</option>
                    <option value="19" <?php if(isset($toEmpDay) && $toEmpDay==19){ echo "selected"; } ?>>19</option>
                    <option value="20" <?php if(isset($toEmpDay) && $toEmpDay==20){ echo "selected"; } ?>>20</option>
                    <option value="21" <?php if(isset($toEmpDay) && $toEmpDay==21){ echo "selected"; } ?>>21</option>
                    <option value="22" <?php if(isset($toEmpDay) && $toEmpDay==22){ echo "selected"; } ?>>22</option>
                    <option value="23" <?php if(isset($toEmpDay) && $toEmpDay==23){ echo "selected"; } ?>>23</option>
                    <option value="24" <?php if(isset($toEmpDay) && $toEmpDay==24){ echo "selected"; } ?>>24</option>
                    <option value="25" <?php if(isset($toEmpDay) && $toEmpDay==25){ echo "selected"; } ?>>25</option>
                    <option value="26" <?php if(isset($toEmpDay) && $toEmpDay==26){ echo "selected"; } ?>>26</option>
                    <option value="27" <?php if(isset($toEmpDay) && $toEmpDay==27){ echo "selected"; } ?>>27</option>
                    <option value="28" <?php if(isset($toEmpDay) && $toEmpDay==28){ echo "selected"; } ?>>28</option>
                    <option value="29" <?php if(isset($toEmpDay) && $toEmpDay==29){ echo "selected"; } ?>>29</option>
                    <option value="30" <?php if(isset($toEmpDay) && $toEmpDay==30){ echo "selected"; } ?>>30</option>
                    <option value="31" <?php if(isset($toEmpDay) && $toEmpDay==31){ echo "selected"; } ?>>31</option>
           		</select>
                <select  name="toEmpMonth" >
                	<option value="0">--</option>
                    <option value="01" <?php if(isset($toEmpMonth) && $toEmpMonth==01){ echo "selected"; } ?>>January</option>
                    <option value="02" <?php if(isset($toEmpMonth) && $toEmpMonth==02){ echo "selected"; } ?>>February</option>
                    <option value="03" <?php if(isset($toEmpMonth) && $toEmpMonth==03){ echo "selected"; } ?>>March</option>
                    <option value="04" <?php if(isset($toEmpMonth) && $toEmpMonth==04){ echo "selected"; } ?>>April</option>
                    <option value="05" <?php if(isset($toEmpMonth) && $toEmpMonth==05){ echo "selected"; } ?>>May</option>
                    <option value="06" <?php if(isset($toEmpMonth) && $toEmpMonth==06){ echo "selected"; } ?>>June</option>
                    <option value="07" <?php if(isset($toEmpMonth) && $toEmpMonth==07){ echo "selected"; } ?>>July</option>
                    <option value="08" <?php if(isset($toEmpMonth) && $toEmpMonth==8){ echo "selected"; } ?>>August</option>
                    <option value="09" <?php if(isset($toEmpMonth) && $toEmpMonth==9){ echo "selected"; } ?>>September</option>
                    <option value="10" <?php if(isset($toEmpMonth) && $toEmpMonth==10){ echo "selected"; } ?>>October</option>
                    <option value="11" <?php if(isset($toEmpMonth) && $toEmpMonth==11){ echo "selected"; } ?>>November</option>
                    <option value="12" <?php if(isset($toEmpMonth) && $toEmpMonth==12){ echo "selected"; } ?>>December</option>
                </select>
				<input type="number" name="toEmpYear" placeholder="Year" min="1950" max="2018" value="<?php if(isset($toEmpYear)){echo $toEmpYear;}?>">
			</div>
			<textarea name="empDesc" placeholder="Description of Employment"><?php 
				if(isset($empDesc)){echo $empDesc;}
			?></textarea>
		</div>

			<input type="submit" name="submit" value="Submit">
	</form>

</body>
</html>
<?php } ?>