//Get the relevant elements

		var keywordDiv = $('<div></div>').attr('id','specializedKeyword');
		var label = $('<label>Keyword:</label>').attr('for','keyword');
		var input = $('<input />').attr('type','text').attr('name', 'keyword').attr('id', 'keyword').attr('value', 'Enter Ingredient');
		// Append
		$(keywordDiv).append(label);
		$(keywordDiv).append(input);
		$('#specializedTypes').append(keywordDiv);

		var specializedDiv = $('<div></div>').attr('id','specializedContainer').addClass('ingredientsContainer');
		var specializedUl = $('<ul></ul>').attr('id','specializedList');
		
		$(specializedDiv).append(specializedUl);
		$('#specializedTypes').append(specializedDiv);


var ingredientInput = document.getElementById('keyword');
var ingredientSuggestions = document.getElementById('specializedList');

//when a key is pressed...(or key up - when the key is released)

$(function() {

    $('#keyword').focus(function() {

           $(this).val('');
      });

 });

ingredientInput.onkeyup = suggestIngredients;

function suggestIngredients() {
	//instantiate the XHR
	var xhr = getXHR();

	//Define the url
	var url = 'js/autocomplete.php?ingredient=' + ingredientInput.value;

	//console.log(url);

	//open the connection
	xhr.open('GET', url, true);
	xhr.send(null);

	//tap into the onreadystate paramter

					xhr.onreadystatechange = function() {
						if(xhr.readyState == 4 && xhr.status == 200) {
							//output suggestions
							//ingredientSuggestions.innerHTML = xhr.responseText;


							if(xhr.responseText != false) {

								var suggestions = JSON.parse(xhr.responseText);

								$('#specializedList').html('');
								$.each(suggestions, function(i, item) {
										// Create elements
										var li = $('<li></li>');
										var form = $('<form></form>').attr('method','post').attr('action', 'index.php?page=home#ingredients');
										var input1 = $('<input />').attr('type','hidden').attr('name', 'selectedIngredientID').attr('value', item.id);
										var input2 = $('<input />').attr('type','submit').attr('name', 'selectedIngredient').attr('value', item.name).addClass('homeIngredients');
										// Append
										$(form).append(input1);
										$(form).append(input2);
										$(li).append(form).click(addIngredient);
										$('#specializedList').append(li);
								});
								//get the divs

							} else {
								$('#specializedList').html('No suggestions');
							}


						}
				}
	
}

function addIngredient() {

	var item = $(this); //overwriting the previous click function

	var id = item.find('input[name="selectedIngredientID"]').val();
	
	var name = item.find('input[name="selectedIngredient"]').val();

	//console.log(name);
	
	$.post("index.php?page=home", {selectedIngredient: name, selectedIngredientID: id});

	var exists = false; 
	$('#finalizedContainer li').each(function(){
	  if ($(this).find('input[name="selectedIngredient"]').val() == name) {
	    exists = true;
	  	}
	});

	if (exists == false){
	item
  	item.clone().appendTo($("#finalizedContainer ul"));
  	var newItem = $('#finalizedContainer').find('input[name="selectedIngredient"]').attr('name');

	}
	
	return false;

};

		

		$("#finalizedContainer ul").on("click", "li", function() { //jquery selector
			var id = $(this).find('input[name="selectedIngredientID"]').val();	
			$.post("index.php?page=home", {removeFinalized: true, selectedIngredientID: id});			
			$(this).remove();
			return false;
		}) 

		$('#basicContainer ul').on("click", "li", function() {
			
			var item = $(this); //overwriting the previous click function

			var id = item.find('input[name="selectedIngredientID"]').val();
			
			var name = item.find('input[name="selectedIngredient"]').val();

			//console.log(name);
			
			$.post("index.php?page=home", {selectedIngredient: name, selectedIngredientID: id});

			var exists = false; 
			$('#finalizedContainer li').each(function(){
			  if ($(this).find('input[name="selectedIngredient"]').val() == name) {
			    exists = true;
			  	}
			});

			if (exists == false){
		  	item.clone().appendTo($("#finalizedContainer ul"));
			}


			return false;

		});


// This function will return us the XMLHttpRequest object
function getXHR() {
	var xhr = null;
	
	if(window.XMLHttpRequest) {
		// Most modern browsers
		xhr = new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		// IE6+
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	} else {
		alert('Your brower does not support AJAX!');
	}
	
	return xhr;
}