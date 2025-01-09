<?php 
require "header.php"; 




//Form Submission Check.
if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['formType'] == 'login') 
{
	//echo '<script>document.getElementById("loginModal").style.display="block";</script>';
	require "login_process.php";
	
}


	require "modals.php";
?>

	
	


	
	
    <main>
	
		

		<?php

			echo '<script>
				function expandRecipe(r)
				{
					$("#for_recipe_"+r).slideToggle("1000");
				}
			
			</script>';

		
			if ($_GET['message'] == 'd')
			{
				echo '<script>alert("Account deleted.");</script>';
			}
			
			if ($_GET['message'] == 'n')
			{
				echo '<script>alert("You\'ve signed up!  You may now log in using your new credentials.");</script>';
			}
			
			if ($_GET['message'] == 'lo')
			{
				echo '<script>alert("You\'ve logged out!");</script>';
			}
			if ($_GET['message'] == 'r')
			{
				echo '<script>alert("New recipe inserted.");</script>';
			}
			if ($_GET['message'] == 'f')
			{
				echo '<script>alert("Complaint noted!");</script>';
			}
			if ($_GET['message'] == 'e')
			{
				echo '<script>alert("Recipe edited!");</script>';
			}


			


			// Learning from https://www.youtube.com/watch?v=QxMBHi_ZiT8
			echo '<div class="search_bar">
			
				<div class="search_input">
					<input type="text" placeholder="Type to search...">
					<div class="autocomplete_box">
						<li>Populate Tag Suggetions Here.</li>
						<li>Populate Tag Suggetions Here.</li>
						<li>Populate Tag  Here.</li>
						<li>Populate Tag Suggetions Here.</li>
					</div>
					<div class="icon" id="searchButton"><span onclick="processSearch(inputBox.value);">&#x1F50E</span></div>
			
				</div>
			
			</div>';


			echo '<script>';
			echo 'let suggestions = [];';
			
			$stmt = $db->prepare('SELECT * FROM `keywords`;');
			$stmt->execute();
			$result = $stmt->get_result();


			while ($row = $result->fetch_assoc())
			{
				echo 'suggestions.push("'.$row['keyword'].'");';
			}
			
			
			echo '</script>
			
			<script src="suggestion_script.js"></script>';


		// Put AJAX Code here.

			echo '<div id="all_else" class="all_else">
			
					Generating Recipes...
			
			
			
			
			
			</div>
			';

			echo '<script>
			
			
			
				document.getElementById("all_else").onclick = function()
				{
					searchWrapper.classList.remove("active"); // Hide autocomplete box.
					console.log("Closed Autocomplete Box.");
				}

				$.ajax({
					url: "generate_recipes.php",
					success: function(result) {
						$("#all_else").html(result);
						}
				});
				</script>
			';


		
		?>
	
		
		
    </main>

<?php require "footer.php"; ?>
