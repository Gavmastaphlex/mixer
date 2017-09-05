$(document).ready(function(){

	$( "#clear-mix-it-up" ).click(function( event ) {

  		event.preventDefault();

		$("#finalizedContainer .ingredient").each(function(){

			$("#ingredientList li").each(function(){
				$(this).css('display', 'block');
			})

			if($('#basic').is(':checked') == true) {
				$("#basic").prop("checked", false);
			}

			$('#mix-it-up').removeClass('red-button').addClass('grey-button');
			$('#mix-it-up').attr('title', 'No recipes matched');

			var element = $(this);
			var added = false;
			var targetList = $('#ingredientList');

			$(this).fadeOut("fast", function() {

		        $(".ingredient", targetList).each(function(){

		            if ($(this).text() > $(element).text()) {
		                $(element).insertBefore($(this)).fadeIn("fast");
		                added = true;
		                return false;
		            }

		        });

		        if(!added) $(element).appendTo($(targetList)).fadeIn("fast");

		    });

		});

		$.post( "js/autocomplete.php", { mixItUpReset: "true" })
		.done(function( data ) {
			$('#mix-it-up').removeAttr('data-badge');
			
		})
	});

	$("#ingredientListContainer").on("click", ".ingredient", function(){

		var ingredientName = $(this).html();
	    var ingredientID = $(this).attr('id').split('-');
		var element = $(this);
		var added = false;
		var targetList = $('#finalizedListContainer');

		$(this).fadeOut("fast", function() {
        $(".ingredient", targetList).each(function(){
	            if ($(this).text() > $(element).text()) {
	                $(element).insertBefore($(this)).fadeIn("fast");
	                added = true;
	                return false;
	            }
	        });
	        if(!added) $(element).appendTo($(targetList)).fadeIn("fast");
	    });

	    if($(this).hasClass('basic')) {
	    	var basicIngredient = 'yes';
	    } else {
	    	var basicIngredient = 'no';
	    }

	    $.post("js/routes/mix-it-up.php", { "ingredientName": ingredientName, "ingredientID": ingredientID[1], "basic": basicIngredient } ).done(function() {
		  	$.post( "js/autocomplete.php", { recipesRequested: "true" })
			  .done(function( data ) {
			  	var splitData = data.split('"');
			  	var filteredData = splitData.filter(function(v){return v!==''});

			  	if(filteredData.length != $('#mix-it-up').attr('data-badge')) {

			  		if(filteredData.length > 0) {
			  			$('#mix-it-up').attr('data-badge', filteredData.length);
						$('#mix-it-up').removeClass('grey-button').addClass('red-button');


				  		if(filteredData.length == 1) {
				  			new Noty({
							    text: 'You\'ve matched 1 recipe!' ,
							    theme: 'mint',
							    timeout: 5000,
							    progressBar: true

							}).show();
							$('#mix-it-up').attr('title', 'You\'ve matched 1 recipe!');
				  		} else {
				  			new Noty({
							    text: 'You\'ve matched ' + filteredData.length + ' recipes!' ,
							    theme: 'mint',
							    timeout: 5000,
							    progressBar: true

							}).show();
							$('#mix-it-up').attr('title', 'You\'ve matched ' + filteredData.length + ' recipes!');
				  		}

				  	} else {
				  		$('#mix-it-up').removeAttr('data-badge');
				  		$('#mix-it-up').removeClass('red-button').addClass('grey-button');
				  		$('#mix-it-up').attr('title', 'No recipes matched');
				  	}

			  	}
			  	
			  });
		  });;

	    $(this).remove();

	})


	$("#finalizedListContainer").on("click", ".ingredient", function(){

		$("#ingredientList li").each(function(){
				$(this).css('display', 'block');
			})

			if($('#basic').is(':checked') == true) {
				$("#basic").prop("checked", false);
			}

		var ingredientID = $(this).attr('id').split('-');
		var element = $(this);
		var added = false;
		var targetList = $('#ingredientList');

		$(this).fadeOut("fast", function() {
        $(".ingredient", targetList).each(function(){
	            if ($(this).text() > $(element).text()) {
	                $(element).insertBefore($(this)).fadeIn("fast");
	                added = true;
	                return false;
	            }
	        });
	        if(!added) $(element).appendTo($(targetList)).fadeIn("fast");
	    });

		
	    $.post("js/routes/mix-it-up.php", { "removeFinalized": "true", "ingredientID": ingredientID[1] } ).done(function() {
		  	$.post( "js/autocomplete.php", { recipesRequested: "true" })
			  .done(function( data ) {
			  	var splitData = data.split('"');
			  	var filteredData = splitData.filter(function(v){return v!==''});

			  	if(filteredData.length != $('#mix-it-up').attr('data-badge')) {

			  		if(filteredData.length > 0) {
			  			$('#mix-it-up').attr('data-badge', filteredData.length);
						$('#mix-it-up').removeClass('grey-button').addClass('red-button');

				  		if(filteredData.length == 1) {
				  			new Noty({
							    text: 'You\'ve matched 1 recipe!' ,
							    theme: 'mint',
							    timeout: 5000,
							    progressBar: true

							}).show();
							$('#mix-it-up').attr('title', 'You\'ve matched 1 recipe!');
				  		} else {
				  			new Noty({
							    text: 'You\'ve matched ' + filteredData.length + ' recipes!' ,
							    theme: 'mint',
							    timeout: 5000,
							    progressBar: true

							}).show();
							$('#mix-it-up').attr('title', 'You\'ve matched ' + filteredData.length + ' recipes!');
				  		}

				  	} else {
			  		$('#mix-it-up').removeAttr('data-badge');
			  		$('#mix-it-up').removeClass('red-button').addClass('grey-button');
			  		$('#mix-it-up').attr('title', 'No recipes matched');
			  		}

			  	}
			  });
		  });;

	});
    	    
	$('#basic').click(function(){

		$('#filter').val('');

		$("#ingredientList li").each(function(){
			$(this).css('display', 'block');
		})

		if($(this).is(':checked') == true) {
			$("#ingredientList li").each(function(){
				if(!$(this).hasClass('basic')) {
					$(this).css('display', 'none');
				}
			})
		} 
	})


	$('#mix-it-up').click(function() {
		event.preventDefault(); 

		if($(this).hasClass('red-button')) {
			$('#mixItUp2').click();
		}

	    
	})

	$('#filter').on('keyup', searchFunction);
	$('#filter').on('focus', searchFunction);

	function searchFunction() {


		if($('#basic').is(':checked') == true) {
			$("#basic").prop("checked", false);
		}

		var searchVal = $('#filter').val().toLowerCase();

		if(searchVal != '') {
			$("#ingredientList li").each(function() {

				if($('#basic').is(':checked') == false){
					$(this).css( "display", "block" );
				}

				if($(this).html().toLowerCase().indexOf(searchVal) == -1){	
					$(this).css( "display", "none" );
				}

			});

		} else {
			$("#ingredientList li").each(function() {
				$(this).css( "display", "block" );
			});
		}

	}
	
	


})