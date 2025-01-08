<?php 
require "header.php"; 




//Form Submission Check.
if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['formType'] == 'login') 
{
	//echo '<script>document.getElementById("loginModal").style.display="block";</script>';
	require "login_process.php";
	
}


	require "modals.php";
	
	if (is_null($_SESSION['user_id']))
	{
		$user_id = 0;
	}
	else
	{
		$user_id = $_SESSION['user_id'];
	}
	
	
	
	// This code is just in case a person has accessed it without specifying an id.  This should result in 0 recipes.
	if ($_GET['profile_id'])
	{
		
		$profile_id = $_GET['profile_id'];	

	}
	else
	{
		echo "Profile ID is not found";
		$profile_id = 0;
		
	}

	
	
	

?>

	
	


	
	
    <main>
	
		

		<?php
		
			$followed = false;
			
			$stmt = $db->prepare("SELECT * FROM `test`.`login` WHERE `id` = ?;");
			$stmt->bind_param("i", $profile_id);
			$stmt->execute();
			$res = $stmt->get_result();
			$profile_row = $res->fetch_assoc();

			$stmt = $db->prepare("SELECT * FROM `test`.`follows` WHERE `id_to` = ?;");
			$stmt->bind_param("i", $profile_id);
			$stmt->execute();
			$res = $stmt->get_result();
			$follows_row = $res->fetch_assoc();
			
			$stmt = $db->prepare("SELECT * FROM `test`.`follows` WHERE `id_frm` = ? AND `id_to` = ? LIMIT 1;");
			$stmt->bind_param("ii", $user_id, $profile_id);
			$stmt->execute();
			$res = $stmt->get_result();
			$is_follow_row = $res->fetch_assoc();
			
			
			if (is_null($is_follow_row['id_frm']))
			{
				$followed = false;
			}
			else
			{
				$followed = true;
			}


			$stmt = $db->prepare("SELECT COUNT(`id_to`) AS 'count' FROM `follows` WHERE `id_to` = ?;");
			$stmt->bind_param("i", $profile_id);
			$stmt->execute();
			$res = $stmt->get_result();
			$count_row = $res->fetch_assoc();
	




	
			$followers = $count_row['count'];
			
			
			
			echo '<script>
				function processFollow(from, name_from, to)
				{
					$.ajax({
							url: "process_follows.php?from="+from+"&to="+to,
							success: function(result) {
								window.location.href = "profile.php?profile_id='.$profile_id.'";
							}
					
						});
				}
			
			</script>';
			
			
			
	
			// Title
			echo '<div class="container">
						<div class="profileTitle">'.estring($profile_row['given_name']).'\'s Recipes</div>
						<div class="followBar"><span class="followers">'.$followers.' followers</span><span class="followSpan">';
						
				if (isset($_SESSION['username']))
				{
					echo '<button class="followButton" onclick=\'processFollow('.$user_id.', "'.estring($profile_row['given_name']).'", '.$profile_id.')\'>';
							
					if ($followed)
					{
						echo 'Unfollow';
					}
					else
					{
						echo 'Follow';
					}
					echo '</button></span>';
					
				}
				
						
				echo '</div></div>';


			echo '<script>
				function expandRecipe(r)
				{
					$("#for_recipe_"+r).slideToggle("1000");
				}
			
			</script>';

		
			if ($_GET['message'] == 'd')
			{
				echo '<script>alert("Account deleted.");</script>';
			}
			
			if ($_GET['message'] == 'n')
			{
				echo '<script>alert("You\'ve signed up!  You may now log in using your new credentials.");</script>';
			}
			
			if ($_GET['message'] == 'lo')
			{
				echo '<script>alert("You\'ve logged out!");</script>';
			}
			if ($_GET['message'] == 'r')
			{
				echo '<script>alert("New recipe inserted.");</script>';
			}
			if ($_GET['message'] == 'f')
			{
				echo '<script>alert("Complaint noted!");</script>';
			}

			echo '<div id="all_else">';

			echo '<script>
				$.ajax({
					url: "generate_recipes.php?generation_mode=3&profile_id='.$profile_id.'",
					success: function(result) {
						$("#all_else").html(result);
						}
				});
				</script>
			';
		
			echo '</div>';
		
		?>
	
		
		
    </main>

<?php require "footer.php"; ?>
