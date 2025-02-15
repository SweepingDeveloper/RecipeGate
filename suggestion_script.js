
// Getting all required elements
const searchWrapper = document.querySelector(".search_input");
const inputBox = searchWrapper.querySelector("input");
const suggBox = document.querySelector(".autocomplete_box");
const everywhereElse = document.querySelector("main");

// If user presses and key and releases it
inputBox.onkeyup = (e) => {
	let userData = e.target.value;  // User entered data.
	let emptyArray = [];
	
	if (userData)
	{
		emptyArray = suggestions.filter((data)=>{
			// Filtering array value and user char to lowercase and return only those words/sentences which start with the prompt
			return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
		});
		emptyArray = emptyArray.map((userData)=>{
			return data = '<li>'+userData+'</li>';
		});
		
		
		console.log(emptyArray);
		searchWrapper.classList.add("active"); // Show autocomplete box.
		showSuggestions(emptyArray);
		let allList = suggBox.querySelectorAll("li");
		for (let i = 0; i < allList.length; i++)
		{
			// Adding onclick attribute in all li tag
			allList[i].setAttribute("onclick", "select(this)");
			
	
		}
		
		
	}
	else
	{
		searchWrapper.classList.remove("active"); // Hide autocomplete box.
	}
	
	// https://stackoverflow.com/questions/7060750/detect-the-enter-key-in-a-text-input-field
	if (e.key === 'Enter' || e.keyCode === 13)
	{
		processSearch(inputBox.value);
	}
	
}

inputBox.onclick = function()
{
	inputBox.select();
}





function select(element)
{
	let selectUserData = element.textContent;
	inputBox.value = selectUserData; // Passing the user selected list item data in text field
	searchWrapper.classList.remove("active"); // Hide autocomplete box.
}

document.getElementById("searchButton").addEventListener("click", processSearch(inputBox.value));


function processSearch(query)
{
	console.log("Processing search...");
	$.ajax({
		url: "generate_recipes.php?generation_mode=1&query="+query,
		success: function(result) {
			if (result == "No recipes found.")
			{
				searchWrapper.classList.remove("active"); // Hide autocomplete box.
			}
			else
			{
				$("#all_else").html(result);
				searchWrapper.classList.remove("active"); // Hide autocomplete box.
			}
		}
	});
}





function showSuggestions(list)
{
	let listData;
	if (!list.length)
	{
		userValue = inputBox.value;
		listData = '<li>' + userValue + '</li>';
	}
	else
	{
		listData = list.join('');
	}
	suggBox.innerHTML = listData;
}