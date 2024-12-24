<?php session_start();   //Start every PHP page with session_start().  By putting it in the header, you already do this. ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeGate</title>
    <link rel="stylesheet" href="styles.css">
	<script src="jquery-3.7.1.min.js"></script>
</head>
<body>


	<?php
			require "mysql_login.php";
			require "encrypt_2025.php";
			//error_reporting(0);
			//echo var_dump(get_defined_functions());
			if (isset($_SESSION['username']))
			{
				$encrypted_given_name = estring($_SESSION['given_name']);
			}


	
			//echo estring('anonrobot@robot.com');
	
	?>
    <header>
        <div class="logo">RecipeGate</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">About</a></li>
				<?php
					if (isset($_SESSION['username']))
					{
						echo '<li><a href="settings.php">Settings</a></li>';
						if (array_search($_SESSION['admin_status'], $admins))  
						{
							echo '<li><a href="">Reports</a></li>';
						}
					}
					
					
				?>
                <li id="loginStatus">
				<?php				
				
					if (isset($_SESSION['username']))
					{
						echo '<a href="logout.php">Logout ('.$encrypted_given_name.')</a>';
					}
					else
					{
						echo '<a href="#" onclick= "document.getElementById(\'loginModal\').style.display=\'block\'">Login/Sign Up</a>';  
					}
				?>
				</li>
				
				
				
            </ul>
        </nav>
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </header>