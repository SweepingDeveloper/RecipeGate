<?php
require "header.php";
require "mysql_login.php";


	$username = estring($_POST['username']);
	$password = estring($_POST['password']);
	$login_message = "";

	// Prepare the SQL statement
	$stmt = $db->prepare("SELECT * FROM `login` WHERE username = ?");


	$stmt->bind_param("s", $username); // "s" means the variable is a string
	$stmt->execute();

	// Get the result
	$result = $stmt->get_result();

	if ($result->num_rows === 1) 
	{
		// Fetch the hashed password from the database
		$row = $result->fetch_assoc();
		$hashed_password = $row['password'];
		$given_name = $row['given_name'];
		$user_id = $row['id'];
		$admin_status = $row['admin_status'];
		$ban_status = $row['ban_status'];

		$banned = false;
		
		
		//Generate today's date.
		//https://stackoverflow.com/questions/1995562/now-function-in-php
		$today = date_create(date("Y-m-d"));			
		$ban_release = date_create($ban_status);

		


		
		// Verify the password
		if ($password == $hashed_password and $ban_release <= $today)
		{
			#if (password_verify($password, $hashed_password)) {
			// Password is correct, start a session or set a cookie
			session_start();
			echo '<pre>';
			var_dump($_SESSION);
			echo '</pre>';
			$_SESSION['username'] = $username; // Store the username in the session
			$_SESSION['given_name'] = $given_name;  //  Store the given name in the session
			$_SESSION['user_id'] = $user_id; //Store the user id in the session
			$_SESSION['admin_status'] = $admin_status;
			

			$login_message = "Login successful.";
			header('Location: index.php');
			//echo '<div class="loginTitle">Login successful.  <a href="index.php">Click here</a> to return to the main page.</div>';
		} 
		else if ($ban_release <= $today)
		{
			$login_message = "Invalid username or password.";
			echo '<br><br>
					<form class="defForm" action="login_process.php" method="post">
			
							<div class="container">
								
								<div class="loginTitle">Login To RecipeGate</div>
								<br>
								<label for="loginMessage"><div class="loginMessage">'.$login_message.'</div></label>
							</div>
							<div class="defContainer">
								<input class="hiddenInput" name="formType" value="login"> 
								<label for="username"><b>Username</b></label>
								<input class="redo" type="text" placeholder="Enter Username" name="username" required>
								<label for="password"><b>Password</b></label>
								<input class="redo" type="password" placeholder="Enter Password" name="password" required>
								<button class="longButton" type="submit">Login</button>
								<br><br>
							</div>
						
						
						</form>
						
						';
			
			//echo '<div class="loginTitle">Invalid username or password.</div>';
		}
		else
		{
			$login_message = "You are under a ban until ".$ban_release->format('m-d-Y').".  To appeal, please email me if and when I get a new email.";
			echo '<br><br>
					<form class="defForm" action="login_process.php" method="post">
			
							<div class="container">
								
								<div class="loginTitle">Login To RecipeGate</div>
								<br>
								<label for="loginMessage"><div class="loginMessage">'.$login_message.'</div></label>
							</div>
							<div class="defContainer">
								<input class="hiddenInput" name="formType" value="login"> 
								<label for="username"><b>Username</b></label>
								<input class="redo" type="text" placeholder="Enter Username" name="username" required>
								<label for="password"><b>Password</b></label>
								<input class="redo" type="password" placeholder="Enter Password" name="password" required>
								<button class="longButton" type="submit">Login</button>
								<br><br>
							</div>
						
						
						</form>
						
						';
			
		}
	} 
	else 
	{
			$login_message = "Invalid username or password.";
			echo '<br><br>
					<form class="defForm" action="login_process.php" method="post">
			
							<div class="container">
								
								<div class="loginTitle">Login To RecipeGate</div>
								<br>
								<label for="loginMessage"><div class="loginMessage">'.$login_message.'</div></label>
							</div>
							<div class="defContainer">
								<input class="hiddenInput" name="formType" value="login"> 
								<label for="username"><b>Username</b></label>
								<input class="redo" type="text" placeholder="Enter Username" name="username" required>
								<label for="password"><b>Password</b></label>
								<input class="redo" type="password" placeholder="Enter Password" name="password" required>
								<button class="longButton" type="submit">Login</button>
								<br><br>
							</div>
						
						
						</form>
						
						';
			
		//echo '<div class="loginTitle">Invalid username or password.</div>';
	}

	$stmt->close();


require "footer.php";
?>