<?php
require "header.php";
require "mysql_login.php";
?>

<main>

	<div class="defForm">
	
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
		
			<?php
			
			$reason_names = ['Recipe Doesn\'t Work', 'Recipe Is Inappropriate','Recipe Is Spam (not the meat)','Recipe Has Too Many Errors','Recipe Is Plagarized','Other'];
			
			
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
				echo '<span class="ind_report_cell">Action</span>';
				
				
				
				
				echo '</div>
				
				';
				
			}


			// Fetch User.
			
			
			
			
			?>
		
		
		</div>
	
	
	</div>




</main>




<?php
require "footer.php";
?>