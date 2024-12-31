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


$now = time();
$now_format = date("Y-m-d", $now). " " . date("H:i:s", $now);

//INSERT INTO `test`.`follows` (`from`, `to`, `timestamp`) VALUES ('2', '8', 'stuff');

$from = $_GET['from'];
$to = $_GET['to'];


// This is to make sure the to/from combination exists.
$stmt = $db->prepare("SELECT * FROM `test`.`follows` WHERE `id_frm` = ? AND `id_to` = ? LIMIT 1;");
$stmt->bind_param("ii", $from, $to);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (is_null($row['id_frm']))
{
	// Process Follow.
	$stmt = $db->prepare("INSERT INTO `test`.`follows` (`id_frm`, `id_to`, `timestamp`) VALUES (?, ?, ?);");
	$stmt->bind_param("iis", $from, $to, $now_format);
	$stmt->execute();	
	echo 1;
}
else
{
	// Process Unfollow.
	$stmt = $db->prepare("DELETE FROM `test`.`follows` WHERE `id_frm` = ? AND `id_to` = ?;");
	$stmt->bind_param("ii", $from, $to);
	$stmt->execute();	
	echo 0;
	
}







?>