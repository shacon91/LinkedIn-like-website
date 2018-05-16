<?php

$sql = "SELECT * FROM notifications WHERE from_id={$_SESSION['id']} OR to_id={$_SESSION['id']} ORDER BY created DESC";

if ($results=$db->query($sql)) {
	//$not_details=$results->fetch_all(MYSQL_ASSOC);
	//mysqli_free_result($results);
	while ($row = $results->fetch_array(MYSQL_ASSOC)) {
		$not_details[] = $row;
	}
	mysqli_free_result($results);
}

?>
<div id="notif">

	<?php

	/*
		friend req = 0
		friend acc = 1
		vacany apply = 2
	*/
		if (empty($not_details)) {
			echo "You have no notifications yet";
		}else{

			foreach ($not_details as $value) {

				if($value['type']==0 && $value['to_id']==$_SESSION['id']){
					//ACCEPT/DECLINE
					$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id=user_details.id WHERE user_details.id={$value['from_id']}";
					$results=$db->query($sql);
					$friend_details=$results->fetch_object();
					mysqli_free_result($results);
					?>
					<div class="friendRequest">
						<img src="images/<?php echo $friend_details->profile_pic;?>">
						<span>
							<div><a href="<?php echo $friend_details->url;?>"><?php echo $friend_details->first_name." ".$friend_details->last_name;?></a></div>
							<div>Sent you a friend Request</div>
						</span>
						<form action="controllers/friendDecision.php" method="POST">
							<input type="hidden" name="to_id" value="<?php echo $value['to_id'];?>">
							<input type="hidden" name="from_id" value="<?php echo $value['from_id'];?>">
							<input type="submit" name="accept" value="Accept">
							<input type="submit" name="decline" value="Decline">
						</form>
					</div>
					<?php
				}else if ($value['type']==1 && $value['to_id']==$_SESSION['id']) {
					//accepted
					
					$sql = "SELECT first_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id=user_details.id WHERE user_details.id={$value['from_id']}";
					$results=$db->query($sql);
					$friend_details=$results->fetch_object();
					mysqli_free_result($results);
					?>
					<div class="friendRequest">
						<img src="images/<?php echo $friend_details->profile_pic;?>">
						<span>
							<div><a href="<?php echo $friend_details->url;?>"><?php echo $friend_details->first_name;?></a> accepted your friend request</div>
						</span>
					</div>
					<?php
				}else if ($value['type']==2 && $value['from_id']==$_SESSION['id']) {
					//you applied
					$sql = "SELECT first_name,url FROM user_details WHERE id={$value['to_id']}";
					$results=$db->query($sql);
					$friend_details=$results->fetch_object();
					mysqli_free_result($results);
					$sql = "SELECT title FROM vacancy WHERE id={$value['vac_id']}";
					$results=$db->query($sql);
					$vac_details=$results->fetch_object();
					mysqli_free_result($results);
					?>
					<div class="friendRequest">
						You applied for <?php echo $vac_details->title;?> vacancy with the company <a href="<?php echo $friend_details->url;?>" class="appLink"><?php echo $friend_details->first_name;?></a>
					</div>
					<?php
				}else if ($value['type']==2 && $value['to_id']==$_SESSION['id']) {
					//applied to you
					$sql = "SELECT first_name,last_name,url FROM user_details WHERE id={$value['from_id']}";
					$results=$db->query($sql);
					$applicant_details=$results->fetch_object();
					mysqli_free_result($results);
					$sql = "SELECT title FROM vacancy WHERE id={$value['vac_id']}";
					$results=$db->query($sql);
					$vac_details=$results->fetch_object();
					mysqli_free_result($results);
					?>

					<div class="friendRequest">
						<a href="<?php echo $applicant_details->url;?>" class="appLink"><?php echo $applicant_details->first_name." ".$applicant_details->last_name;?></a> applied for <?php echo $vac_details->title;?>  vacancy within your company
					</div>
					<?php
				}
			}
		}	
	?>
</div>