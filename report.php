<?php
require "header.php";
require "mysql_login.php";


//Form Submission Check.
if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['formType'] == 'flag') 
{
	echo '<script>alert("'.$_POST['complainer_id'].'");</script>';
	$p_complainer_id = $_POST['complainer_id'];
	echo $p_complainer_id;
	$p_reported_id = $_POST['reported_id'];
	$reason_code = $_POST['reason'];
	$comment = $_POST['comment'];

	$now = time();
	$now_format = date("Y-m-d", $now). " " . date("H:i:s", $now);

	
	$stmt = $db->prepare('INSERT INTO `test`.`flags` (`user_id`, `recipe_id`, `reason_code`, `comment`, `timestamp`) VALUES (?, ?, ?, ?, ?);');
	$stmt->bind_param("iiiss", $p_complainer_id, $p_reported_id, $reason_code, $comment, $now_format);
	$stmt->execute();

	header('Location: index.php?message=f');
	

}
else
{
	$reported_id = $_GET['recipe_id'];
	$complainer_id = $_GET['complainer_id'];	
}



	$stmt = $db->prepare('SELECT * FROM `test`.`recipes` WHERE `id` = ?;');
	$stmt->bind_param("i", $reported_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();




	echo '

	
	<form class="defForm" action="report.php" method="post"> 

		<div class="container">
			<div class="signupTitle">Recipe Complaint Form<br>for <i>'.$row['title'].'</i></div>
		</div>	
		<div class="defContainer">
			<input class="hiddenInput" name="formType" value="flag">
			<input class="hiddenInput" name="reported_id" value="'.$reported_id.'">
			<input class="hiddenInput" name="complainer_id" value="'.$complainer_id.'">
			
			<label for="complaint_reason"><b>Reason For Complaint</b></label>
			<div class="complaint_placeholder">
			<select name="reason">
				<option value="1">Recipe Doesn\'t Work</option>
				<option value="2">Recipe Is Inappropriate</option>
				<option value="3">Recipe Is Spam (not the meat)</option>
				<option value="4">Recipe Has Too Many Errors</option>
				<option value="5">Recipe Is Plagarized</option>
				<option value="6">Other</option>
			</select>
			</div>
			<label for="comment_label"><b>Comments</b></label><br><br>
			<textarea name="comment" class="muchtext"></textarea>
			
			<button class="longButton" type="submit">Submit</button>
			
			
		</div>
	
	

	</form>
	


	';


require "footer.php";
?>