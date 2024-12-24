<?php
require "header.php";
require "mysql_login.php";

	$encrypted_username = estring($username);

	$stmt = $db->prepare("SELECT * FROM `login` WHERE username = ?");


	$stmt->bind_param("s", $encrypted_username); // "s" means the variable is a string
	$stmt->execute();

	// Get the result
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();


	session_start();
	echo '<pre>';
	var_dump($_SESSION);
	echo '</pre>';
	$_SESSION['username'] = $encrypted_username; // Store the username in the session
	$_SESSION['given_name'] = $encrypted_username;  //  Store the given name in the session
	$_SESSION['user_id'] = $row['id']; //Store the user id in the session



	echo '<div class="loginTitle">Welcome to RecipeGate, '.estring($encrypted_username).'!</div>'
	echo '<br><a href="index.php">Click here to go to the home page.</a>';


require "footer.php";
?>