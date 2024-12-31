<?php


session_start();   //Start every PHP page with session_start().  By putting it in the header, you already do this.

require "mysql_login.php";
require "encrypt_2025.php";

$complaint_id = $_GET['complaint_id'];
$recipe_id = $_GET['recipe_id'];
$plaintiff_id = $_GET['complaining_user_id'];
$defendant_id = $_GET['target_user_id'];
$selected_action = $_GET['selectedAction'];


$reason_names = ['Recipe Doesn\'t Work', 'Recipe Is Inappropriate','Recipe Is Spam (not the meat)','Recipe Has Too Many Errors','Recipe Is Plagarized','Other'];
$action_names = ['Do Nothing','Remove Complaint','Send Message To Plaintiff','Send Message To Defendant','Remove Recipe','Ban Defendant For 1 Week','Ban Defendant For 4 Weeks','Ban Defendant For 1 Year','Ban Defendant For 100 Years','Ban Plaintiff For 1 Week','Ban Plaintiff For 4 Weeks','Ban Plaintiff For 1 Year','Ban Plaintiff For 100 Years'];

//Generate today's date.
//https://stackoverflow.com/questions/1995562/now-function-in-php
$today = date_create(date("Y-m-d"));



// -> format from https://stackoverflow.com/questions/10209941/object-of-class-datetime-could-not-be-converted-to-string

switch ($selected_action)
{
	case 0:		// Do Nothing
		break;
	case 1: 	// Remove Complaint
		$stmt = $db->prepare('DELETE FROM `flags` WHERE (`id` = ?);');
		$stmt->bind_param("i", $complaint_id);
		$stmt->execute();
		//$result = $stmt->get_result();
		//$row = $result->fetch_assoc();
	
		break;
	case 2: 	// Send Message To Plaintiff
		break;
	case 3: 	// Send Message To Defendant
		break;
	case 4: 	// Remove Recipe
		$stmt = $db->prepare('DELETE FROM `recipes` WHERE (`id` = ?);');
		$stmt->bind_param("i", $recipe_id);
		$stmt->execute();

		

		break;
	case 5: 	// Ban Defendant For 1 Week
		$ban_length = 7;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $defendant_id);
		$stmt->execute();

		break;
	case 6: 	// Ban Defendant For 4 Weeks
		$ban_length = 28;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $defendant_id);
		$stmt->execute();
		break;
	case 7: 	// Ban Defendant For 1 Year
		$ban_length = 365;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $defendant_id);
		$stmt->execute();
		break;
	case 8: 	// Ban Defendant For 100 Years
		$ban_length = 100;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' years'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $defendant_id);
		$stmt->execute();
		break;
	case 9: 	// Ban Plaintiff For 1 Week
		$ban_length = 7;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d') ;
		echo $ban_release;
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $plaintiff_id);
		$stmt->execute();
		break;
	case 10: 	// Ban Plaintiff For 4 Weeks
		$ban_length = 28;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $plaintiff_id);
		$stmt->execute();
		break;
	case 11: 	// Ban Plaintiff For 1 Year
		$ban_length = 365;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' days'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $plaintiff_id);
		$stmt->execute();
		break;
	case 12: 	// Ban Plaintiff For 100 Years
		$ban_length = 100;
		$ban_release = date_sub($today, date_interval_create_from_date_string('-'.$ban_length . ' years'))->format('Y/m/d');		
		$stmt = $db->prepare('UPDATE `test`.`login` SET `ban_status` = ? WHERE (`id` = ?);');
		$stmt->bind_param("si", $ban_release, $plaintiff_id);
		$stmt->execute();
		break;
}


echo 'Plaintiff # '.$plaintiff_id.' complained about a recipe from Defendant # '.$defendant_id.'.  Action taken: '.$action_names[$selected_action].'.';

?>