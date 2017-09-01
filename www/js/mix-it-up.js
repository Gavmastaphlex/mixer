$(document).ready(function(){

	var ingredientList = $('#noscriptSpecializedContainer li');

	$('#noscriptSpecializedContainer li').click(function(){
		if(!$(this).hasClass('selected')) {
			$(this).clone().appendTo( "#finalizedContainer ul" );
			// $(this).addClass('selected');
		}

	    var ingredientName = $(this).html();
	    var ingredientID = $(this).attr('id').split('-')

	    $.post("js/routes/mix-it-up.php", { "ingredientName": ingredientName, "ingredientID": ingredientID[1] } );

	    $(this).remove();

	})


	$("#finalizedContainer").on("click", ".ingredient", function(){

		$(this).clone().appendTo( "#noscriptSpecializedContainer ul" );

	    var ingredientID = $(this).attr('id').split('-');
	    
	    $.post("js/routes/mix-it-up.php", { "removeFinalized": "true", "ingredientID": ingredientID[1] } );

	    $(this).remove();


	});
    	    

	$('#basic').click(function(){
		if($(this).is(':checked') == true) {
			ingredientList.each(function(){
				if(!$(this).hasClass('basic')) {
					$(this).css('display', 'none');
				}
			})

		} else {
			searchFunction();
		}
	})


	$('#filter').on('keyup', searchFunction);


	function searchFunction() {

		var searchVal = $('#filter').val().toLowerCase();

		if(searchVal != '') {
			ingredientList.each(function() {

				$(this).css( "display", "block" );


				if($(this).html().toLowerCase().indexOf(searchVal) == -1){	
					$(this).css( "display", "none" );
				}

			});

		} else {
			ingredientList.css('display', 'block');
		}

	}


})

// function submitIngredients(ingredientID, ingredientName) {
// 	//instantiate the XHR
// 	var xhr = getXHR();
// 	//Define the url
// 	var url = 'js/routes/mix-it-up.php?ingredientID=' + ingredientID + '&ingredientName=' + ingredientName;
// 	//console.log(url);
// 	//open the connection
// 	xhr.open('GET', url, true);
// 	xhr.send(null);
// 	//tap into the onreadystate paramter
// 	xhr.onreadystatechange = function() {
// 		if(xhr.readyState == 4 && xhr.status == 200) {
// 		//output suggestions
// 		//ingredientSuggestions.innerHTML = xhr.responseText;
// 			if(xhr.responseText != false) {
// 			var suggestions = JSON.parse(xhr.responseText);
// 			$('#specializedList').html('');
// 			$.each(suggestions, function(i, item) {
// 			// Create elements
// 			var li = $('<li></li>');
// 			var form = $('<form></form>').attr('method','post').attr('action', 'index.php?page=home#ingredients');
// 			var input1 = $('<input />').attr('type','hidden').attr('name', 'selectedIngredientID').attr('value', item.id);
// 			var input2 = $('<input />').attr('type','submit').attr('name', 'selectedIngredient').attr('value', item.name).addClass('homeIngredients');
// 			// Append
// 			$(form).append(input1);
// 			$(form).append(input2);
// 			$(li).append(form).click(addIngredient);
// 			$('#specializedList').append(li);
// 			});
// 			//get the divs
// 			} else {
// 				$('#specializedList').html('No suggestions');
// 			}
// 		}
// 	}
// }