<?php

class EditRecipeView extends View {
        
    protected function displayContent() {

    	    	

        	$html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";

		        	if(isset($_GET['id'])) {
		            //get the record from database
		            $_SESSION['updateRecipe'] = true;
		            $_SESSION['newRecipe']['recipeID'] = $_GET['id'];
		            $this -> recipe = $this -> model -> getRecipeByID($_SESSION['newRecipe']['recipeID']);
	        	} 
		            if(is_array($this -> recipe)) {
		                extract($this -> recipe);
	            }

	            /*
			If $_POST['newIngredientNameButton'] is true, there is a user-inputted ingredient thats required
			for the recipe they're uploading. The clicking of the button triggers this code, which first checks
			to see if there is any values in the variable $_POST['newIngredientName']. If theres not then the user
			hadn't entered anything into the input before clicking the "Add New Ingredient" Button, and a validation
			message is generated informing them of this fact.

			If there is a value in $_POST['newIngredientName'], the preprogrammed ucfirst function is performed so that
			all the ingredients consistently have a starting capital letter, then a comparison is performed to make sure
			the user hasn't already added that newIngredient by checking the variable $_SESSION['newIngredient'][$newIngredientNameVal]
			(which is where the newIngredients are temporarily stored until the end finalizing of the recipe) - and if it
			finds a match then an appropriate validation message is generated informing the user that they've already
			added that New Ingredient.

			But if there is no existing match then the function processNewIngredient() is called from the modelClass where
			some more validation takes place (is there an existing ingredient in the database that matches the newly inputted
			ingredient, is there any invalid characters, as well as performing sanitization on the inputted ingredient etc)
        	*/
	 		if($_POST['newIngredientNameButton']) {

	 			if($_POST['newIngredientName']) {

	 				$newIngredientNameVal = ucfirst($_POST['newIngredientName']);
				            
		            if($newIngredientNameVal == $_SESSION['newIngredient'][$newIngredientNameVal]) {

		    			$addNewIngredMsg = 'New Ingredient already exists!';

		        	} else {
		        		$niresult = $this -> model -> processNewIngredient();
		        	}

	 			} else {
	 				$addNewIngredMsg = 'Please enter a new ingredient!';
	 			}
	 			
            }

            /*
			niresult (newIngredientResult)
			If it is an array because the processNewIngredient() has returned values to the variable,
			then it is extracted so that those values can be displayed where they need to be.
			(whether they are specific error messages due to a users input/non input, or a 'true' status in order for the next step
			to be generated)
            */
			if(is_array($niresult)) {
           	 extract($niresult);
            }

            /*
			If $_POST['deleteConfirmedIngredient'] is true then the user has clicked the delete button that is associated
			with a previously confirmed ingredient (which are all stored in the variable $_SESSION['confirmed'] until the
			finalizing of the recipe) so JUST THE $_SESSION['confirmed'] variable that matches with the ingredient name being
			deleted, is unset, so that there ends up being no trace of that ingredient in the confirmed section anymore.
            */
			if($_POST['deleteConfirmedIngredient']) {
	            $name = $_POST['ingredientName'];
	            unset($_SESSION['confirmed'][$name]);
		    }

		    /*
			If $_FILES is true then the user has uploaded a photo for the recipe, so then the function uploadAndResizeImage() is
			called from the model class to process it so that no more what size the photo is they inputted, it will be scaled
			to consistent dimensions in order to fit on the various pages where the recipe image is to be shown
            */
		    if($_FILES) {
		    	$uploaded = $this -> model -> uploadAndResizeImage();
		    }

		    if(is_array($uploaded)) {
	    		if($uploaded['ok'] == true) {
	    			$_SESSION['newRecipe']['recipeImage'] = $uploaded['recipeImage'];
	    		}
	        } 

	        /*
			If $_POST['form'] is true then the user has filled out one of the three sections of the Edit Recipe Process
			and clicked the "Next" button, so now the information that they have filled is sent to a function
			in order to validate it, such as check for required fields etc
            */
		    if($_POST['form']) {
		    	$vresult = $this -> model -> validateRecipeForm();

		    }

		    /*
			Just like with $niresult (newIngredientResult) previously,  if $vresult is an array because the validateRecipeForm()
			has returned values to the variable, then it is extracted so that those values can be displayed where they need to be.
			(whether they are specific error messages due to a users input/non input, or a 'true' status in order for the next step
			to be generated)
            */
		    if(is_array($vresult)) {
            extract($vresult);

        	}

        	/*
			If $_POST['deleteNewIngredientBtn'] is true, then the user has clicked the "Delete" button that
			referes to any "New Ingredients" that they have created using the "Edit New Ingredient" input
			field and submit button.

			These new ingredients are simply stored in a multidimensional array with the root element being 
			called $_SESSION['newIngredient'] and the subsequent array being in the name of each New Ingredient
			that the user loads. So when they chose to delete an ingredient, the $_SESSION['newIngredient'] array that
			matches up with that ingredient name is unset and wiped.
            */
        	if($_POST['deleteNewIngredientBtn']) {

        		/*checks first to make sure a value from the confirmed new ingredients was actually selected to delete
	       		and if there was none, an appropriate validation message tells the user that fact.*/
	       		if($_POST['formNewIngredientsSelect']) {
	       			unset($_SESSION['newIngredient'][$_POST['formNewIngredientsSelect']]);
	       		} else {
	       			$niDeleteErrorMsg = 'No ingredient selected!';
	       		}
	       	}

		    

	       	/*
			--------------------------FORM VALIDATIONS---------------------------

			Everytime that page reloads (if any buttons are pressed to go forward or backward
			through the steps of editing a recipe) - Line 101 is carried out where form validation
			is completed, and the results extracted, therefore, not only will there automatically
			be validation carried out every time a step is accessed, the validation error messages
			will generate and be displayed in their places that are written into the code

	       	*/


	       	/*
			The following code will display the FIRST PART of the Edit Recipe Process, but will first check to see if :
			|
			|
			V
			1 - The user has not submitted anything via $_POST (they have just arrived at the First Step) & !$_SESSION['newRecipe']['recipeName'] is false so the user is not adding a new recipe
			2 - The user is going from the second step to the first step
			3 - The user is trying to go to the second step but there is validation errors
			4 - The user has previewed the Recipe right at the end of the Edit Recipe procedure, and then clicked the link to go back to the first step.

	       	*/
        	if(!$_POST && !$_SESSION['newRecipe']['recipeName'] || $_POST['firstStep'] || $_POST['secondStep'] && $vresult['ok'] == false || $_GET['step'] == 'one') {

        		/*
				This part of the code makes the users input sticky, and also retains the information so that
				it can be double checked by the user at the end when the entire recipe is previewed in the
				Recipe View.
        		*/
	        	if($_POST['secondStep']) {
	        		$_SESSION['newRecipe']['recipeName'] = strip_tags($_POST['recipeName']);
	        		$_SESSION['newRecipe']['recipeDescription'] = strip_tags($_POST['recipeDescription']);
	        	}

			$html .= '<div id="recipeForm">'."\n";
			$html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm" enctype="multipart/form-data">'."\n";
	        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
	        $html .= '<input type="hidden" name="recipeImageStorage" value="'.$_POST['recipeImage'].'" />'."\n";
	        $html .= '<input type="hidden" name="form" value="One" />'."\n";
		    $html .= '<div id="nameDescriptionUpload">'."\n";
			$html .= '<label for="formRecipeName">Name</label>'."\n";

			/*
			The variable $_GET['firstEditPage'] comes from the "Edit Recipe" Link that is on the Recipe View. When the user first accesses the
			page, the information displayed will be from the database, but after that first time, whatever is subsequently entered / altered /
			even kept the same in the text / inout boxes will be stored in $_SESSION so as to not keep overwriting the users changes with the
			information thats in the database, but rather keep a running track of any changes.
			*/
			if($_GET['firstEditPage']) {
				$html .= '<input type="text" name="recipeName" id="formRecipeName" value="'.htmlspecialchars($recipeName).'" />'."\n";
			} else {
				$html .= '<input type="text" name="recipeName" id="formRecipeName" value="'.htmlspecialchars($_SESSION['newRecipe']['recipeName']).'" />'."\n";
			}

			
			$html .= '<div id="recipeNameMsg" class="requiredError">'.$recipeNameMsg.'</div>';
			$html .= '<div class="clearDiv"></div>'."\n";
			$html .= '<label for="formRecipeDescription">Description (optional)</label>'."\n";

			/*
			The variable $_GET['firstEditPage'] comes from the "Edit Recipe" Link that is on the Recipe View. When the user first accesses the
			page, the information displayed will be from the database, but after that first time, whatever is subsequently entered / altered /
			even kept the same in the text / inout boxes will be stored in $_SESSION so as to not keep overwriting the users changes with the
			information thats in the database, but rather keep a running track of any changes.
			*/
			if($_GET['firstEditPage']) {
				$html .= '<textarea rows="4" cols="40" name="recipeDescription" id="formRecipeDescription" >'.$recipeDescription.'</textarea>'."\n";
			} else {
				$html .= '<textarea rows="4" cols="40" name="recipeDescription" id="formRecipeDescription" >'.$_SESSION['newRecipe']['recipeDescription'].'</textarea>'."\n";
			}

			
			$html .= '<div class="clearDiv"></div>'."\n";
			$html .= '<div id="recipeDescMsg" class="requiredError">'.$recipeDescMsg.'</div>';
		    $html .= '<label for="recipeImage">Upload Image (optional)</label>'."\n";
		    $html .= '<input type="file" name="recipeImage" />'."\n";

				
		    if($_SESSION['newRecipe']['recipeImage']) {
				$html .= '<div id="recipeThumbnailImage">'."\n";
                $html .= '<img src="tempUploads/thumbnails/'.$_SESSION['newRecipe']['recipeImage'].'" id="mainRecipePic" alt="Recipe Picture Preview" />'."\n";
                $html .= '</div>'."\n";
            } else {

            	/*
				Checking to see if there was ever a picture associated with the recipe, and if there was
				storing it inside the $_SESSION variables, so that again, we don't keep overwriting the
				users changes with the information thats in the database, but rather keep a running track of any changes.
            	*/
                if($recipeImage) {
		             $_SESSION['newRecipe']['originalImageName'] = $recipeImage;
		             $_SESSION['newRecipe']['recipeThumbnailPath'] = 'uploads/thumbnails/'.$recipeImage.'';
		             $_SESSION['newRecipe']['recipeImagePath'] = 'uploads/'.$recipeImage.'';
		             $html .= '<div id="recipeThumbnailImage">'."\n";
		             $html .= '<img src="'.$_SESSION['newRecipe']['recipeThumbnailPath'].'" id="mainRecipePic" alt="Recipe Picture Preview" />'."\n";
		             $html .= '</div>'."\n";
                 } else if($_SESSION['newRecipe']['originalImageName']) {
					$html .= '<div id="recipeThumbnailImage">'."\n";
                    $html .= '<img src="uploads/thumbnails/'.$_SESSION['newRecipe']['originalImageName'].'" id="mainRecipePic" alt="Recipe Picture Preview" />'."\n";
                    $html .= '</div>'."\n";
                 }
            }
		  
		    $pRecipeMsg = $uploadMsg ? $uploadMsg : $pRecipeMsg;
        
        	$html .= '<div class="recipeError">'.$pImageMsg.'</div>';

		    $html .= '<div class="clearDiv"></div>'."\n";
	        $html .= '</div>'."\n";
		    $html .= '<div id="navigationButton">'."\n";
		    $html .= '<input type="submit" name="secondStep" id="firstStepBtn" class="navigationButton" value="Next Step" />'."\n";
		    $html .= '</div>'."\n";
			$html .= '</form>'."\n";
	        $html .= '</div>'."\n";
	    }

	    	/*
			The following code will display the SECOND PART of the Edit Recipe Process, but will first check to see if :
			|
			|
			V
			1 - The user is going from the third step back to the second step.
			2 - The user has gone from the first step to the second step and there is no validation errors.
			3 - The user has tried going from the second step to the third step but there is validation errors
			4 - The user has created a New Ingredient.
			5 - The user has confirmed an Ingredient that will be in the recipe and provided measurements/quantity etc
			6 - The user has deleted a confirmed Ingredient that was in the recipe
			7 - The user has previewed the Recipe right at the end of the Edit Recipe procedure, and then clicked the link to go back to the second step.
			8 - The user has clicked the button to delete a New Ingredient that they have loaded during this Edit Recipe session.
	       	*/
	        if($_POST['backToSecondStep'] || $_POST['secondStep'] && $vresult['ok'] || $_POST['thirdStep'] && $vresult['ok'] == false || $_POST['newIngredientNameButton'] || $_POST['confirmIngredientBtn'] || $_POST['deleteConfirmedIngredient'] || $_GET['step'] == 'two' || $_POST['deleteNewIngredientBtn']){

	        	/*
				These functions go straight to the database to get all the ingredients and all the different measurements
				to put these values drop down boxes for the user to select when confirming ingredients that their recipe
				will contain.
	        	*/
	        	$this -> ingredients = $this -> model -> showIngredients();
	        	$this -> measurements = $this -> model -> showMeasurements();

	        	/*
				If there are no confirmed ingredients in the $_SESSION['confirmed'] variable, they'll need to be pulled
				from the database, since there must be ingredients stored in order for the recipe to be stored in the
				database in the first place!
	        	*/
	        	if(!$_SESSION['confirmed'] && $_POST['secondStep']) {

	        		/*
					Running the getRecipeIngredientsByID() function to pull the stored ingredients from the database in
					order to prepopulate the Confirmed Ingredients box so that the user can make any necessary changes.
	        		*/
	        	$this -> recipeIngredients = $this -> model -> getRecipeIngredientsByID($_SESSION['newRecipe']['recipeID']);

	        	foreach ($this -> recipeIngredients as $recipeIngredient) {

	        		if(!$recipeIngredient['measurement'] && !$recipeIngredient['ingredientQuantity'] && !$recipeIngredient['ingredientName']) {
                            $html .= '';
                        } else {

	        				$_SESSION['confirmed'][$recipeIngredient['ingredientName']] = array(

			                'ingredientName' => $recipeIngredient['ingredientName'],
			                'quantity' => $recipeIngredient['ingredientQuantity'],
			                'measurement' => $recipeIngredient['measurement'],
			                'extraInfo' => $recipeIngredient['extraIngredientInfo']
		                	);

	        			}

		        	}
		        }

		        /*
				This function will run when the user selects at least an Ingredient (existing or recently added) and a quantity,
				but they can also select a measurement from the different measurements that are stored in the database, as well
				as having the extra option for Extra Information to be saved as well in relation to each ingredient.
	        	*/
		        if($_POST['confirmIngredientBtn']) {

						$confirmedResult = $this -> model -> confirmIngredient();				
					}                            
                            
				/*
					If the user has come from the first step by clicking the button to take them from the first step to the
					second step - $_POST['secondStep'] - then we save the values that they had entered onto the previous form
					by logging them into $_SESSION variables, making the form sticky and having that saved information readily
					accessible for whenever the user goes back to the first step, or continues going all the way to the "Preview
					Recipe page"
					*/
	        	if($_POST['secondStep']) {
	        		$_SESSION['newRecipe']['recipeName'] = strip_tags($_POST['recipeName']);
	        		$_SESSION['newRecipe']['recipeDescription'] = strip_tags($_POST['recipeDescription']);
	        	}

	        	/*
				If the user has come from the third step by clicking the button to take them from the first step to the
				second step - $_POST['secondStep'] - then we save the values that they had entered onto the previous form
				by logging them into $_SESSION variables, making the form sticky and having that saved information readily
				accessible for whenever the user goes back to the first step, or continues going all the way to the "Preview
				Recipe page"
				*/
	        	if($_POST['backToSecondStep']) {
	        		$_SESSION['newRecipe']['recipeMethod'] = strip_tags($_POST['recipeMethod']);
	        	}	     


		        	$html .= '<div id="recipeForm">'."\n";
				    $html .= '<div id="formAddAndDeleteIngredients" class="formSection">'."\n";
				    $html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm" enctype="multipart/form-data">'."\n";
			        $html .= '<input type="hidden" name="recipeID" value="'.$_POST['recipeName'].'" />'."\n";
		        	$html .= '<div id="formAddIngredients">'."\n";
		        	$html .= '<div class="ingredientsError">'.$ingredientsMsg.'</div>';

		        	/*
					If the $_SESSION['newIngredient'] variable is activated by the user adding a New Ingredient, the If statement
					meets its condition and creates a seperate Ingredient area by means of a Fieldset so as to be fairly straightforward
					for the user to use.
		        	*/
		        	if($_SESSION['newIngredient']) {

		        	$html .= '<fieldset id="newIngredientSection">'."\n";
					$html .= '<legend>Ingredient Section</legend>'."\n";

							$html .= '<div class="ingredientsError">'.$niDeleteErrorMsg.'</div>';
							$html .= '<label for="formNewIngredientsSelect">New Ingredient:</label>'."\n";
							$html .= '<select name="formNewIngredientsSelect" id="formNewIngredientsSelect">'."\n";
							$html .= '<option value="0">Please Select...</option>'."\n";

								/*
								This foreach loops runs through all the New Ingredients loaded by the user and generates
								them inside a drop down menu.
								*/
								foreach ($_SESSION['newIngredient'] as $newIngredient) {
								$html .= '<option value="'.$newIngredient.'">'.$newIngredient.'</option>'."\n";
								}

							/*
							The delete button will be for any New Ingredient loaded, but will only work if a New Ingredient
							is loaded, as per the code on Line 119.
							*/
							$html .= '</select>'."\n";
							$html .= '<input type="submit" name="deleteNewIngredientBtn" id="deleteNewIngredientBtn" value="Delete" />'."\n";
							$html .= '<div class="clearDiv"></div>'."\n";
							$html .= '<div class="clearDiv"></div>'."\n";

					}

			        $html .= '<label for="formIngredientsSelect">Ingredient:</label>'."\n";
					$html .= '<select name="formIngredientsSelect" id="formIngredientsSelect">'."\n";
					$html .= '<option value="0">Please Select...</option>'."\n";

					/*
					Interpreting all the Ingredients that have been previously pulled from the database and prepolutating
					a drop down box with all the different recipes.
					*/
					foreach ($this -> ingredients as $ingredient) {
						$html .= '<option value="'.$ingredient['ingredientName'].'">'.$ingredient['ingredientName'].'</option>'."\n";
					}

					$html .= '</select> <br />'."\n";

					if($_SESSION['newIngredient']) {

						$html .= '<br />'."\n";
						$html .= '<div class="ingredientsError">'.$confirmedResult['doubleErrorMsg'].'</div>';
						$html .= '<p>Now that you\'ve added a new ingredient, please select EITHER a New Ingredient OR an Existing Ingredient when confirming anymore ingredients for your recipe.</p>'."\n";
						$html .= '</fieldset>'."\n";

					}

					$html .= '<label for="formMeasurement">Quantity:</label>'."\n";
			        $html .= '<input type="text" name="formMeasurement" id="formMeasurement" value="" />'."\n";
					$html .= '<label for="formMeasurementSelect">Measurement:</label>'."\n";
			        $html .= '<select name="formMeasurementSelect" id="formMeasurementSelect">'."\n";
					$html .= '<option value="0"></option>'."\n";

					foreach ($this -> measurements as $measurement) {
						$html .= '<option value="'.$measurement['measurement'].'">'.$measurement['measurement'].'</option>'."\n";
					}
				
					$html .= '</select> <br />'."\n";
					$html .= '<label for="formExtraInfo">Extra Info:</label>'."\n";
			        $html .= '<input type="text" name="formExtraInfo" id="formExtraInfo" value="" placeholder="Finely chopped" />'."\n";
					$html .= '<div class="clearDiv"></div>'."\n";
					$html .= '</div>'."\n";
				    $html .= '<input type="submit" name="confirmIngredientBtn" id="confirmIngredientBtn" value="Confirm Ingredient" />'."\n";
				    $html .= '</form>'."\n";
				    $html .= '<br />';
				    $html .= '<div class="ingredientsError">'.$confirmedResult['confirmMsg'].'</div>';
					$html .= '<div id="formConfirmedIngredients">'."\n";
					$html .= '<fieldset>'."\n";
					$html .= '<legend>Confirmed Ingredients</legend>'."\n";

					

					$confirmedIngredients = $this -> model -> getConfirmedIngredients();


					/*	
						Whenever the $confirmedIngredients variable is an array, this means that there have been
						ingredients that have been confirmed by the user, therefore we carry out the function -
						displayConfirmedIngredients - in order to generate them in the middle of the page.
						*/
						if(is_array($confirmedIngredients)) {
				            $html .= $this -> displayConfirmedIngredients($confirmedIngredients);
				        } else {

				        	if(!is_array($this -> recipeIngredients)) {
				        		 $html .= '<p>No Ingredients selected.</p>';
				        	}

				           
				        }

					$html .= '<div class="clearDiv"></div>'."\n";
					$html .= '</fieldset>'."\n";
					$html .= '</div>'."\n";
					$html .= '<div class="clearDiv"></div>'."\n";
					$html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm" enctype="multipart/form-data">'."\n";
					$html .= '<p>If an ingredient is not already loaded, enter it into the below field and click the "Add Ingredient" button to add it to the list.</p>'."\n";
					$html .= '<input type="text" name="newIngredientName" id="newIngredient" value="'.htmlentities(stripslashes($newIngredientName),ENT_QUOTES).'" />'."\n";
					$html .= '<div class="ingredientsError">'.$requiredMessage.'</div>';
				    $html .= '<div class="ingredientsError">'.$notStringMessage.'</div>';
				    $html .= '<div id="newIngredMsg">'.$newIngredMsg.'</div>';
				    $html .= '<div class="ingredientsError">'.$addNewIngredMsg.'</div>';
				    $html .= '<input type="submit" name="newIngredientNameButton" id="addNewIngredient" value="Add New Ingredient" />'."\n";
				    $html .= '</form>'."\n";
				    $html .= '</div>'."\n";
				    $html .= '<div id="navigationButtons">'."\n";
				    $html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm" enctype="multipart/form-data">'."\n";
				    $html .= '<input type="hidden" name="form" value="Two" />'."\n";
				    $html .= '<input type="submit" name="firstStep" id="previousStepBtn" class="navigationButton" value="Previous Step" />'."\n";
				    $html .= '<input type="submit" name="thirdStep" id="nextStepBtn" class="navigationButton" value="Next Step" />'."\n";
				    $html .= '</form>'."\n";
				    $html .= '</div>'."\n";			
			       	$html .= '</div>'."\n";      				               
			        
	        } 
	        	/*
				The following code will display the SECOND PART of the Edit Recipe Process, but will first check to see if :
				|
				|
				V
				1 - The user has clicked the "Preview Recipe" Button so the form resubmits in order to validate before it gets redirected.
				2 - The user has gone from the second step to the third step and there is no validation errors.
				3 - The user has previewed the Recipe right at the end of the Edit Recipe procedure, and then clicked the link to go back to the third step.
	       		*/
	        	if($_POST['previewRecipe'] || $_POST['thirdStep'] && $vresult['ok'] || $_GET['step'] == 'three') {

	        		/*
					If the form has been submitted to itself due to the user clicking "Preview Recipe" OR the user has entered
					something into the Recipe Method text area and then gone back to the second step, the values entered into
					the Recipe Method will be logged into the $_SESSION in order to make the form sticky.
	        		*/
	        		if($_POST['recipeMethod'] || $_POST['backToSecondStep']) {
	        			$_SESSION['newRecipe']['recipeMethod'] = strip_tags($_POST['recipeMethod']);
	        		}

	        		$html .= '<div id="recipeForm">'."\n";

	        		/*
					If the last form validates and the user has selected the option to preview the
					recipe, then they're redirected to the recipeView class where the information
					that they have inputted thus far will be arranged in the same way one the Recipe
					has been completely finalized.
	        		*/
	        		if($_POST['previewRecipe'] && $vresult['ok']) {
	        			header("location:index.php?page=recipe");
	        		}

					$html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm" enctype="multipart/form-data">'."\n";
			        $html .= '<input type="hidden" name="form" value="Three" />'."\n";
			        $html .= '<div id="formMethod">'."\n";
				   	$html .= '<fieldset>'."\n";
					$html .= '<legend>Method</legend>'."\n";

					if(!$_SESSION['newRecipe']['recipeMethod']) {
						$html .= '<textarea rows="4" cols="40" name="recipeMethod" id="recipeMethod">'.$recipeMethod.'</textarea>'."\n";
					} else {
						$html .= '<textarea rows="4" cols="40" name="recipeMethod" id="recipeMethod">'.$_SESSION['newRecipe']['recipeMethod'].'</textarea>'."\n";
					}

				    
				    $html .= '<div id="recipeMethodMsg">'.$recipeMethodMsg.'</div>';
				    $html .= '<div>'.$newIngredMsg.'</div>';
				    $html .= '</fieldset>'."\n";
				    $html .= '<div class="clearDiv"></div>'."\n";
			       	$html .= '</div>'."\n";
			       	$html .= '<div id="finalNavigationButtons">'."\n";
			        $html .= '<input type="submit" name="backToSecondStep" id="previousStepBtn" class="navigationButton" value="Previous Step" />'."\n";
			        $html .= '<input type="submit" name="previewRecipe" id="previewRecipeBtn" class="navigationButton" value="Preview Recipe" />'."\n";
			        $html .= '</div>'."\n";
			       	$html .= '</form>'."\n";
		       		$html .= '</div>'."\n";
	        }
                
        return $html;        
    }


