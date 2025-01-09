<?php

	session_start();
	require "mysql_login.php";
	require "encrypt_2025.php";
	error_reporting(0);

	$recipe_count = 0;



	if (isset($_SESSION['username']))
	{
		echo '<p align="center"><div id="plus_button_id" class="plus_button" onclick="document.getElementById(\'recipeModal\').style.display=\'block\'; document.getElementById(\'plus_button_id\').style.display=\'none\'; document.getElementById(\'all_else\').style.display=\'none\'">+</div></p>';
		echo '<p align="center"><div class="new_recipe_placeholder"><a class="new_recipe_button" onclick="document.getElementById(\'recipeModal\').style.display=\'block\'; document.getElementById(\'all_else\').style.display=\'none\'">New Recipe</a></div></p><br>';
	}

	if (empty($_GET['start_at'])) { $start_at = 0; } else { $start_at = $_GET['start_at']; }

	if (empty($_GET['generation_mode'])) { $generation_mode = 0; } else { $generation_mode = $_GET['generation_mode']; }
	
	echo '<script>console.log("Generation Mode: '.$generation_mode.'");</script>';
	// 0: Show all recipes.
	// 1: Show recipes based on search.
	// 2: Show recipes based on suggested recipe algorithm.
	// 3: Show recipes from specific profile.
	
	if (empty($_GET['profile_id'])) { $profile_id = 0; } else { $profile_id = $_GET['profile_id']; }
	
	echo '<script>console.log("Profile ID: '.$profile_id.'");</script>';


	if (empty($_GET['query'])) { $query = 'ALL'; } else { $query = $_GET['query']; }

	echo '<script>console.log("Query: '.$query.'");</script>';

	if ($start_at >= 20)
	{
		echo '<a class="new_recipe_button" href="index.php?start_at='.strval($start_at - 20).'">Previous 20 Recipes</a>';
	}
	
	/* Queries to search for all types:
	
	   Title: SELECT id FROM recipes WHERE title LIKE '%???%';
	   
	   
	   Tag: SELECT tag_list.recipe_id, tags.`id`, tags.`name` FROM tag_list INNER JOIN tags ON tag_list.tag_id = tags.id WHERE tags.name LIKE '%???%';

	  
	   Ingredient: SELECT DISTINCT recipe_id FROM ingredients WHERE name LIKE '%Chi%';
	
	   Also:  Add some checkboxes for "Search by Title", "Search by Tag", "Search by Ingredient"
	
	*/
	
	
	

	switch ($generation_mode)
	{
		case 0:  // Show all recipes.
			$recipe_query = 'SELECT * FROM '.$db_name.'.`recipes` ORDER BY `id` DESC LIMIT '.strval($start_at).', '.strval($start_at + 20).';';
			break;
		case 1: // Show recipes based on search.
			if ($query == 'ALL')
			{
				$recipe_query = 'SELECT * FROM '.$db_name.'.`recipes` ORDER BY `id` DESC LIMIT '.strval($start_at).', '.strval($start_at + 20).';';
			} 
			else
			{
				$recipe_id_matches = [];
				$formatted_query_text = '%'.$query.'%';
				
				// Title Search.
				$stmt = $db->prepare("SELECT id FROM recipes WHERE title LIKE ?;");
				$stmt->bind_param("s", $formatted_query_text);
				$stmt->execute();
				$res = $stmt->get_result();
				
				while ($row = $res->fetch_assoc())
				{
					
					array_push($recipe_id_matches, $row['id']);
				}
				
				// Tag Search.
				$stmt = $db->prepare("SELECT `test`.tag_list.recipe_id, tags.`id`, tags.`name` FROM tag_list INNER JOIN tags ON tag_list.tag_id = tags.id WHERE tags.name LIKE ?;");
				$stmt->bind_param("s", $formatted_query_text);
				$stmt->execute();
				$res = $stmt->get_result();

				//echo '<script>console.log("Tag error: '.mysqli_error($db).'");</script>';				

				while ($row = $res->fetch_assoc())
				{
					array_push($recipe_id_matches, $row['recipe_id']);
				}
				
				// Ingredient Search.
				$stmt = $db->prepare("SELECT DISTINCT recipe_id FROM ingredients WHERE name LIKE ?;");
				$stmt->bind_param("s", $formatted_query_text);
				$stmt->execute();
				$res = $stmt->get_result();

				//echo '<script>console.log("Ingredient error: '.mysqli_error($db).'");</script>';				
				
				while ($row = $res->fetch_assoc())
				{
					array_push($recipe_id_matches, $row['recipe_id']);
				}
								
				
				
				if (sizeof($recipe_id_matches) > 0)
				{
					$recipe_query = 'SELECT * FROM '.$db_name.'.`recipes` WHERE `id` IN ('.$recipe_id_matches[0];
					
					if (sizeof($recipe_id_matches) > 1)
					{
						for ($m = 1; $m < sizeof($recipe_id_matches); $m++)
						{
							if ($recipe_id_matches[$m] > 0) { $recipe_query .= ', ' . $recipe_id_matches[$m]; }
						}
					}
					$recipe_query .= ') ORDER BY `id` DESC LIMIT '.strval($start_at).', '.strval($start_at + 20).';';
				}
				else 
				{
					echo "No recipes found.";
					$recipe_query = 0;
				}
				echo '<script>console.log("'.$recipe_query.'")</script>';
				
			}
			break;
		case 2:  // Show recipes based on suggested recipe algorithm.
			$recipe_query = 'SELECT * FROM '.$db_name.'.`recipes` ORDER BY `id` DESC LIMIT '.strval($start_at).', '.strval($start_at + 20).';';
			break;
		case 3:  // Show recipes from specific profile.
			$recipe_query = 'SELECT * FROM '.$db_name.'.`recipes` WHERE `user_id` = "'.$profile_id.'" ORDER BY `id` DESC LIMIT '.strval($start_at).', '.strval($start_at + 20).';';
			break;
		
	}
	
	if ($recipe_query != 0) { $recipe_result = mysqli_query($db, $recipe_query) or die ('Error querying recipes.'); } 
	 

	// BEGIN RECIPE GENERATION LOOP.
	while ($recipe_row = mysqli_fetch_array($recipe_result) and $recipe_query != 0)
	{
		//Start of individual recipe.
		echo '<div class="ind_recipe">
					<div class="recipe_title">
							<div class="recipe_title_label">'.$recipe_row['title'];
							
							
							
							
							echo '<span onclick="expandRecipe('.$recipe_row['id'].')" class="expand" title="Expand Recipe">&blacktriangledown;</span></div>
							

							';

		//Author title.
		$author_query = 'SELECT * FROM '.$db_name.'.`login` WHERE `id` = "'.$recipe_row['user_id'].'";';
		$author_result =  mysqli_query($db, $author_query) or die ('Error querying author.'); 
		$author_row = mysqli_fetch_array($author_result);

		if (empty($recipe_row['original_author']) or $recipe_row['original_author'] == '')
		{
			echo '<div class="author_title">by <a href="profile.php?profile_id='.$recipe_row['user_id'].'">'.encryptString($author_row['given_name']).'</a></div>';
		}
		else
		{
			echo '<div class="author_title">by '.$recipe_row['original_author'].'
			<br>submitted by <a href="profile.php?profile_id='.$recipe_row['user_id'].'">'.encryptString($author_row['given_name']).'</a></div>';
		}

		echo '<div class="edit_bar">';
		
		if (isset($_SESSION['username']) and ($recipe_row['user_id'] == $_SESSION['user_id']))
		{
			echo '<a class="edit_button"  href="new_recipe.php?message=edit&recipe_id='.$recipe_row['id'].'">Edit</a>';
		}
		
		echo '</div>';
		
		
		//Tags
		echo '<div class="tags">';
		$tag_query = 'SELECT * FROM '.$db_name.'.`tag_list` WHERE `recipe_id` = "'.$recipe_row['id'].'";';
		$tag_result = mysqli_query($db, $tag_query) or die ('Error querying tags.'); 
		
		while ($tag_row = mysqli_fetch_array($tag_result))
		{				
			$tag_name_query = 'SELECT * FROM '.$db_name.'.`tags` WHERE `id` = "'.$tag_row['tag_id'].'";';
			$tag_name_result = mysqli_query($db, $tag_name_query) or die ('Error querying tags.'); 
			$tag_name_row = mysqli_fetch_array($tag_name_result);

			echo '<span class="ind_tag" data-label="'.$tag_name_row['name'].'">'.$tag_name_row['name'].'</span>  ';

		}
		
		echo '</div>';
		
		
		
		echo '</div>';
		
		
		//echo '<div id="rest_of_recipe_'.$recipe_row['id'].'>';
		
		//Start of Ingredients and Directions section.
		echo '<div class="ingredients_directions" id="for_recipe_'.$recipe_row['id'].'">
				<div class="ingredients_label">Ingredients</div><br>
					<ul class="ingredients_list">';
		
		//Individual Ingredients
		
		$ingredients_query = 'SELECT * FROM '.$db_name.'.`ingredients` WHERE `recipe_id` = "'.$recipe_row['id'].'";';
		$ingredients_result = mysqli_query($db, $ingredients_query) or die ('Error querying ingredients.'); 
		
		while ($ingredients_row = mysqli_fetch_array($ingredients_result))
		{
			
			//This is my solution for getting rid of trailing zeroes in quantity numbers.
			$quantity_num = $ingredients_row['quantity'];
			if (floor($quantity_num) == $quantity_num) 
			{$quantity_num = intval($quantity_num);}
			else if (floor($quantity_num * 10) == ($quantity_num * 10))
			{$quantity_num = intval($quantity_num * 10) / 10;}
			else if (floor($quantity_num * 100) == ($quantity_num * 100))
			{$quantity_num = intval($quantity_num * 100) / 100;}
				
			
			
			echo '<li class="ind_ingredient" aria-label="'.$quantity_num.' '.$ingredients_row['type'].' of '.$ingredients_row['name'].'">';
			if ($quantity_num == 0)
			{
				echo '<span class="ind_quan"></span> ';
			}
			else
			{
				echo '<span class="ind_quan">'.$quantity_num.'</span> ';
			}
			
			echo '<span class="ind_quan">'.$ingredients_row['type'].'</span> ';
			echo '<span class="ind_quan">'.$ingredients_row['name'].'</span>';
			echo '</li>';
		}
		
		echo '</ul><br>';

		//Directions.
		
		echo '<div class="directions_label">Directions</div> <br>
				<ol class="directions_list">';
			
		//Individual Directions
		
		$directions_query = 'SELECT * FROM '.$db_name.'.`directions` WHERE `recipe_id` = "'.$recipe_row['id'].' ORDER BY `direction_num` ASC";';
		$directions_result = mysqli_query($db, $directions_query) or die ('Error querying directions.');
		
		while ($directions_row = mysqli_fetch_array($directions_result))
		{
			echo '<li class="ind_direction aria-label="'.$directions_row['direction'].'"><span class="s_ind_direction">'.$directions_row['direction'].'</span></li>';
		}
		


		echo '</ol>';
		
		// 								$("#lb_'.$recipe_row['id'].'").html(result);
		// Submit Like Javascript function.
		echo '
		<script>
			function submitLike(recipe_id) 
			{	

				$.ajax({
					url: "process_likes.php?id="+recipe_id,
					success: function(result) {
						if (result == 1)
						{
							alert("Like submitted!");
							window.location.href = "index.php";
						}							
						else
						{
							alert("Unlike submitted!");
							window.location.href = "index.php";
						}
						

						
					}
			
				});

			}
		
		
		</script>
		
		
		
		
		';

		
		
		
		echo '<div class="options_bar" id="options_'.$recipe_row['id'].'"><div class="last_modified"><i>Last modified '.$recipe_row['timestamp'].'</i></div><div class="like_flag" id="like_flag_'.$recipe_row['id'].'">';




		// Get Likes.
		$likes_query = 'SELECT COUNT(`recipe_id`) AS `like_count` FROM '.$db_name.'.`likes` WHERE `recipe_id` = "'.$recipe_row['id'].'";';
		$likes_result = mysqli_query($db, $likes_query) or die ('Error querying likes.');
		$likes_row = mysqli_fetch_array($likes_result);
		$num_likes = 0;
		if (empty($likes_row))
		{
			$num_likes = 0;
		}
		else
		{
			$num_likes = $likes_row['like_count'];
		}
		
		// Get Your Likes.
		$your_likes_query = 'SELECT * FROM '.$db_name.'.`likes` WHERE `user_id` = "'.$_SESSION['user_id'].'" AND `recipe_id` = "'.$recipe_row['id'].'";';
		$your_likes_result = mysqli_query($db, $your_likes_query) or die ('Error querying likes.');
		$your_likes_row = mysqli_fetch_array($your_likes_result);
		
		
		
		
		// If you liked a recipe, give the like button a green background.  Otherise, don't.
		if (isset($_SESSION['username']))
		{
			// text-indent idea from https://stackoverflow.com/users/1355315/abhitalks
			if (!empty($your_likes_row))
			{
				echo '<div id="lb_'.$recipe_row['id'].'"><span class="like_button" id="like_'.$recipe_row['id'].'" style="background-color: green" onclick="submitLike('.$recipe_row['id'].')">&#128079 '.$num_likes.'</span></div>';
			}
			else
			{
				echo '<div id="lb_'.$recipe_row['id'].'"><span class="like_button" id="like_'.$recipe_row['id'].'" style="background-color: none" onclick="submitLike('.$recipe_row['id'].')">&#128079 '.$num_likes.'</span></div>';
			}
		
		}
		
		
		
		// 
		
		
			if (isset($_SESSION['username']))
			{
				$complainer_id = $_SESSION['user_id'];
			}
			else
			{
				$complainer_id = 0;
			}
		
			echo '<a class="flag_button" id="flag_'.$recipe_row['id'].'" href="report.php?complainer_id='.$complainer_id.'&recipe_id='.$recipe_row['id'].'">&#9873</a></div></div>
			<div class="endofrecipe"></div>
			</div></div>';
			
			
			
			
		
		$recipe_count++;

	}
	// END RECIPE GENERATION LOOP.

	if ($recipe_count >= 20)
	{
		echo '<a class="new_recipe_button" href="index.php?start_at='.strval($start_at + 20).'">Next 20 Recipes</a>';
	}
		
?>