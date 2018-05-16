
<?php
	$sql =  "SELECT reciever_id FROM friends WHERE initialiser_id={$id} AND relationship=1 UNION 
	SELECT initialiser_id FROM friends WHERE reciever_id={$id} AND relationship=1";

	if ($results=$db->query($sql)) {
		if ($results->num_rows==0) {
			$friends=false;
		}else{
			//$rows=$results->fetch_all();
			while ($row = $results->fetch_array(MYSQLI_BOTH)) {
							$rows[] = $row;
			}
			
			$friends=true;
			$friend_info=array();

			foreach ($rows as $key => $value) {

				
				$sql = "SELECT first_name,last_name,url,profile_pic FROM user_details JOIN profile_details ON profile_details.user_id=user_details.id WHERE user_details.id={$value[0]}";
				$results2=$db->query($sql);
				$friend_info[]=$results2->fetch_object();
				mysqli_free_result($results2);
			}
			
		}
		mysqli_free_result($results);	
	}
?>



<div id="friends">
	<div id=friendReturn>
		<?php
				if ($friends===false) {
					echo "No friends yet.";
				}else{
					foreach ($friend_info as $key => $value) {
						?>
							<div class="friend">
								<img src="images/<?php echo $value->profile_pic;?>">
								<a href="<?php echo $value->url;?>">
									<?php echo $value->first_name." ".$value->last_name;?>	
								</a>
							</div>
						<?php
					}
					
				}
		?>
		
	

	</div>
</div>