<?php
	require "header.php";
	require "mysql_login.php";


	// Prepare the SQL statement
	$stmt = $db->prepare("DELETE FROM `login` WHERE (`id` = ?);");
	$userid = $_SESSION['user_id'];

	$stmt->bind_param("s", $userid); // "s" means the variable is a string
	$stmt->execute();


	$_SESSION = array();


	session_destroy();



	echo '<script>$(#loginStatus).text("")</script>';


	header('Location: index.php?message=d');
	die();




	require "footer.php";
?>