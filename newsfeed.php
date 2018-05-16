<?php

require 'config/core.php';

if(!loggedIn()){
	header('Location: index.php');
}elseif(userBan()){
	header('Location: logout.php');
}else{ ?>

<!DOCTYPE html>
<html>
<head>
	<title>Newsfeed</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="images/logo.jpg"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/newsfeed.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/header.css">
	<link rel="stylesheet" type="text/css" href="stylesheets/templates/vacancy.css">
	<script type="text/javascript" src="http/js/ajax.js"></script>
	<script type="text/javascript" src="http/js/header.js"></script>
	<script type="text/javascript" src="http/js/vacancy.js"></script>	
	<script type="text/javascript" src="javascript/vacancy.js"></script>	
</head>
<body>

	<div class="container">
		<?php include "templates/header.php";?>

		<div class="main">
		<div class="mainScroll">

			<?php
			
			$sql = "SELECT skill_id FROM user_skills WHERE user_id={$_SESSION['id']}";
			

			if ($results=$db->query($sql)) {
				while ($row = $results->fetch_array(MYSQLI_BOTH)) {
					$skills_id[] = $row;
				}
				mysqli_free_result($results);
			}
						
			$noDuplicate = array();

			if (empty($skills_id)) {
				echo "Please choose a skill that you have in order to see vacancies";
			}else{
				$empty=false;
				foreach ($skills_id as $value) {
					
					$sql = "SELECT vac_id FROM vac_skills WHERE skill_id={$value[0]}";
					//$results1=$db->query($sql)->fetch_all();
					if ($results=$db->query($sql)) {
						while ($row = $results->fetch_array(MYSQLI_BOTH)) {
							$results1[] = $row;
						}
						mysqli_free_result($results);
					}
					
					if (empty($results1)) {
						$empty=true;
						break;
					}else{
						foreach ($results1 as $value1) {
							if (in_array($value1[0], $noDuplicate)==false) {
								$sql = "SELECT * FROM vacancy WHERE id={$value1[0]}";
								$results2=$db->query($sql)->fetch_object();
								$sql = "SELECT first_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id=user_details.id WHERE user_details.id={$results2->user_id}";
								$results3=$db->query($sql)->fetch_object();
								$sql = "SELECT title FROM skills JOIN vac_skills ON skills.id=vac_skills.skill_id WHERE vac_skills.vac_id={$value1[0]}";
								
								if ($results=$db->query($sql)) {
									while ($row = $results->fetch_array(MYSQLI_BOTH)) {
										$skills[] = $row;
									}
									mysqli_free_result($results);
								}
								include "templates/vacancy.php";
								$skills=[];
								array_push($noDuplicate, $value1[0]);
							}
						}
					}
				}

				if ($empty==true) {
					echo "There are currently no vacancies that match you skills.";
				}

			}
			
			?>

		</div>
		</div>
	</div>

</body>
</html>

<?php } ?>