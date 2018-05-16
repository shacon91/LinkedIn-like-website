<?php

$sql = "SELECT user_details.url,user_details.first_name,profile_details.profile_pic FROM user_details JOIN profile_details ON profile_details.user_id = user_details.id WHERE user_details.id={$_SESSION['id']}";

if ($results=$db->query($sql)) {
	$my_details=$results->fetch_object();
	mysqli_free_result($results);
}




?>

<header>
	<img src="images/logo.jpg">
	<h1>MotorPros</h1>

	<div class="search">
		<input type="text" name="search" id="search" placeholder="Search">
		<span id="searchBox"></span>
	</div>

	<?php if(organisation()){?>
		<a href="add-vacancy.php" class="rightLinks">+</a>
	<?php }?>

	<a href="<?php echo $my_details->url;?>" class="rightLinks">
		<img src="images/<?php echo $my_details->profile_pic;?>" >
		<?php echo $my_details->first_name;?>
	</a>


	<a href="newsfeed.php" class="rightLinks">Newsfeed</a>
	
	<span  id="settings">
		&#9776
		<span>
			<a href="search.php" class="settingsLink">Detailed Search</a>
			<a href="settings.php" class="settingsLink">Settings</a>
			<?php
				if (admin()) {
			?>
				<a href="admin.php" class="settingsLink">Admin Panel</a>
			<?php } ?>

			<a href="logout.php" class="settingsLink">Sign Out</a>
		</span>
	</span>

	<!--Mobile-->

	<span  id="mobile" >
		&#9776
		<span>
			<?php if(organisation()){?>
				<a href="add-vacancy.php" class="settingsLink">+</a>
			<?php }?>

			<a href="<?php echo $my_details->url;?>" class="settingsLink">
				<img src="images/<?php echo $my_details->profile_pic;?>" >
				<?php echo $my_details->first_name;?>
			</a>
			<a href="newsfeed.php" class="settingsLink">Newsfeed</a>
			<a href="search.php" class="settingsLink">Detailed Search</a>
			<a href="settings.php" class="settingsLink">Settings</a>
			<?php
				if (admin()) {
			?>
				<a href="admin.php" class="settingsLink">Admin Panel</a>
			<?php } ?>
			<a href="logout.php" class="settingsLink">Sign Out</a>
		</span>
	</span>

	
	
</header>