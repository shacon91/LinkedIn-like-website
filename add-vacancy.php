<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}elseif(organisation()==false){
	header('Location: newsfeed.php');
}else{ 

	if(isset($_POST['vacancy'])){
		$title = nameFix(secure($_POST['title']));
		$exp = $_POST['exp'];
		$about_role = secure($_POST['about']);
		$about_emp = secure($_POST['aboutYou']);
		$day = $_POST['day'];
		$month = $_POST['month'];
		$year = secure($_POST['year']);
		$deadline = $year.'-'.$month.'-'.$day;
		$errors = [];

		if (strlen($title)<1) {
			$errors[0]='Please enter a title of the vacancy';
		}elseif (strlen($title)>25) {
			$errors[0]='The title cannot be longer then 25 characters';
		}else if (!preg_match("/^[a-zA-Z ]*$/",$title)) {
			  $errors[0] = "Only letters and white space allowed"; 
			}

		if(isset($_POST['skill'])){
			$skill = $_POST['skill'] ;
			if (sizeof($skill)===0) {
				$errors[1]='Please pick at least one Skill ';
			}
		}else{
			$errors[1]='Please pick at least one Skill ';
		}

		if (strlen($about_role)<1) {
			$errors[2]='Please enter some details about the Role';
		}

		if (strlen($about_emp)<1) {
			$errors[4]='Please enter some details about the Employee you wish to hire';
		}


		if ($day === 0 || $month === 0) {
			$errors[3]='Please enter a valid Date';
		}elseif ($year<2018 || $year>2100) {
			$errors[3]='Please enter a valid Date';
		}

		if(empty($errors)){
			$sql="INSERT INTO vacancy (user_id,title,req_exp,about_role,about_emp,deadline,created) 
			VALUES ({$_SESSION['id']},'{$title}','{$exp}','{$about_role}','{$about_emp}','{$deadline}',NOW())";

			if ($db->query($sql)) {

				if ($db->affected_rows==0) {	
					$errors[0]='Unfortunately we cannot register you at this time.';
				}else{
					$id=mysqli_insert_id($db);
					foreach( $skill as $key => $value) {
					$db->query("INSERT INTO vac_skills (vac_id,skill_id)  VALUES ( {$id},{$value})");
					$success="Successfully added vacancy";
		    	}
				}
			}
			
		}
		
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Vacancy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/vacancy.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
	
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">
			<form action="" method="POST" class="vacancy">

				<h1>Vacancy</h1>
				
				<?php if(isset($success)){ ?>

				 	<div class="success">
				 		<?php echo $success; ?>
				 	</div>

				 <?php } ?>
				 <?php if(isset($errors[0])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[0]; ?>
				 	</div>

				 <?php } ?>
				<div class="exp">
					<h2>Job Title:</h2>
					<input type="text" name="title" placeholder="Title" value="<?php if(isset($title)){echo $title;}?>">
				</div>
				<div class="exp">
					<h2>Required Expierence :</h2>
					<select name="exp">
						<option value="0" <?php if(isset($exp) && $exp==0){ echo "selected"; } ?>>None</option>
						<option value="1" <?php if(isset($exp) && $exp==1){ echo "selected"; } ?>>1 year</option>
						<option value="2" <?php if(isset($exp) && $exp==2){ echo "selected"; } ?>>2 years</option>
						<option value="3" <?php if(isset($exp) && $exp==3){ echo "selected"; } ?>>3 years</option>
						<option value="4" <?php if(isset($exp) && $exp==4){ echo "selected"; } ?>>4 years</option>
						<option value="5" <?php if(isset($exp) && $exp==5){ echo "selected"; } ?>>5+ years</option>
					</select>
				</div>

				<?php if(isset($errors[3])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[3]; ?>
				 	</div>

				 <?php } ?>
				
				<div class="exp">
					<h2>Application deadline:</h2>
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
		            <select  name="month" >
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
					<input type="number" name="year" placeholder="Year" min="2018" value="<?php if(isset($year)){echo $year;}?>">
				</div>
				

				<h2>Required Skills</h2>
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

				<h2>About the Role</h2>
				<?php if(isset($errors[2])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[2]; ?>
				 	</div>

				 <?php } ?>
				<textarea name="about" placeholder="About the Role" required><?php
				 	if(isset($about_role)){echo $about_role;}
				?></textarea>

				<h2>About You</h2>
				<?php if(isset($errors[4])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[4]; ?>
				 	</div>

				 <?php } ?>
				<textarea name="aboutYou" placeholder="About You" required><?php
					 if(isset($about_emp)){echo $about_emp;}
				?></textarea>

				

					<input type="submit" name="vacancy" value="Submit">
			</form>
		</div>
		</div>
	</div>

</body>
</html>

<?php } ?>