
	


	<div id="loginModal" class="defModal">
		
		<form class="defForm" action="login_process.php" method="post">
			
			<div class="container">
				
				<div class="loginTitle">Login To RecipeGate</div>
				<span onclick="document.getElementById('loginModal').style.display='none'" class="close" title="Close Login Modal">&times;</span>

			
			</div>
			<div class="defContainer">
				<input class="hiddenInput" name="formType" value="login"> 
				<label for="username"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="username" required>
				<label for="password"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="password" required>
				<button class="longButton" type="submit">Login</button>
				<br><br>
				<label for="signupQuery"><div class="loginMessage">Don't have an account?  <a href="#" onclick="document.getElementById('signupModal').style.display='block'">Sign up</a> here!</div></label>
				<label for="loginMessage"><div class="loginMessage"><?php echo $login_message; ?></div></label>
			</div>
		
		
		</form>
		

	
	</div>
	
	
	
	
	<div id="signupModal" class="defModal">
	
		<form class="defForm" action="signup_process.php" method="post">
		
			<div class="container">
				
				<div class="signupTitle">Sign Up For RecipeGate</div>
				<span onclick="document.getElementById('signupModal').style.display='none'" class="close" title="Close Signup Modal">&times;</span>

			
			</div>
			
			
			<div class="defContainer">
				<input class="hiddenInput" name="formType" value="signup"> 
				<label for="username"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="username" required>
				<label for="password"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="password" required>
				<label for="password_again"><b>Re-Enter Password</b></label>
				<input type="password" placeholder="Enter Password" name="password_again" required>
				<fslabel for="birthdate"><b>Birthdate</b></label>
				<div class="birthdate_placeholder">
					<select name="birth_month">
						<?php
							$monthnames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
						
							for ($a = 1; $a <= 12; $a++)
							{
								if ($a < 10) {echo '<option value="month_0'.strval($a).'">'.$monthnames[$a-1].'</option>';}
								else {echo '<option value="month_'.strval($a).'">'.$monthnames[$a-1].'</option>';}
							}
							
						?>
					</select>
					<select name="birth_day">
						<?php
							for ($a = 1; $a <= 31; $a++)
							{
								if ($a < 10) {echo '<option value="day_0'.strval($a).'">'.strval($a).'</option>';}
								else {echo '<option value="day_'.strval($a).'">'.strval($a).'</option>';}
							}							
						?>
					</select>
					<select name="birth_year">
						<?php
							for ($a = intval(date("Y")); $a > intval(date("Y")) - 120; $a--)
							{
								echo '<option value="year_'.strval($a).'">'.strval($a).'</option>';
							}
							
						?>
					
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
	
	
	</div>
		<?php
		
			$num_tags = 3;
			$num_ingredients = 3;
			$num_directions = 3;
		
		?>

				<script>
					function clickAddTag()
					{
												
						c = document.getElementsByClassName("tag").length;
						
						if (c)
						{
							var newTag = document.getElementById("tag_"+(c-1));

						}
						else 
						{
							var newTag = document.getElementById("starttags");
						}
						newTag.insertAdjacentHTML('afterend', addTag(c));							
					}
					
					function clickRemoveTag()
					{
						c = (document.getElementsByClassName("tag").length)-1;


						if (c > -1)
						{
							document.getElementById("tag_"+c).remove();
						}
						else
						{
							alert("There are no tags left to remove!");
						}
					}
					
					function clickAddIngredient()
					{
						c = document.getElementsByClassName("ingredient_group").length; //https://stackoverflow.com/questions/12237529/count-how-many-elements-in-a-div
						
						if (c)
						{
							var newIngredient = document.getElementById("ingredient_"+(c-1));
						}
						else
						{
							var newIngredient = document.getElementById("start_ingredients");
						}
						newIngredient.insertAdjacentHTML('afterend', addIngredient(c));		//Also from Stack Overflow
						document.getElementById("ingredient_"+c).focus();
						
					}
					
					function clickRemoveIngredient()
					{
						c = (document.getElementsByClassName("ingredient_group").length) - 1;
						
						if (c > -1)
						{
							document.getElementById("ingredient_"+c).remove();
							document.getElementById("ingredient_"+(c-1)).focus();

						}
						else
						{
							alert("There are no ingredients left to remove!");
						}
					}
				
					function clickAddDirection()
					{
						c = document.getElementsByClassName("direction").length;
						
						if (c)
					    {
							var newDirection = document.getElementById("direction_"+(c-1));
						}
						else
						{
							var newDirection = document.getElementById("start_directions");
						}
						newDirection.insertAdjacentHTML('afterend', addDirection(c));
						document.getElementById("direction_"+c).focus();
					
					}
					
					function clickRemoveDirection()
					{
						c = (document.getElementsByClassName("direction").length) - 1;
						
						if (c > -1)
						{
							document.getElementById("direction_"+c).remove();
							document.getElementById("direction_"+(c-1)).focus();

						}
						else
						{
							alert("There are no directions left to remove!");
						}
					}
				
				
				</script>
				
				
				
	<div id="recipeModal" class="recipeModalClass">			

		<form class="defForm" action="recipe_process.php" method="post">
		
			<div class="container">
				
				<div class="signupTitle">Insert Recipe</div>
				<span onclick="document.getElementById('recipeModal').style.display='none'; document.getElementById('all_else').style.display='block'; document.getElementById('plus_button_id').style.display='flex'" class="close" title="Close Recipe Modal">&times;</span>
			
			</div>
			
			
			<div class="ingContainer">
				<input class="hiddenInput" name="formType" value="insert_recipe"> 
				
				<label for="title"><b>Title</b></label>
				<input class="redo" type="text" placeholder="Enter Title" name="title" required>


				<label for="tags"><b>Tags</b></label><br>




				<?php
				
					$stmt = $db->prepare("SELECT * FROM `tags`");
					//$stmt->bind_param("s", $username); // "s" means the variable is a string  (No need to bind parameters, there are no variables in the query)
					$stmt->execute();

					// Get the result
					$result = $stmt->get_result();
					
					
					$tags = [];
					$tag_ids = [];
					while ($row = $result->fetch_assoc())
					{
						array_push($tags, $row['name']);
						$tag_ids[$row['name']] = $row['id'];
					}
					
					sort($tags);

				?>

				<script>
					function addTag(c)
					{
						
						<?php
							
							echo 'return "<div class=\"tag\" id=\"tag_" + c.toString() + "\"><select name=\"tag" + c.toString() + "\">';
							
							for ($b = 0; $b < sizeof($tags); $b++)
							{
								echo '<option value=\"'.$tags[$b].'\">'.$tags[$b].'</option>';
							}
							
							
							echo '</select><br>";';

							
						
						?>

					}
				
				</script>
				
				<?php


					echo '<div id="starttags"></div>';
							
					for ($a = 0; $a < $num_tags; $a++)
					{
						echo '<div class="tag" id="tag_'.$a.'"><select name="tag'.$a.'">';
						
						for ($b = 0; $b < sizeof($tags); $b++)
						{
							echo '<option value="'.$tags[$b].'">'.$tags[$b].'</option>';
						}
						
						echo '</select><br></div>';
					}
				
					echo '<br><div class="alter_buttons"><a class="alter_button" onclick="clickAddTag(); return false">Add Tag</a>  <a class="alter_button" onclick="clickRemoveTag(); return false">Delete Tag</a></div><br><br>';
				
			
				?>


				<label for="original_author"><b>Original Author</b></label>
				<input type="text" class="redo" name="original_author">
				<br><br>

				<label for="prep_time"><b>Prep Time</b></label>
				<select name="prep_time">
					
					<?php
					
					for ($a = 5; $a <= 120; $a += 5)
					{
						echo '<option value="'.$a.'_mins">'.$a.'</option>';
					}
					
					?>
					
				
				</select><label for="minutes"> minutes</label><br>
				
				<label for="serves"><b>Serves</b></label>
				
				<select name="serves">

					<?php
					
					for ($a = 1; $a <= 50; $a ++)
					{
						echo '<option value="'.$a.'_people">'.$a.'</option>';
					}
					
					?>
				
				</select><label for="people"> people</label><br><br>
				
				
				<script>
					//Add Ingredients Javascript.
					
					function addIngredient(c)
					{
						return "<div class=\"ingredient_group\" id=\"ingredient_"+c.toString()+"\"><input type=\"text\" class=\"ingredient_quantity\" name=\"ingredient_" + c.toString() + "_quantity\"><input type=\"text\" class=\"ingredient_unit\" name=\"ingredient_" + c.toString() + "_unit\"><input type=\"text\" class=\"ingredient_name\" name=\"ingredient_" + c.toString() + "_name\"></div>";
					}
				
				
				</script>


				<label for="ingredients"><b>Ingredients</b><label><br><br>
				<div class="ingredients_labels">
					<div class="quantity_label">Quantity</div>
					<div class="unit_label">Unit</div>
					<div class="name_label">Name</div><br>
				</div>
				

				<?php
				echo '<div id="start_ingredients"></div>';
				
				for ($a = 0; $a < $num_ingredients; $a++)
				{
					echo '<div class="ingredient_group" id="ingredient_'.$a.'">';
					echo '<input type="text" class="ingredient_quantity" name="ingredient_'.$a.'_quantity">';
					echo '<input type="text" class="ingredient_unit" name="ingredient_'.$a.'_unit">';
					echo '<input type="text" class="ingredient_name" name="ingredient_'.$a.'_name">';
					echo '</div>';
					
				}
				
				
				?>
				<br>
				<div class="alter_buttons">
				<a class="alter_button" onclick="clickAddIngredient(); return false">Add Ingredient</a>  <a class="alter_button" onclick="clickRemoveIngredient(); return false">Delete Ingredient</a>
				</div>
				<br><br>
				
				<script>
				//Add Directions Javascript.
				
					function addDirection(c)
					{
						return "<div class=\"direction\" id=\"direction_"+ c+ "\">"+(c+1)+". <input class=\"redo\" type=\"text\" name=\"direction_"+c+"\"><br></div>";
					}
				
				</script>
				
				
				
				<label for="directions"><b>Directions</b></label><br><br>
				
				<?php
				
				echo '<div id="start_directions"></div>';
				
				for ($a = 0; $a < $num_directions; $a++)
				{
					echo '<div class="direction" id="direction_'.$a.'">';
					echo strval($a+1).'. <input class="redo" type="text" name="direction_'.$a.'">';
					echo '<br></div>';
					
				}
				?>
				<br>
				<div class="alter_buttons">
				<a class="alter_button" onclick="clickAddDirection(); return false">Add Direction</a>  <a class="alter_button" onclick="clickRemoveDirection(); return false">Delete Direction</a>
				</div>
				<br><br>
				
				<button class="longButton" type="submit">Submit Recipe</button>
				
				
				
				
				
				
			</div>
		
		</form>
	
	</div>