    private function displayConfirmedIngredients($confirmedIngredient) {
        
        $html = '';

        foreach($confirmedIngredient as $confirmed) {

			$html .= '<form method="post" action="index.php?page=editRecipe&amp;id='.$_SESSION['newRecipe']['recipeID'].'#recipeForm">';

			/*
            This function takes all the ingredients that the user has confirmed in the Edit Recipe process, loops
            through them all and at the same time runs the pluralIngredientsAndMeasurements() function to ensure
            the correct and logical formation of ingredients, quantities and measurements, as well
            as using plurals where required. (e.g 1 Cup Flour - compared to - 2 Cup(s) Flour)
            */

			if($confirmed['ingredientName']) {
				$html .= $this -> model -> pluralIngredientsAndMeasurements($confirmed['measurement'], $confirmed['ingredientQuantity'], $confirmed['extraIngredientInfo'], $confirmed['ingredientName']);	
			} else {
				$html .= $this -> model -> pluralIngredientsAndMeasurements($confirmed['measurement'], $confirmed['quantity'], $confirmed['extraInfo'], $confirmed['ingredient']);
			}        				
								
            $html .= '<input type="hidden" name="ingredientName" value="'.$confirmed['ingredient'].'" />';
            $html .= '<input type="submit" name="deleteConfirmedIngredient" value="Delete" />';
            $html .= '</form>';
			                
        }

        return $html;
        
    }
        
}

?>