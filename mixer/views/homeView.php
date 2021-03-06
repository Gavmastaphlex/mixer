<?php

class HomeView extends View {
        
    protected function displayContent() {

    	session_start();

    	$html = '';

    	/*
		if a user presses the Delete Profile button from the User Panel, then
		the delete user function is being called from the database to wipe all
		traces of that user, as well as unsetting all the session information
		that would have held that users information right up to the point of
		pressing the "Delete User Button"
    	*/
      	if($_POST['deleteProfile']) {
    		$deleteUser = $this -> model -> deleteUser($_SESSION['userName']);
    
    		if($deleteUser == true) {
    		unset($_SESSION['userName']);
            unset($_SESSION['userAccess']);
            unset($_SESSION['userID']);
            unset($_SESSION['userFirstName']);
            unset($_SESSION['liveRecipes']);
    		}

    	}

    	if($_POST['checkSession']) {
    		echo '<pre>';
			print_r($_SESSION);
			echo '</pre>'; 
    	}

		

		if($_GET['mixerReset']) {
			unset($_SESSION['finalized']);
			unset($_SESSION['mixItUp']);
			unset($_SESSION['incomplete']);
			unset($_SESSION['incompleteIngredients']);
			unset($_SESSION['allRecipes']);
            unset($_SESSION['lastPage']);
            unset($_SESSION['pageNum']);
            unset($_SESSION['liveRecipes']);
		}

        $html .= '<!--h2>'.$this -> pageInfo['pageHeading'].'</h2-->'."\n";

        $html .= '<!--form method="post" action="index.php?page=home#ingredients" enctype="multipart/form-data">'."\n";
        $html .= '<input type="hidden" name="checkSession" value="checkSession" />'."\n";
        $html .= '<input type="submit" class="homeIngredients" name="submit"" />'."\n";
        $html .= '</form-->'."\n";


        $html .= '<!--h3>The Dynamic Recipe Generator</h3-->'."\n";
		$html .= '<div id="ingredientBoxes">'."\n";
		$html .= '<div id="ingredients">'."\n";
		$html .= '<div id="ingredientContainer" class="ingredientTypes">'."\n";
		$html .= '<h4>Ingredients</h4>'."\n";

		$html .= '<label for="filter" id="filter-label">Filter:</label>'."\n";
		$html .= '<input type="text" name="filter" id="filter" placeholder="Enter ingredient">'."\n";

		$html .= '<label id="basic-label" for="basic">Basic ingredients:</label>'."\n";
		$html .= '<input type="checkbox" name="basic" id="basic">'."\n";

		$html .= '<div id="ingredientListContainer">'."\n";
		$html .= '<ul id="ingredientList">'."\n";
		
		$this -> ingredients = $this -> model -> getAllIngredients();
        
        if(is_array($this -> ingredients)) {
            $html .= $this -> displayAllIngredients();
        } else {
            $html .= '<p>No ingredients available.</p>';
        }

		$html .= '</ul>'."\n";
		$html .= '</div>'."\n";

		// $html .= '</noscript>'."\n";

		$html .= '</div>'."\n";
		$html .= '<div class="clearDiv"></div>'."\n";
		$html .= '<p><strong>Tip - Click on an ingredient to copy it to the finalized section.</strong></p>'."\n";
		$html .= '</div>'."\n";
		$html .= '<div id="finalized">'."\n";
		$html .= '<h4>Finalized Ingredients</h4>'."\n";
		$html .= '<p><strong>Tip - Click on any ingredient you want to remove</strong></p>'."\n";
		$html .= '<div id="finalizedContainer">'."\n";
		$html .= '<ul id="finalizedListContainer">'."\n";

		if(isset($_SESSION['finalized'])) {
            $html .= $this -> displayfinalizedIngredients();
        }

        $html .= '</ul>'."\n";
		$html .= '</div>'."\n";
		$html .= '<form method="post" action="index.php?page=recipes#content" >'."\n";

		$html .= '<input type="hidden" value="mixItUp" id="mixItUp" name="mixItUp">'."\n";

		$html .= '<div id="mixer-button-container">'."\n";

		$html .= '<a href="#" id="clear-mix-it-up" class="blue-button" title="Clear search">Clear Search</a>'."\n";
		$html .= '<a href="#" id="mix-it-up" class="badge1 ';

		if(isset($_SESSION['liveRecipes'])) {

			$html .= 'red-button" data-badge="'.count($_SESSION['liveRecipes']).'"';

	  	} else {
	  		$html .= 'grey-button" title="No recipes matched"';
	  	}

		$html .= '>Mix it Up</a>'."\n";

		$html .= '<input type="submit" name="mixItUp" id="mixItUp2" value="Mix It Up!" />'."\n";

		$html .= '</div>'."\n";

		$html .= '</form>'."\n";

		if($_GET['previousSearch'] == true) {
	        	
        	unset($_SESSION['mixItUp']);
        	unset($_SESSION['incomplete']);
        	unset($_SESSION['incompleteIngredients']);

        }
		
		$html .= '<div id="recipe-summary-box">'."\n";
		$html .= '<p id="recipe-summary" style="text-align; font-size:15px; padding:20px;">'."\n";

		$html .= '<p>'."\n";

		$html .= '</div>'."\n";

		$html .= '</form>'."\n";

		$html .= '</div>'."\n";
		$html .= '</div>'."\n";
        
        $html .= '<script type="text/javascript" src="js/mix-it-up.js"></script>'."\n";
                
        return $html;        
    }


