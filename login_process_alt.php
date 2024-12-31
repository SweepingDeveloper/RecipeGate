<?php
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

		// Verify the password
		if ($password == $hashed_password)
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
			$login_message = "Login successful.";
			header('Location: index.php');
			//echo '<div class="loginTitle">Login successful.  <a href="index.php">Click here</a> to return to the main page.</div>';
		} 
		else 
		{
			echo '<script>
					 document.getElementById("loginModal").style.display="block";
					 document.getElementById("loginMessage").style.color="red";
					 document.getElementById("loginMessage").innerHTML = "Invalid username or password.";
				</script>';
			
			//$login_message = "Invalid username or password.";
			//echo '<div class="loginTitle">Invalid username or password.</div>';
		}
	} 
	else 
	{
			echo '<script>
					 document.getElementById("loginModal").style.display="block";
					 document.getElementById("loginMessage").style.color="blue";
					 document.getElementById("loginMessage").innerHTML = "Invalid username or password.";
				</script>';
		//$login_message = "Invalid username or password.";
		//echo '<div class="loginTitle">Invalid username or password.</div>';
	}

	$stmt->close();
		
?>