<?php
require "header.php";
require "mysql_login.php";
//$_SESSION

$fields = [];

$tag = [];
$ingredient_quantity = [];
$ingredient_unit = [];
$ingredient_name = [];
$direction = [];

$tag_numbers = [];

$now = time();
$now_format = date("Y-m-d", $now). " " . date("H:i:s", $now);


$user_id = $_SESSION['user_id'];

foreach ($_POST as $key => $value)
{
	array_push($fields, htmlspecialchars($key));
	
}

var_dump($fields);

//Ignore the 0th field.  That's the "formType", which is an invisible marker.

//title
//tags

//prep_time
//serves

//ingredient_x_quantity
//ingredient_x_unit
//ingredient_x_name

//direction_x

foreach ($fields as $x)
{
	if (substr($x,0,3) == "tag")
	{
		array_push($tag, $_POST[$x]);
	}		
	else if (substr($x,-8) == "quantity")
	{
		array_push($ingredient_quantity, $_POST[$x]);
	}
	else if (substr($x, -4) == "unit")
	{
		array_push($ingredient_unit, $_POST[$x]);
	}
	else if (substr($x, -4) == "name")
	{
		array_push($ingredient_name, $_POST[$x]);
	}
	else if (substr($x,0,9) == "direction")
	{
		array_push($direction, $_POST[$x]);
	}
}


$recipe_title = $_POST['title'];
$prep_time = $_POST['prep_time'];
$serves = $_POST['serves'];


//Extract tag names.
$stmt = $db->prepare('SELECT * FROM `test`.`tags` ORDER BY `id` ASC;');
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc())
{
	$tag_numbers[$row['name']] = $row['id'];
}


//Insert initial values in the database.
$stmt = $db->prepare('INSERT INTO `test`.`recipes` (`title`, `user_id`, `serves`, `prep_time`, `timestamp`) VALUES (?, ?, ?, ?, ?);');
$stmt->bind_param("siiis", $recipe_title, $user_id, $serves, $prep_time, $now_format);
$stmt->execute();
$result = $stmt->get_result();

//Grab new recipe id.
$stmt = $db->prepare('SELECT `id` FROM `test`.`recipes` WHERE `title` = ? AND `user_id` = ? AND `serves` = ? AND `prep_time` = ? LIMIT 1;');
$stmt->bind_param("siii", $recipe_title, $user_id, $serves, $prep_time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$recipe_id = $row['id'];

//Insert tags in the database.
foreach ($tag as $t)
{
	$stmt = $db->prepare('INSERT INTO `test`.`tag_list` (`recipe_id`, `tag_id`) VALUES (?, ?);');
	$stmt->bind_param("ii", $recipe_id, $tag_numbers[$t]);
	$stmt->execute();
}

//Insert ingredients in the database.
for ($a = 0; $a < sizeof($ingredient_name); $a++)
{
	$stmt = $db->prepare('INSERT INTO `test`.`ingredients` (`recipe_id`, `quantity`, `type`, `name`) VALUES (?, ?, ?, ?);');
	$stmt->bind_param("iiss", $recipe_id,$ingredient_quantity[$a], $ingredient_unit[$a], $ingredient_name[$a]);
	$stmt->execute();
	
}

//Insert directions in the database.
for ($a = 0; $a < sizeof($direction); $a++)
{
	$direction_num = ($a + 1);
	$stmt = $db->prepare('INSERT INTO `test`.`directions` (`recipe_id`, `direction_num`, `direction`) VALUES (?,?,?);');
	$stmt->bind_param("iis", $recipe_id, $direction_num, $direction[$a]);
	$stmt->execute();
}


header('Location: index.php?message=r');








require "footer.php";
?>