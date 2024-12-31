<?php
require "header.php";
require "mysql_login.php";
?>

<main>


<?php  


	if (isset($_SESSION['username']) and ($_SESSION['admin_status'] == 1))
	{
		
		echo '<div class="defForm">
	
		<div class="container">
		
			<div class="signupTitle">Complaint Reports</div>
			
		</div>
		
		<div class="reportContainer">
		
			<div class="report_headings">
				<span class="report_heading">User</span>
				<span class="report_heading">Recipe Name</span>
				<span class="report_heading">Reason</span>
				<span class="report_heading">Comment</span>
				<span class="report_heading">Timestamp</span>
				<span class="report_heading">Action</span>
			</div>
					';
		
		
		$reason_names = ['Recipe Doesn\'t Work', 'Recipe Is Inappropriate','Recipe Is Spam (not the meat)','Recipe Has Too Many Errors','Recipe Is Plagarized','Other'];
		$action_names = ['Do Nothing','Remove Complaint','Send Message To Plaintiff','Send Message To Defendant','Remove Recipe','Ban Defendant For 1 Week','Ban Defendant For 4 Weeks','Ban Defendant For 1 Year','Ban Defendant For 100 Years','Ban Plaintiff For 1 Week','Ban Plaintiff For 4 Weeks','Ban Plaintiff For 1 Year','Ban Plaintiff For 100 Years'];
		
		// Fetch All Complaints.
		$stmt = $db->prepare('SELECT * FROM `test`.`flags`;');
		//$stmt->bind_param("si", $db_name, $reported_id);
		$stmt->execute();
		$result = $stmt->get_result();


		while ($row = $result->fetch_assoc())
		{
			echo '<div class="ind_report">';
			
			if ($row['user_id'] == 0)
			{
				$complaining_user = "Anonymous";
			}
			else 
			{
				// Fetch User.
				$u_stmt = $db->prepare('SELECT * FROM `test`.`login` WHERE `id` = ?');
				$u_stmt->bind_param("i", $row['user_id']);
				$u_stmt->execute();
				$u_result = $u_stmt->get_result();
				$u_row = $u_result->fetch_assoc();
				
				$complaining_user = estring($u_row['given_name']);
			}
			
			echo '<span class="ind_report_cell">'.$complaining_user.'</span>';
			
			// Fetch Recipe.
			$r_stmt = $db->prepare('SELECT * FROM `test`.`recipes` WHERE `id` = ?');
			$r_stmt->bind_param("i", $row['recipe_id']);
			$r_stmt->execute();
			$r_result = $r_stmt->get_result();
			$r_row = $r_result->fetch_assoc();
			
			
			echo '<span class="ind_report_cell">'.$r_row['title'].'</span>';
			echo '<span class="ind_report_cell">'.$reason_names[$row['reason_code']-1].'</span>';
			echo '<span class="ind_report_cell">'.$row['comment'].'</span>';
			echo '<span class="ind_report_cell">'.$row['timestamp'].'</span>';
			echo '<span class="ind_report_cell">';
			
			echo '<select id="action_'.$row['id'].'" name="action_'.$row['id'].'">';
			for ($a = 0; $a < sizeof($action_names); $a++)
			{
				echo '<option value="'.$a.'">'.$action_names[$a].'</option>';
			}
			echo '</select>';
			
			if ($row['user_id'] == 0)
			{
				echo '<br><button onclick="processComplaint('.$row['id'].', '.$row['recipe_id'].', 0, '.$r_row['user_id'].')">Submit</button>';
			}
			else
			{
				echo '<br><button onclick="processComplaint('.$row['id'].', '.$row['recipe_id'].', '.$u_row['id'].', '.$r_row['user_id'].')">Submit</button>';
			}
			
			
			echo '</span>';
			
			
			
			
			echo '</div>
			
			';
			
		}

		
		echo '
		
					<script>

				function processComplaint(complaint_id, recipe_id, complaining_user_id, target_user_id)
				{
					var actionNames = [\'Do Nothing\',\'Remove Complaint\',\'Send Message To Plaintiff\',\'Send Message To Defendant\',\'Remove Recipe\',\'Ban Defendant For 1 Week\',\'Ban Defendant For 4 Weeks\',\'Ban Defendant For 1 Year\',\'Ban Defendant For 100 Years\',\'Ban Plaintiff For 1 Week\',\'Ban Plaintiff For 4 Weeks\',\'Ban Plaintiff For 1 Year\',\'Ban Plaintiff For 100 Years\'];;
					console.log(document.getElementById("action_"+complaint_id).selectedIndex);
					
					var selectedAction = document.getElementById("action_"+complaint_id).selectedIndex;
					var warningMessage = "";
					
					
					switch (selectedAction)
					{
						case 0:
							warningMessage = "You are about to do nothing.  Confirm?";
							break;
						
						case 1:
							warningMessage = "You are about to remove complaint # "+complaint_id+".  Confirm?";				
							break;
						
						case 2:
							warningMessage = "You are about to send a message to Plaintiff # "+complaining_user_id+".  Confirm?";
							break;
						
						case 3:
							warningMessage = "You are about to send a message to Defendatnt # "+target_user_id+".  Confirm?";
							break;
						
						case 4:
							warningMessage = "You are about to remove recipe # "+recipe_id+".  Confirm?";
							break;
						
						case 5:
							warningMessage = "You are about to ban Defendent # "+target_user_id+" for 1 week.  Confirm?";
							break;
						
						case 6:
							warningMessage = "You are about to ban Defendent # "+target_user_id+" for 4 weeks.  Confirm?";
							break;
						
						case 7:
							warningMessage = "You are about to ban Defendent # "+target_user_id+" for 1 year.  Confirm?";
							break;
						
						case 8:
							warningMessage = "YOU ARE ABOUT TO BAN DEFENDANT # "+target_user_id+" FOR 100 YEARS.  CONFIRM?";
							break;
						
						case 9:
							warningMessage = "You are about to ban Plaintiff # "+complaining_user_id+" for 1 week.  Confirm?";
							break;
						
						case 10:
							warningMessage = "You are about to ban Plaintiff # "+complaining_user_id+" for 4 weeks.  Confirm?";
							break;
						
						case 11:
							warningMessage = "You are about to ban Plaintiff # "+complaining_user_id+" for 1 year.  Confirm?";
							break;
						
						case 12:
							warningMessage = "YOU ARE ABOUT TO BAN PLAINTIFF # "+complaining_user_id+" FOR 100 YEARS.  CONFIRM?";
							break;
						
					}
					
					
					if (confirm(warningMessage))
					{
						$.ajax({
									url: "process_complaint_action.php?complaint_id="+complaint_id+"&recipe_id="+recipe_id+"&complaining_user_id="+complaining_user_id+"&target_user_id="+target_user_id+"&selectedAction="+selectedAction,
									success: function(result) {
										alert(result);
										// https://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-webpage
										window.location.href="report_manager.php";
									}
							
								});
					}
					else
					{
						alert ("Nothing has been done.  Carry on.");
					}
					
					
				}
			
			
			
			</script>
					
				
				</div>
			
			
			</div>




		</main>

				
		
		
		';
		
		
		
		
		
	}
	else
	{
		echo 'Access denied to non-admins.  <a href="index.php">Click here</a> to return to the main page.';
	}


require "footer.php";
?>