    private function displayAllIngredients() {
        
	        foreach($this -> ingredients as $ingredient) {

	        	// if($_GET['previousSearch'] == true) {

		        	if($ingredient['ingredientID'] != $_SESSION['finalized'][$ingredient['ingredientID']]['ingredientID']) {

				    	$html .= '<li id="ingredient-'.$ingredient['ingredientID'].'" class="ingredient';

			        	if ($ingredient['basicIngredient'] == 1) {
			        		$html .= ' basic';
			        	}

			        	$html .= '">'.$ingredient['ingredientName'].'</li>'."\n";

		        	}

	        	// }
	        }   

        return $html;       
        
    }

    private function displayBasicIngredients() {
        
        foreach($this -> basicIngredients as $basicIngredient) {
        	$html .= '<li>'."\n";
            $html .= '<form method="post" action="index.php?page=home#ingredients" enctype="multipart/form-data">'."\n";
            $html .= '<input type="hidden" name="selectedIngredientID" value="'.$basicIngredient['ingredientID'].'" />'."\n";
            $html .= '<input type="submit" class="homeIngredients" name="selectedIngredient" value="'.$basicIngredient['ingredientName'].'" />'."\n";
            $html .= '</form>'."\n";
            $html .= '</li>'."\n";
        }

        return $html;       
        
    }

    private function displaySpecializedIngredients() {
        
	        foreach($this -> specializedIngredients as $specializedIngredient) {
	        	$html .= '<li>'."\n";
	            $html .= '<form method="post" action="index.php?page=home#ingredients" enctype="multipart/form-data">'."\n";
	            $html .= '<input type="hidden" name="selectedIngredientID" value="'.$specializedIngredient['ingredientID'].'" />'."\n";
	            $html .= '<input type="submit" class="homeIngredients" name="selectedIngredient" value="'.$specializedIngredient['ingredientName'].'" />'."\n";
	            $html .= '</form>'."\n";
	            $html .= '</li>'."\n";
	        }   

        return $html;       
        
    }

    private function displayFinalizedIngredients() {

    		foreach($_SESSION['finalized'] as $finalizedIngredient) {
	        	$html .= '<li id="ingredient-'.$finalizedIngredient['ingredientID'].'" class="ingredient';

	        	if($finalizedIngredient['basic'] == 'yes') {
	        		$html .= ' basic';
	        	}
	        	
	        	$html .= '">'.$finalizedIngredient['ingredientName'].'</li>'."\n";
	        }


        	// foreach($_SESSION['finalized'] as $finalizedIngredient) {
	        // 	$html .= '<li>'."\n";

	        //     $html .= '<form method="post" action="index.php?page=home#ingredients" enctype="multipart/form-data">'."\n";
	        //     $html .= '<input type="hidden" name="selectedIngredientID" value="'.$finalizedIngredient['ingredientID'].'" />'."\n";

	        //     if($_GET['previousSearch'] == true){
	        //     	$html .= '<input type="submit" class="homeIngredients" name="selectedIngredient" value="'.$finalizedIngredient['ingredientName'].'" />'."\n";
	        //     } else {
	        //     	$html .= '<input type="submit" class="homeIngredients" name="removeFinalized" value="'.$finalizedIngredient['ingredientName'].'" />'."\n";
	        //     }

	            
	        //     $html .= '</form>'."\n";

	        //     $html .= '</li>'."\n";
	        // }


        return $html;       
        
    }
            
        
}


?>