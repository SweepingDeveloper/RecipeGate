<?php
require "header.php";
require "mysql_login.php";


$monthnames = array('January','February','March','April','May','June','July','August','September','October','November','December');

$message = "Hello";


$username = $_POST["username"];
$password = $_POST["password"];
$password_again = $_POST["password_again"];


$epoch_date = "1901-01-01";


//Test for Username.

	$stmt = $db->prepare('SELECT COUNT(*) AS `count` FROM `test`.`login` WHERE `username` = ?');
	$stmt->bind_param("s", estring($username));
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	
	$username_count = $row['count'];



//Grab birthdate information.
$b_day = substr($_POST['birth_day'],4);
$b_month = substr($_POST['birth_month'],6);
$b_year = substr($_POST['birth_year'],5);


//Generate a date format for the birthdate.
if (!empty($b_day) and !empty($b_month) and !empty($b_year))
{
	$b_date = date_create($b_year."-".$b_month."-".$b_day);
}
else
{
	$b_date = date_create(date("Y-m-d"));
}

//Generate today's date.
//https://stackoverflow.com/questions/1995562/now-function-in-php
$today = date_create(date("Y-m-d"));


//Generate the day codes for each.
//From https://www.php.net/manual/en/function.date-diff.php#126177
$today_from_epoch = (array) date_diff(date_create($epoch_date), $today);
$b_date_from_epoch = (array) date_diff(date_create($epoch_date), $b_date);

$today_date_code = $today_from_epoch['days'];  //date_diff($epoch_date, $today)->format("%a");
$b_date_code = $b_date_from_epoch['days'];  //date_diff($epoch_date, $b_date)->format("%a");

//Generate a minimum age date code.
$min_age_date = $today;
$min_date = date_sub($min_age_date, date_interval_create_from_date_string('18 years'));
$min_date_from_epoch = (array) date_diff(date_create($epoch_date), $min_date); 

$min_date_code = $min_date_from_epoch['days']; //date_diff($epoch_date, $min_date)->format("%a");


//Error Checks:


$ok = true;
if (intval($username_count) > 0)
{
	$message = "Someone already has that username.  Please use another one.";
	$ok = false;
}
else if ($password == "pants")
{
	echo '<script>window.location.replace("https://www.youtube.com/watch?v=qyoGSZxD6JU");</script>';  //Worldwide Pants
	$ok = false;
}
else if ($password !== $password_again)
{
	$message = "The passwords don't match.  Please try again.";
	$ok = false;
}
else if (strlen($password) < 10)
{
	$message = "The password is too short.  Please try again.";
	$ok = false;
}
else if (preg_match('/^[a-zA-Z0-9]/', $password) == false)
{
	$message = "Uppercase letters, Lowercase letters, and Numbers only please.";
	$ok = false;
}
else if (intval($b_date_code) > intval($min_date_code)) //If you were born after the minimum date code
{
	$message = "Sorry, you're not yet old enough to use this website.";
	$ok = false;
}

if ($ok)
{
	$message = "You're good to go!";
	$default_ban_status = "1900-01-01";
	$default_admin_status = "0";
	$b_date_string = $b_year."-".$b_month."-".$b_day;
	//Check for Email: 
	$stmt = $db->prepare('INSERT INTO `test`.`login` (`username`, `given_name`, `password`, `birthdate`,`ban_status`,`admin_status`) VALUES (?,?,?,?,?,?);');
	$stmt->bind_param("ssssss", estring($username), estring($username), estring($password), $b_date_string, $default_ban_status, $default_admin_status);
	$stmt->execute();
	$result = $stmt->get_result();
	header('Location: index.php?message=n');
}
else
{
	echo '
		<form class="defForm" action="signup_process.php" method="post">
		
			<div class="container">
				
				<div class="signupTitle">Sign Up For RecipeGate</div>
			
			</div>
			
			
			<div class="defContainer">
				'.$message.'
				<input class="hiddenInput" name="formType" value="signup"> 
				<label for="username"><b>Username</b></label>
				<input class="redo" type="text" placeholder="Enter Username" name="username" required>
				<label for="password"><b>Password</b></label>
				<input class="redo" type="password" placeholder="Enter Password" name="password" required>
				<label for="password_again"><b>Re-Enter Password</b></label>
				<input class="redo" type="password" placeholder="Enter Password" name="password_again" required>
				<fslabel for="birthdate"><b>Birthdate</b></label>
				<div class="birthdate_placeholder">
					<select name="birth_month">';
						
							$monthnames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
						
							for ($a = 1; $a <= 12; $a++)
							{
								if ($a < 10) {echo '<option value="month_0'.strval($a).'">'.$monthnames[$a-1].'</option>';}
								else {echo '<option value="month_'.strval($a).'">'.$monthnames[$a-1].'</option>';}
							}
							
		echo'
					</select>
					<select name="birth_day">';
					
						
							for ($a = 1; $a <= 31; $a++)
							{
								if ($a < 10) {echo '<option value="day_0'.strval($a).'">'.strval($a).'</option>';}
								else {echo '<option value="day_'.strval($a).'">'.strval($a).'</option>';}
							}							
						
		echo '
					</select>
					<select name="birth_year">';
						
							for ($a = intval(date("Y")); $a > intval(date("Y")) - 120; $a--)
							{
								echo '<option value="year_'.strval($a).'">'.strval($a).'</option>';
							}
					
					
		echo'
					</select>
				</div>
				<button class="longButton" type="submit">Sign Up</button>
				<br><br>
				<label for="signupMessage">
					<div class="signupMessage">
					<p align="center">You must be 18 years old or older to use RecipeGate.</p><br>
						<p align="center">Passwords must:
						<br><br>Be at least 10 characters long
						<br>Only contain uppercase letters, lowercase letters, and numbers.
						
					</p>
					</div>	
				</label>
				
				
				
			</div>
		
		</form>

		';

}




require "footer.php";
?>