
<div class="vacBox" id="<?php echo "vac".$results2->id;?>" >
	<div class="headVac">
		<img src="images/<?php echo $results3->profile_pic;?>">
		<a href="<?php echo $results3->url;?>" class="titleLink"><?php echo $results3->first_name;?></a>
		<?php
		if (admin() || $results2->user_id == $_SESSION['id']) {
		?>
		<span class="edit" id="<?php echo $results2->id;?>">
			<!--img-->
			<span class="editBox">
				<form action="edit-vacancy.php" method="POST">
					<input type="hidden" name="vac_id" value="<?php echo $results2->id;?>">
					<button>Edit</button>
				</form>
				<form action="./http/php/vacancy.php" method="POST">
					<input type="hidden" name="vac_id" value="<?php echo $results2->id;?>">
					<button>Delete</button>
				</form>	
			</span>
		</span>
		<?php } ?>
	</div>
	<div class="titleVac"><h2><?php echo $results2->title;?></h2><span>Updated: <?php 
	$new_datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $results2->created );
	echo $new_datetime->format('d/m/y');
	?></span></div>
	<div class="info">
		<span class="infoTitle">About the Role:</span>
		<?php echo $results2->about_role;?>
	</div>
	<a href="#" class="readMoreLink">Read More</a>
	<div class="readMore">
		<span class="info">
			<span class="infoTitle">About You:</span>
			<?php echo $results2->about_emp;?>
		</span>
		<span class="info">
			<span class="infoTitle">You should have the following skills:</span>
			<?php 
				foreach ($skills as $value) {
					echo $value[0].', ';
				}
			?>
		</span>
		<span class="info">
			<span class="infoTitle">Experience Required:</span>
			<?php if ($results2->req_exp == 0) {
				echo "None";
			}else{
				echo "You should have at least ".$results2->req_exp." years experience.";
			}?>
		</span>
		<span class="info">
			<span class="infoTitle">Deadline:</span>
			 <?php echo $results2->deadline;?>
		</span>

		<?php 
		if ($results2->user_id!=$_SESSION['id']) {
			$sql = "SELECT id FROM applications WHERE vac_id={$results2->id} AND user_id={$_SESSION['id']} ";
			$results=$db->query($sql);
			if ($results->num_rows===0) {
				if (!organisation()) {
				?>
				<a href="#" id="<?php echo $results2->id."z".$results2->user_id."z".$_SESSION['id']; ?>" class="apply">Apply</a>
				<?php
				}
			}else{
				?>
				<div class="applied">You have applied for this job</div>
				<?php
			}
		}
		?>
	</div>
</div>
		
		
		
		

	



			
				
				