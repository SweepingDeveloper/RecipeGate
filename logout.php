<?php
require "header.php";
require "mysql_login.php";


$_SESSION = array();


session_destroy();



echo '<script>$(#loginStatus).text("")</script>';



echo '<div class="loginTitle">Logout successful.  <a href="index.php">Click here</a> to return to the main page.</div>';

header('Location: index.php?message=lo');
die();

require "footer.php";



?>