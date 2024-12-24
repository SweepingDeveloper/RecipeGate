<?php

session_start();   //Start every PHP page with session_start().  By putting it in the header, you already do this.

require "mysql_login.php";
require "encrypt_2025.php";
//error_reporting(0);
//echo var_dump(get_defined_functions());
if (isset($_SESSION['username']))
{
	$encrypted_given_name = estring($_SESSION['given_name']);
}


$recipe_id = $_GET['id'];

$user_id = $_SESSION['user_id'];


$stmt = $db->prepare("SELECT * FROM `likes` WHERE `user_id` = ? AND `recipe_id` = ?;");
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (empty($row))
{
	
	
	$stmt = $db->prepare("INSERT INTO `test`.`likes` (`user_id`, `recipe_id`) VALUES (?, ?);");
	$stmt->bind_param("ii", $user_id, $recipe_id);
	$stmt->execute();
	echo 1;
	
}
else
{
	$stmt = $db->prepare("DELETE FROM `test`.`likes` WHERE `user_id` = ? AND `recipe_id` = ?;");
	$stmt->bind_param("ii", $user_id, $recipe_id); 
	$stmt->execute();
	echo 0;
	
}



?>