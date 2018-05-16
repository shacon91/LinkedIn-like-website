
<div id="about">
	<div class="sec">
		<h2>About <?php echo $user_details->first_name;?>:</h2>
		<div class="aboutInfo"><?php echo $profile_details->about ;?></div>
	</div>
	<?php if($user_details->accType == 1){?>
		<div class="sec">
			<h2>Skills needed for a role within <?php echo $user_details->first_name;?>:</h2>
			<?php 
			if (empty($prof_skills)) {
				echo "<div class='aboutInfo'> No skills entered </div>";
			}else{
				foreach ($prof_skills as $value) {
					echo '<span class="skill">'.$value[0].'</span>';
				}
			}
			?>
		</div>

		<div class="sec">
			<h2>A general description of the type of employee <?php echo $user_details->first_name;?> look for:</h2>
			<div class="aboutInfo">
			<?php 
			if(!isset($company_details)){
				echo "No description entered";
			}else{echo $company_details->emp_desc; 
			} ?>
			</div>
		</div>
	
	<?php }else{?>

		<div class="sec">
			<h2><?php echo $user_details->first_name."'s";?> skills:</h2>
			<?php 
			if (empty($prof_skills)) {
				echo "<div class='aboutInfo'> No Skills entered </div>";
			}else{
				foreach ($prof_skills as $value) {
					echo '<span class="skill">'.$value[0].'</span>';
				}
			}
			?>
		</div>

		<div class="sec">
			<h2>Qualifications:</h2>
			<div class="aboutInfo">
				<?php
				if($qualifications->set == false){
					echo "No Qualifications entered";
				}else{ ?>
					<div><span>Qualification:</span> <span><?php echo $qualifications->title;?></span></div>
					<div><span>Level of qualification:</span> <span><?php echo $qualifications->level;?></span></div>
					<div><span>Obtained qualification:</span> <span><?php echo $obtDayOrg."/".$obtMonthOrg."/".$obtYearOrg;?></span></div>
					<div><span>Description of qualification:</span> </div>
					<div class="aboutInfoDesc"><?php echo $qualifications->description;?></div>
				<?php } ?>
			</div>
		</div>
		<div class="sec">
			<h2>Employment History:</h2>
			<div class="aboutInfo">
				<?php
				if($employment->set == false){
					echo "No Employment entered";
				}else{ ?>
					<div><span>Company:</span> <span><?php echo $employment->company;?></span></div>
					<div><span>Role:</span>  <span><?php echo $employment->title;?></span></div>
					<div><span>Worked there from:</span>  <span><?php echo $fromDayOrg."/".$fromMonthOrg."/".$fromYearOrg?></span> <span> to</span> <span><?php echo $toDayOrg."/".$toMonthOrg."/".$toYearOrg;?></span></div>
					<div><span>Description of Role:</span> </div>
					<div class="aboutInfoDesc"><?php echo $employment->description;?></div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>