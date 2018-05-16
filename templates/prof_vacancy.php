<div id="vacancy" >
	<?php
		$sql = "SELECT * FROM vacancy WHERE user_id={$user_details->id}";

			if ($results1=$db->query($sql)) {
				//$skillSet=$results->fetch_array();
				while ($row = $results1->fetch_array(MYSQLI_BOTH)) {
					$vacancy[] = $row;
				}
				mysqli_free_result($results1);
			}
			
			
			
			
			
			if (empty($vacancy)) {
				echo "No Vacancies posted yet!";
			}else{
			foreach ($vacancy as $value) {
				$skills = [];
				$sql = "SELECT title FROM skills JOIN vac_skills ON skills.id=vac_skills.skill_id WHERE vac_skills.vac_id={$value[0]}";
				if ($results1=$db->query($sql)) {
					//$skillSet=$results->fetch_array();
					while ($row = $results1->fetch_array(MYSQLI_BOTH)) {
						$skills[] = $row;
					}
					mysqli_free_result($results1);
				}
				

			?>
			<div class="vacBox" id="<?php echo "vac".$value[0];?>">
				<div class="headVac">
					<img src="images/<?php echo $profile_details->profile_pic;?>">
					<a href="<?php echo $user_details->url;?>" class="titleLink"><?php echo $user_details->first_name; ?></a>
					<?php
					if (loggedIn()) {
						
						if (admin() || $value[1] == $_SESSION['id']) {
						?>
						<span class="edit" id="<?php echo $results2->id;?>">
							<!--img-->
							<span class="editBox">
								<form action="edit-vacancy.php" method="POST">
									<input type="hidden" name="vac_id" value="<?php echo $value[0];?>">
									<button>Edit</button>
								</form>
								<form action="./http/php/vacancy.php" method="POST">
									<input type="hidden" name="vac_id" value="<?php echo $value[0];?>">
									<button>Delete</button>
								</form>	
							</span>
						</span>
						<?php } } ?>
				</div>
				<div class="titleVac"><h2><?php echo $value[2];?></h2><span>Updated: <?php 
				$new_datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $value[8] );
				echo $new_datetime->format('d/m/y');
				?></span></div>
				<div class="info">
					<span class="infoTitle">About the Role:</span>
					<?php echo $value[4];?>
				</div>
				<a href="#" class="readMoreLink">Read More</a>
				<div class="readMore">
					<span class="info">
						<span class="infoTitle">About You:</span>
						<?php echo $value[5];?>
					</span>
					<span class="info">
						<span class="infoTitle">You should have the following skills:</span>
						<?php 
							foreach ($skills as $value1) {
								echo $value1[0].', ';
							}
						?>
					</span>
					<span class="info">
						<span class="infoTitle">Experience Required:</span>
						<?php if ($value[3] == 0) {
							echo "None";
						}else{
							echo "You should have at least ".$value[3]." years experience.";
						}?>
					</span>
					<span class="info">
						<span class="infoTitle">Deadline:</span>
						 <?php echo $value[6];?>
					</span>

					<?php 
					if (loggedIn()) {
						if ( $value[1]!=$_SESSION['id']) {
							$sql = "SELECT id FROM applications WHERE vac_id={$value[0]} AND user_id={$_SESSION['id']} ";
							$results=$db->query($sql);
							if ($results->num_rows===0) {
								if (!organisation()) {
									?>
									<a href="#" id="<?php echo $value[0]."z".$value[1]."z".$_SESSION['id']; ?>" class="apply">Apply</a>
									<?php
								}
							}else{
								?>
								<div class="applied">You have applied for this job</div>
								<?php
							}
						}
					}else{
						?>
						<a href="login.php" class="applied">Sign in to apply for this job</a>
						<?php
					}
					?>
				</div>
			</div>
			
			<?php }} ?>
</div>
