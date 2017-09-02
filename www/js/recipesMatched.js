

var ingredientInput = document.getElementById('keyword');
var ingredientSuggestions = document.getElementById('specializedList');

$('#noscriptSpecializedContainer li, #finalizedContainer li').click(recipesMatched);

function recipesMatched() {
	$.post( "js/autocomplete.php", { recipesRequested: "bla-bla!" })
	  .done(function( data ) {
	    console.log( "Data Loaded: " + data );
	  });
}