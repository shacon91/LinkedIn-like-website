<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ 
	$vac_id = $_POST['vac_id'];
	$sql = "SELECT * FROM vacancy WHERE id={$vac_id}";
	$results=$db->query($sql);
	$vac_details=$results->fetch_object(); //profile owners user details


	$time = strtotime($vac_details->deadline);
	$dayOrg = date('d', $time);
	$monthOrg = date('m', $time);
	$yearOrg = date('Y', $time);

	if(isset($_POST['vacancy'])){
		$vac_id = $_POST['vac_id'];
		$title = nameFix(secure($_POST['title']));
		$exp = $_POST['exp'];
		$about_role = secure($_POST['about_role']);
		$about_you = secure($_POST['about_you']);
		$day = $_POST['day'];
		$month = $_POST['month'];
		$year = secure($_POST['year']);
		$deadline = $year.'-'.$month.'-'.$day;
		$errors = [];

		if (strlen($title)<1) {
			$errors[0]='Please enter a title of the vacancy';
		}elseif (strlen($title)>25) {
			$errors[0]='The title cannot be longer then 25 characters';
		}

		if ($day === 0 || $month === 0) {
			$errors[3]='Please enter a correct Date';
		}elseif ($year<1900 || $year>2018) {
			$errors[3]='Please enter a correct Date';
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

		if (strlen($about_you)<1) {
			$errors[2]='Please enter some details about the Role';
		}

		

		if(empty($errors)){
			

			

			$db->query("DELETE FROM vac_skills WHERE vac_id={$vac_id}");
            foreach( $skill as $key => $value) {
                $db->query("INSERT INTO vac_skills (vac_id,skill_id)  VALUES ( {$vac_id},{$value})");
            }

			$sql="UPDATE vacancy SET title='{$title}',req_exp='{$exp}',about_role='{$about_role}',about_emp='{$about_you}',deadline='{$deadline}',edited='NOW()' WHERE id={$vac_id}";

			if ($db->query($sql)) {

				if ($db->affected_rows==0) {	
					$errors[0]='Unfortunately we cannot update vacancy at this time. Please try again later';
				}else{
					$success="Successfully edited vacancy";
		    	}
				
			}
			
		}
		
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Vacancy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/vacancy.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">
			<form action="" method="POST" class="vacancy">
				
				<h1>Edit Vacancy</h1>
				
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
					<input type="text" name="title" placeholder="Title" value="<?php if(isset($title)){echo $title;}else{ echo $vac_details->title;}?>">
				</div>
				<div class="exp">
					<h2>Required Expierence :</h2>
					<select name="exp">
						<option value="0" <?php if(isset($exp) && $exp==0){ echo "selected"; }else if($vac_details->req_exp == 0){echo "selected";} ?>>None</option>
						<option value="1" <?php if(isset($exp) && $exp==1){ echo "selected"; }else if($vac_details->req_exp == 01){echo "selected";} ?>>1 year</option>
						<option value="2" <?php if(isset($exp) && $exp==2){ echo "selected"; }else if($vac_details->req_exp == 02){echo "selected";} ?>>2 years</option>
						<option value="3" <?php if(isset($exp) && $exp==3){ echo "selected"; }else if($vac_details->req_exp == 03){echo "selected";} ?>>3 years</option>
						<option value="4" <?php if(isset($exp) && $exp==4){ echo "selected"; }else if($vac_details->req_exp == 04){echo "selected";} ?>>4 years</option>
						<option value="5" <?php if(isset($exp) && $exp==5){ echo "selected"; }else if($vac_details->req_exp == 05){echo "selected";} ?>>5+ years</option>
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
	                    <option value="01" <?php if(isset($day) && $day==01){ echo "selected"; }else if($dayOrg == 01){echo "selected";} ?>>1</option>
	                    <option value="02" <?php if(isset($day) && $day==02){ echo "selected"; }else if($dayOrg == 02){echo "selected";} ?>>2</option>
	                    <option value="03" <?php if(isset($day) && $day==03){ echo "selected"; }else if($dayOrg == 03){echo "selected";} ?>>3</option>
	                    <option value="04" <?php if(isset($day) && $day==04){ echo "selected"; }else if($dayOrg == 04){echo "selected";} ?>>4</option>
	                    <option value="05" <?php if(isset($day) && $day==05){ echo "selected"; }else if($dayOrg == 05){echo "selected";} ?>>5</option>
	                    <option value="06" <?php if(isset($day) && $day==06){ echo "selected"; }else if($dayOrg == 06){echo "selected";} ?>>6</option>
	                    <option value="07" <?php if(isset($day) && $day==07){ echo "selected"; }else if($dayOrg == 07){echo "selected";} ?>>7</option>
	                    <option value="08" <?php if(isset($day) && $day==8){ echo "selected"; }else if($dayOrg == 8){echo "selected";} ?>>8</option>
	                    <option value="09" <?php if(isset($day) && $day==9){ echo "selected"; }else if($dayOrg == 9){echo "selected";} ?>>9</option>
	                    <option value="10" <?php if(isset($day) && $day==10){ echo "selected"; }else if($dayOrg == 10){echo "selected";} ?>>10</option>
	                    <option value="11" <?php if(isset($day) && $day==11){ echo "selected"; }else if($dayOrg == 11){echo "selected";} ?>>11</option>
	                    <option value="12" <?php if(isset($day) && $day==12){ echo "selected"; }else if($dayOrg == 12){echo "selected";} ?>>12</option>
	                    <option value="13" <?php if(isset($day) && $day==13){ echo "selected"; }else if($dayOrg == 13){echo "selected";} ?>>13</option>
	                    <option value="14" <?php if(isset($day) && $day==14){ echo "selected"; }else if($dayOrg == 14){echo "selected";} ?>>14</option>
	                    <option value="15" <?php if(isset($day) && $day==15){ echo "selected"; }else if($dayOrg == 15){echo "selected";} ?>>15</option>
	                    <option value="16" <?php if(isset($day) && $day==16){ echo "selected"; }else if($dayOrg == 16){echo "selected";} ?>>16</option>
	                    <option value="17" <?php if(isset($day) && $day==17){ echo "selected"; }else if($dayOrg == 17){echo "selected";} ?>>17</option>
	                    <option value="18" <?php if(isset($day) && $day==18){ echo "selected"; }else if($dayOrg == 18){echo "selected";} ?>>18</option>
	                    <option value="19" <?php if(isset($day) && $day==19){ echo "selected"; }else if($dayOrg == 19){echo "selected";} ?>>19</option>
	                    <option value="20" <?php if(isset($day) && $day==20){ echo "selected"; }else if($dayOrg == 20){echo "selected";} ?>>20</option>
	                    <option value="21" <?php if(isset($day) && $day==21){ echo "selected"; }else if($dayOrg == 21){echo "selected";} ?>>21</option>
	                    <option value="22" <?php if(isset($day) && $day==22){ echo "selected"; }else if($dayOrg == 22){echo "selected";} ?>>22</option>
	                    <option value="23" <?php if(isset($day) && $day==23){ echo "selected"; }else if($dayOrg == 23){echo "selected";} ?>>23</option>
	                    <option value="24" <?php if(isset($day) && $day==24){ echo "selected"; }else if($dayOrg == 24){echo "selected";} ?>>24</option>
	                    <option value="25" <?php if(isset($day) && $day==25){ echo "selected"; }else if($dayOrg == 25){echo "selected";} ?>>25</option>
	                    <option value="26" <?php if(isset($day) && $day==26){ echo "selected"; }else if($dayOrg == 26){echo "selected";} ?>>26</option>
	                    <option value="27" <?php if(isset($day) && $day==27){ echo "selected"; }else if($dayOrg == 27){echo "selected";} ?>>27</option>
	                    <option value="28" <?php if(isset($day) && $day==28){ echo "selected"; }else if($dayOrg == 28){echo "selected";} ?>>28</option>
	                    <option value="29" <?php if(isset($day) && $day==29){ echo "selected"; }else if($dayOrg == 29){echo "selected";} ?>>29</option>
	                    <option value="30" <?php if(isset($day) && $day==30){ echo "selected"; }else if($dayOrg == 30){echo "selected";} ?>>30</option>
	                    <option value="31" <?php if(isset($day) && $day==31){ echo "selected"; }else if($dayOrg == 31){echo "selected";} ?>>31</option>
	           		</select>
	                <select  name="month" >
	                    <option value="0">--</option>
	                    <option value="01" <?php if(isset($month) && $month==01){ echo "selected"; }else if($monthOrg == 01){echo "selected";} ?>>January</option>
	                    <option value="02" <?php if(isset($month) && $month==02){ echo "selected"; }else if($monthOrg == 02){echo "selected";} ?>>February</option>
	                    <option value="03" <?php if(isset($month) && $month==03){ echo "selected"; }else if($monthOrg == 03){echo "selected";} ?>>March</option>
	                    <option value="04" <?php if(isset($month) && $month==04){ echo "selected"; }else if($monthOrg == 04){echo "selected";} ?>>April</option>
	                    <option value="05" <?php if(isset($month) && $month==05){ echo "selected"; }else if($monthOrg == 05){echo "selected";}?>>May</option>
	                    <option value="06" <?php if(isset($month) && $month==06){ echo "selected"; }else if($monthOrg == 06){echo "selected";} ?>>June</option>
	                    <option value="07" <?php if(isset($month) && $month==07){ echo "selected"; }else if($monthOrg == 07){echo "selected";} ?>>July</option>
	                    <option value="08" <?php if(isset($month) && $month==8){ echo "selected"; }else if($monthOrg == 8){echo "selected";} ?>>August</option>
	                    <option value="09" <?php if(isset($month) && $month==9){ echo "selected"; }else if($monthOrg == 9){echo "selected";}?>>September</option>
	                    <option value="10" <?php if(isset($month) && $month==10){ echo "selected"; }else if($monthOrg == 10){echo "selected";} ?>>October</option>
	                    <option value="11" <?php if(isset($month) && $month==11){ echo "selected"; }else if($monthOrg == 11){echo "selected";} ?>>November</option>
	                    <option value="12" <?php if(isset($month) && $month==12){ echo "selected"; }else if($monthOrg == 12){echo "selected";} ?>>December</option>
	                </select>
					<input type="number" name="year" placeholder="Year" value="<?php if(isset($year)){echo $year;}else{ echo $yearOrg;}?>">
				</div>
				

				<h2>Required Skills</h2>

				<?php if(isset($errors[1])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[1]; ?>
				 	</div>

				 <?php } ?>
				<div class="skillSec">
						<?php 
							//6 racing/drivin/pit/engineering
							$sql = "SELECT title FROM skills JOIN vac_skills ON skills.id=vac_skills.skill_id WHERE vac_skills.vac_id={$vac_id}";
							
							if ($results=$db->query($sql)) {
								while ($row = $results->fetch_array(MYSQLI_BOTH)) {
									$vacSkills1[] = $row;
								}
								mysqli_free_result($results);
							}
							
							$vacSkills2 = array();
							foreach ($vacSkills1 as $value) {
	                            $vacSkills2[] = $value[0];
	                        }	

							$sql = "SELECT title FROM skills";  

		                    if ($results=$db->query($sql)) {
		                        while ($row = $results->fetch_array(MYSQLI_BOTH)) {
		                                    $skillSet[] = $row;
		                                }
		                        mysqli_free_result($results);
		                    }

		    
		                    foreach ($skillSet as $key => $value) {
		                        $checked=false;
		                        if (in_array($value[0], $vacSkills2)==true) {
		                            $checked=true;
		                    }
						?>
						<span>
							<label><?php echo $value[0];?>:</label>
							<input type="checkbox" name="skill[]" value="<?php echo $key+1;?>" <?php if($checked){ echo "checked"; }?>>
						</span>
						<?php } ?>
				</div>

				<h2>About the Role</h2>
				<?php if(isset($errors[2])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[2]; ?>
				 	</div>

				 <?php } ?>
				<textarea name="about_role" placeholder="About the Role" required><?php 
					echo $vac_details->about_role;
				?></textarea>
				
				<h2>About You</h2>
				<?php if(isset($errors[3])){ ?>

				 	<div class="warning">
				 		<?php echo $errors[3]; ?>
				 	</div>

				 <?php } ?>
				<textarea name="about_you" placeholder="About You" required><?php
				 	echo $vac_details->about_emp;
				?></textarea>
					<input type="hidden" name="vac_id" value="<?php echo $vac_id;?>">
					<input type="submit" name="vacancy" value="Edit">
			</form>
		</div>
		</div>
	</div>

</body>
</html>

<?php } ?>