	<?php 
	require "header.php";
	require "mysql_login.php";


	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		if (isset($_SESSION['username']) and $_POST['formType'] == "settings")
		{
			//echo '<script>document.getElementById("loginModal").style.display="block";</script>';

			$new_given_name = estring($_POST['new_given_name']);

			$stmt = $db->prepare('UPDATE `test`.`login` SET `given_name` = ? WHERE (`id` = ?);');
			$stmt->bind_param("ss", $new_given_name, $_SESSION['user_id']);
			$stmt->execute();
			$result = $stmt->get_result();
			$_SESSION['given_name'] = $new_given_name;
			//echo '<script>alert ("Given name changed.");</script>';
			//$message = "Given Name Changed.";
			

			header('Location: settings.php?message=1');
			
		}
		else if ($_POST['formType'] == "login")
		{
			$message = "";
			
			require "login_process.php";
			
			
			
		}
		else if ($_POST['formType'] == "delete")
		{
			$message = "Account deleted.";
			
			require "delete_process.php";
			
		}
		
		

	}
	else
	{
		if ($_GET['message'] == '1') {$message = "Given Name Changed.";}
		//$message = "";
	}



	require "modals.php";

 ?>

<main>
	<?php if (isset($_SESSION['username']) == false) {$encrypted_given_name = "";}  ?>


	<div class="defModal" id="deleteModal">
	
		
		<form class="defForm">
			
			<div class="container">
				
				<div class="loginTitle">Delete Account</div>
				<span onclick="document.getElementById('deleteModal').style.display='none'" class="close" title="Close Delete Modal">&times;</span>

			
			</div>
			<div class="defContainer">
				<input class="hiddenInput" name="formType" value="delete">
				<label for="deleteQuery"><b>Are you sure you wish to delete your account?</b></label>
				<br><br>
				<a id="noDelete" onclick="document.getElementById('deleteModal').style.display='none'"><b><u>No!  Don't Delete!</u></b></a>
				<br><br>
				<a id="yesDelete" href="delete_process.php">Delete Account</a>
			</div>
		
		
		</form>
		

	
	</div>
	
	
	
	
	
	
	<form class="defForm" action="#" method="post">
		<div class="container">
		<div class="pageTitle"><?php echo $encrypted_given_name; ?> Account Settings</div>
			<br>

			<div class="defContainer">
				<input class="hiddenInput" name="formType" value="settings">
				<label for="given_name"><b>Given Name</b></label>
				<input type="text" name="new_given_name">
				<button class="longButton" type="submit">Submit</button>
				<br>
				<br>
				<label for="deleteAccount"><a href="#" onclick= "document.getElementById('deleteModal').style.display='block'"><b>Delete Account</b></a></label>
				<br>
				<br>
				<div class="message"><?php echo $message; ?></div>
			</div>
		
		</div>
		
	
	</form>

</main>

<?php require "footer.php"; ?>