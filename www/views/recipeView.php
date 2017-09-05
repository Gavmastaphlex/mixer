<?php

class RecipeView extends View {
    
    protected function displayContent() {

            /*
                If $_GET['uploadedRecipe'] == true, then the Preview Recipe Page (which is the final stage of adding a
                new recipe) has been accepted by the user, they've clicked Create Recipe the page has submitted to itself,
                and then the recipe is entered into the database and the user then will have their own recipe page displayed for them
                with the new recipe immediately displayed with their other recipes.
            */

        if($_GET['uploadedRecipe'] == true) {
            
            $finalUpload = $this -> model -> uploadRecipe();

            if($finalUpload == true) {
                header("location:index.php?page=recipes&uploadedRecipe=true");
            }

            
        }

         /*
                If $_GET['updatedRecipe'] == true, then the Preview Recipe Page (which is the final stage of editing an
                existing recipe) has been accepted by the user, they've clicked Update Recipe the page has submitted to itself,
                and then the existing recipe is updated in the database.
            */


        if($_GET['updatedRecipe'] == true) {

            if($_SESSION['newRecipe']['recipeImage']) {
                            $this -> model -> updateRecipe($_SESSION['newRecipe']['recipeID'], $_SESSION['userID'], $_SESSION['newRecipe']['recipeName'], $_SESSION['newRecipe']['recipeImage'], $_SESSION['newRecipe']['recipeDescription'], $_SESSION['newRecipe']['recipeMethod'], $_SESSION['newRecipe']['extraInfo']);
                        } else {
                             $this -> model -> updateRecipe($_SESSION['newRecipe']['recipeID'], $_SESSION['userID'], $_SESSION['newRecipe']['recipeName'], $_SESSION['newRecipe']['originalImageName'], $_SESSION['newRecipe']['recipeDescription'], $_SESSION['newRecipe']['recipeMethod'], $_SESSION['newRecipe']['extraInfo']);
                        }

                        /*
                            If the user has created any new ingredients, up to this point the have been stored in the session
                            to allow the user time to confirm them or delete and recreate them if they mispelled them. Now
                            that the user has confirmed the recipe update, we will now add them to the database.
                            |
                            |
                            V
                        */

            if($_SESSION['newIngredient']) {
                foreach($_SESSION['newIngredient'] as $newIngredient) {
                    $this -> model -> newIngredient($newIngredient);
                }
            }
            
            /*
            All previous ingredients that were matched to the recipe are wiped first of all
            */
            $ingredientsDeleted =  $this -> model -> deleteRecipeIngredients($_SESSION['newRecipe']['recipeID']);

            /*
            Then the newly confirmed ingredients are stored into a variable called $updatedIngredients.
            */
            $updatedIngredients = $this -> model -> getConfirmedIngredients();

            /*
            First we make sure that the newly created variable ($updatedIngredients) is an array
            to double check that the confirmed ingredients were correctly passed through to it
            */
            if(is_array($updatedIngredients)) {

                /*
                Then a foreach loop is run to isolate each seperate ingredient and to enter them into the database
                where they're matched up with the right recipeID
                */

                foreach ($updatedIngredients as $updatedIngredient) {

                   $ingredientID = $this -> model -> getIngredientID($updatedIngredient['ingredient']) ;
                   $measurementID = $this -> model -> getMeasurementID($updatedIngredient['measurement']);
                   $this -> model -> insertIngredientInfo($_SESSION['newRecipe']['recipeID'], $ingredientID, $updatedIngredient['quantity'], $measurementID, $updatedIngredient['extraInfo']);
                }
                
            }

            /*
            Since the new recipe / update is confirmed, the temporary image created during the add/edit recipe process
            can now be permanently moved to uploads.
            */
                if($_SESSION['newRecipe']['recipeImage']) {

                    $this -> model -> moveMainImage($_SESSION['newRecipe']['recipeImage']);
                    $this -> model -> moveThumbnail($_SESSION['newRecipe']['recipeImage']);

                    }

                header("location:index.php?page=recipes&amp;updatedRecipe=true");
        }

        
        if(isset($_GET['id'])) {
            //if $_GET['id'] is set, go to the database and search for the recipe that matches that recipeID
            $this -> recipe = $this -> model -> getRecipeByID($_GET['id']);
            
            /*if an array is returned the query successfully ran and then that recipe information is returned so as to display it,
            but if it isn't an array, then the recipe wasn't found in the the database and an appropriate message is displayed.*/
            if(is_array($this -> recipe)) {
                $html .= $this -> displayRecipe();
            } else {
                $html = '<h2>Oh no!</h2>'."\n";
                $html .= '<div id="searchSummary">'."\n";
                $html .= '<p>Sorry, that recipe doesn\'t exist!</p>';
                $html .= '<a href="index.php?page=recipes&amp;recipes=all">Back to Recipes</a>'."\n";
                $html .= '<img src="images/noFoodPic.jpg" id="noResultsImage" alt="Empty Plate Picture"/>'."\n";
            }
        } else {

            /*
            If $_SESSION['newRecipe'] is true, either a new recipe or an updated recipe is to be displayed
            for the user to check that all the details are correct before finalizing the recipe.
            */
            if($_SESSION['newRecipe']) {
                $html .= $this -> previewRecipe();

            } else {
                $html .= 'Sorry an error occured.';
            }
            
        }
        
        return $html;
    }

                /*
                This function takes all the ingredients that are associated with the recipe (whether already in the database or from the
                $_SESSION variables if its a new recipe being created or an existing recipe thats being updated) and runs the foreach
                loop to seperate the different ingredient information out so as to display it.
                */
                private function displayRecipeIngredients() {                        
                                                    
                        foreach($this -> recipeIngredients as $recipeIngredient) {

                                if(!$recipeIngredient['measurement'] && !$recipeIngredient['ingredientQuantity'] && !$recipeIngredient['ingredientName']) {
                                    $html .= '';
                                } else {

                                    $html .= '<li>'."\n";

                                    /*
                                    This function makes sure s's are displayed for plural measurements (1 Cup Flour // 2 Cup(s) Flour) etc
                                    */
                                    $html .= $this -> model -> pluralIngredientsAndMeasurements($recipeIngredient['measurement'], $recipeIngredient['ingredientQuantity'], $recipeIngredient['extraIngredientInfo'], $recipeIngredient['ingredientName']);

                                $html .= '</li>'."\n";
                             }   

                         }

                        return $html;             

                }
           
    
    private function displayRecipe() {

                        /*
                        If $_POST['deleteButton'] is true, that means that either admin or the owner of the recipe has clicked
                        the delete recipe button, and now a Confirmation Box is generated to double check the user wants to go
                        ahead.
                        */
                        if($_POST['deleteButton']) {
                            $html .= '<div id="deleteRecipeBox">'."\n";
                            $html .= '<form method="post" action="index.php?page=userPanel&amp;id='.$_GET['id'].'">'."\n";
                            $html .= '<p><strong>Are you sure you want to delete this recipe?</strong></p>'."\n";
                            $html .= '<a href="index.php?page=recipe&amp;id='.$_GET['id'].'" id="cancelDeleteRecipe" class="green-button">Cancel</a>'."\n";
                            $html .= '<input type="submit" name="deleteRecipe" value="Delete Recipe" id="confirmDeleteRecipe" class="red-button" />'."\n";
                            $html .= '</form>'."\n";
                            $html .= '</div>'."\n";
                        }


                        /*
                        If $_SESSION['mixItUp'] is true, that means that a Mix It Up search has been performed on ingredients, and that this recipe
                        is one of the results, so a button is generated to allow the user to return back to their results, rather than wiping the
                        search results that match up with the ingredients that they have entered
                        */
                        if($_SESSION['mixItUp'] && !$_POST['deleteButton']) {
                            $html .= '<form method="post" action="index.php?page=recipes&amp;pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            $html .= '<input type="submit" id="backToMixerResults" name="backToMixerResults" value="Back to Mixer results" class="orange-button single-button" />'."\n";
                            $html .= '</form>'."\n";

                        } else if($_SESSION['userRecipes'] && !$_POST['deleteButton'] || $_SESSION['selectedUserRecipes']['userID']) {

                        /*
                        The following code will run if $_SESSION['userRecipes'] is true (which will display the user whos logged in recipes)
                        or if $_SESSION['selectedUserRecipes']['userID'] is true, which is instantiated when the Admin chooses a user to view
                        their recipes
                        */
                            $html .= '<form method="post" action="index.php?page=recipes&amp;';

                            
                            if($_GET['userID']) {

                                /*
                            if $_GET['userID'] is true then this link will lead the user back to the recipes page where their own recipes will
                            be displayed.
                            */
                            $html .= 'browseUserRecipes='.$_GET['userID'].'&pageNum='.$_SESSION['pageNum'].'#recipe'.$this -> recipe['recipeID'].'">'."\n";

                            } else {
                                /*
                                if $_GET['userID'] is false then we revert to using the userID that is stored in $_SESSION['userRecipes']['userID']
                                since it is the admin that is viewing someone elses recipes, so that users id is temporarily stored in the session'
                                variable until it is reset when the admin gets out of the userlist / userdetails page.
                                */
                            $html .= 'id='.$_SESSION['userRecipes']['userID'].'&pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            }

                            $html .= '<input type="submit" id="backToUserRecipes" name="backToUserRecipes" value="Back to User Recipes" class="orange-button single-button" />'."\n";
                            $html .= '</form>'."\n";
                        } else if($_SESSION['allRecipes'] && !$_POST['deleteButton']) {
                            /*
                                If the previous conditions in this if statement fail to be met, and $_SESSION['allRecipes'] is true, then this link
                                that is generated at the top of the Recipe page will link back to the Recipes Page where all the recipes will be
                                displayed. It will display the last page that the user was on (since that value has been stored in a session
                                variable) and the recipeID is called on as an anchor so that the page will be displayed at the recipe that the user
                                last clicked on so that they can easily take off from where they left off.
                            */
                            $html .= '<form method="post" action="index.php?page=recipes&amp;pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            $html .= '<input type="submit" id="backToRecipes" name="backToRecipes" value="Back to Recipes" class="blue-button single-button" />'."\n";
                            $html .= '</form>'."\n";
                        
                        } 
                        
                        /*
                        The following code is displaying the recipe information that has been pulled from the database.
                        */
                        $html .= '<div id="recipe">'."\n";
                        $html .= '<h2>'.$this -> recipe['recipeName'].'</h2>'."\n";
                        $html .= '<div id="recipeBlurb">'."\n";              
                        $html .= '<p><em>'.$this -> recipe['recipeDescription'].'</em></p>'."\n";
                        $html .= '</div>'."\n";

                        if(!$this -> recipe['recipeImage']) {
                            $html .= '<img src="images/noImage2.png" alt="No Image" />'."\n";
                        } else {
                            $html .= '<img src="uploads/'.$this -> recipe['recipeImage'].'" id="mainRecipePic" alt="'.htmlspecialchars($this -> recipe['recipeName']).' picture"/>'."\n";
                        }

                        
                        $html .= '<div id="recipeAndMethod">'."\n";
                        $html .= '<div id="recipeIngredients">'."\n";
                        $html .= '<h4>Ingredients</h4>'."\n";
                        $html .= '<ul>'."\n";

                        /*
                        All the ingredients that match the recipeID are pulled from the database and then
                        sent to the displayRecipeIngredients() function to view them on the recipe view.
                        */
                        $this -> recipeIngredients = $this -> model -> getRecipeIngredientsByID($_GET['id']);
                        
                        if(is_array($this -> recipeIngredients)) {
                            $html .= $this -> displayRecipeIngredients();
                        } else {
                            $html .= '<p>Sorry, the ingredients are not available</p>';
                        } 

                        $html .= '</ul>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div id="method">'."\n";
                        $html .= '<h4>Method</h4>'."\n";
                        $html .= '<p>'.nl2br($this -> recipe['recipeMethod']).'</p>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div class="clearDiv"></div>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div id="recipeHistory">'."\n";
                        $html .= '<ul>'."\n";
                        $html .= '<li><strong>Date Added :</strong> '.$this -> recipe['recipeUploadDate'].'</li>'."\n";
                        $html .= '<li><strong>Added By :</strong> '.$this -> recipe['userName'].'</li>'."\n";
                        $html .= '</ul>'."\n";

                        /*
                        if the owner of the recipe is viewing the recipe, or if admin views any recipe, 
                        a box with extra options is viewable that enables the editing or deleting of
                        the recipe.
                        */
                        if($_SESSION['userName'] == $this -> recipe['userName'] || $_SESSION['userAccess'] == 'admin') {
                            $html .= '<form method="post" action="index.php?page=recipe&amp;id='.$_GET['id'].'">'."\n";  
                            $html .= '<a href="index.php?page=editRecipe&amp;id='.$_GET['id'].'&amp;firstEditPage=true" id="editRecipeButton" class="green-button">Edit Recipe</a>'."\n"; 
                            $html .= '<input type="submit" name="deleteButton" class="loginBtn red-button" value="Delete Recipe"  id="deleteRecipeButton" />'."\n";
                            $html .= '</form>'."\n";
                        }

                        $html .= '<div class="clearDiv"></div>'."\n"; 
                        $html .= '</div>'."\n";               
                        $html .= '</div>'."\n";

                        if($_SESSION['mixItUp'] && !$_POST['deleteButton']) {
                            $html .= '<form method="post" action="index.php?page=recipes&amp;pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            $html .= '<input type="submit" id="backToMixerResults" name="backToMixerResults" value="Back to Mixer results" class="orange-button single-button" />'."\n";
                            $html .= '</form>'."\n";

                        } else if($_SESSION['userRecipes'] && !$_POST['deleteButton'] || $_SESSION['selectedUserRecipes']['userID']) {

                        /*
                        The following code will run if $_SESSION['userRecipes'] is true (which will display the user whos logged in recipes)
                        or if $_SESSION['selectedUserRecipes']['userID'] is true, which is instantiated when the Admin chooses a user to view
                        their recipes
                        */
                            $html .= '<form method="post" action="index.php?page=recipes&amp;';

                            
                            if($_GET['userID']) {

                                /*
                            if $_GET['userID'] is true then this link will lead the user back to the recipes page where their own recipes will
                            be displayed.
                            */
                            $html .= 'browseUserRecipes='.$_GET['userID'].'&pageNum='.$_SESSION['pageNum'].'#recipe'.$this -> recipe['recipeID'].'">'."\n";

                            } else {
                                /*
                                if $_GET['userID'] is false then we revert to using the userID that is stored in $_SESSION['userRecipes']['userID']
                                since it is the admin that is viewing someone elses recipes, so that users id is temporarily stored in the session'
                                variable until it is reset when the admin gets out of the userlist / userdetails page.
                                */
                            $html .= 'id='.$_SESSION['userRecipes']['userID'].'&pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            }

                            $html .= '<input type="submit" id="backToUserRecipes" name="backToUserRecipes" value="Back to User Recipes" class="orange-button single-button" />'."\n";
                            $html .= '</form>'."\n";
                        } else if($_SESSION['allRecipes'] && !$_POST['deleteButton']) {
                            /*
                                If the previous conditions in this if statement fail to be met, and $_SESSION['allRecipes'] is true, then this link
                                that is generated at the top of the Recipe page will link back to the Recipes Page where all the recipes will be
                                displayed. It will display the last page that the user was on (since that value has been stored in a session
                                variable) and the recipeID is called on as an anchor so that the page will be displayed at the recipe that the user
                                last clicked on so that they can easily take off from where they left off.
                            */
                            $html .= '<form method="post" action="index.php?page=recipes&amp;pageNum='.$_SESSION['pageNum'].'#recipe'.$_GET['id'].'">'."\n";
                            $html .= '<input type="submit" id="backToRecipes" name="backToRecipes" value="Back to Recipes" class="blue-button single-button" />'."\n";
                            $html .= '</form>'."\n";
                        
                        } 
                       
                        return $html;        
                    

                                    
                }

                
                private function previewRecipe() {

                    /*
                    This function is called upon when a user is either adding or editing a recipe. All the recipe information
                    that the user has inputted is laid out like an actual recipe so that the user can double check all the
                    information before finalizing it
                    */

                        $html .= '<div id="recipe">'."\n";
                        $html .= '<h2>'.$_SESSION['newRecipe']['recipeName'].'</h2>'."\n";
                        $html .= '<div id="recipeBlurb">'."\n";              
                        $html .= '<p><em>'.$_SESSION['newRecipe']['recipeDescription'].'</em></p>'."\n";
                        $html .= '</div>'."\n";

                        /*
                        The code first checks to see if the variable $_SESSION['newRecipe']['recipeImage'] has any information in it (if it
                        does it means that the user has either set up a new recipe and uploaded a photo for it OR they're editing an existing
                        recipe and they've CHANGED the photo) If this is the case the newly uploaded photo will be in the temporary folder,
                        thus the img src is written to reflect this, as well as pulling the information from the variable $_SESSION['newRecipe']['recipeImage']
                        which should match up exactly with the filename of the photo in the temp folder.

                        If there is no information in the variable $_SESSION['newRecipe']['recipeImage'], then the code next checks to see if there is an existing
                        photo which had its path pulled from the database in the case of editing an existing recipe. If there is, that file becomes the img src.

                        If either of this variable have no information in them, then no photo has been uploaded initially or currently, so a "No Image"
                        picture becomes the img.
                        */
                        if($_SESSION['newRecipe']['recipeImage']) {
                            $html .= '<img src="tempUploads/'.$_SESSION['newRecipe']['recipeImage'].'" id="mainRecipePic" alt="'.htmlspecialchars($_SESSION['newRecipe']['recipeName']).' Picture"/>'."\n";
                        } else {
                            if($_SESSION['newRecipe']['recipeImagePath']) {
                             $html .= '<img src="'.$_SESSION['newRecipe']['recipeImagePath'].'" id="mainRecipePic" alt="'.htmlspecialchars($_SESSION['newRecipe']['recipeName']).' Picture"/>'."\n";
                             } else {
                                $html .= '<img src="images/noImage2.png" alt="No Image" />'."\n";
                             }
                        }

                        $html .= '<div id="recipeAndMethod">'."\n";
                        $html .= '<div id="recipeIngredients">'."\n";
                        $html .= '<h4>Ingredients</h4>'."\n";
                        $html .= '<ul>'."\n";

                        $confirmedIngredients = $this -> model -> getConfirmedIngredients();

                        if(is_array($confirmedIngredients)) {
                            $html .= $this -> displayConfirmedIngredients($confirmedIngredients);
                        } else {
                            $html .= '<p>No Ingredients selected.</p>';
                        }

                        $html .= '</ul>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div id="method">'."\n";
                        $html .= '<h4>Method</h4>'."\n";
                        $html .= '<p>'.nl2br($_SESSION['newRecipe']['recipeMethod']).'</p>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div class="clearDiv"></div>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '<div id="previewOptions">'."\n";
                        $html .= '<ul>'."\n";

                        /*
                            if $_SESSION['updateRecipe'] is true then the links are customized for the user
                            to go back to the different steps for editing an existing recipe.

                            If $_SESSION['updateRecipe'] is false, the only other option for the code to run the previewRecipe
                            code is if its a brand new recipe thats to be previewed, so the code is set up to provide the links
                            to the go back to the seperate steps on the addRecipe View.
                        */
                        if($_SESSION['updateRecipe'] == true) {
                        $html .= '<li><a href="index.php?page=editRecipe&amp;step=one">Step 1</a></li>'."\n";
                        $html .= '<li><a href="index.php?page=editRecipe&amp;step=two">Step 2</a></li>'."\n";
                        $html .= '<li><a href="index.php?page=editRecipe&amp;step=three">Step 3</a></li>'."\n";
                    } else {
                        $html .= '<li><a href="index.php?page=addRecipe&amp;step=one">Step 1</a></li>'."\n";
                        $html .= '<li><a href="index.php?page=addRecipe&amp;step=two">Step 2</a></li>'."\n";
                        $html .= '<li><a href="index.php?page=addRecipe&amp;step=three">Step 3</a></li>'."\n";
                    }

                    /*
                            Again,if $_SESSION['updateRecipe'] is true then the links are customized for the user
                            to finalized the edits they've performed on an existing recipe.

                            If $_SESSION['updateRecipe'] is false the code is set up to provide the links
                            to finalize or cancel the brand new recipe they've written up
                        */
                        

                        if($_SESSION['updateRecipe'] == true) {
                            $html .= '<li><a href="index.php?page=userPanel&amp;cancelledRecipe=true">Cancel Update</a></li>'."\n";
                            $html .= '<li><a href="index.php?page=recipe&amp;updatedRecipe=true">Confirm Update</a></li>'."\n";
                        } else {
                            $html .= '<li><a href="index.php?page=userPanel&amp;cancelledRecipe=true">Cancel Recipe</a></li>'."\n";
                            $html .= '<li><a href="index.php?page=recipe&amp;uploadedRecipe=true">Confirm Recipe</a></li>'."\n";
                        }

                        
                        $html .= '</ul>'."\n";
                        $html .= '</div>'."\n";              
                        $html .= '</div>'."\n";
                       
                        return $html;        
                    }

                          

        private function displayConfirmedIngredients($confirmedIngredient) {
        
            /*
            This function takes all the ingredients for the recipe that has been passed to it through the variable
            $confirmedIngredient, loops through them all and at the same time runs the pluralIngredientsAndMeasurements()
            function to ensure the correct and logical formation of ingredients, quantities and measurements, as well
            as using plurals where required. (e.g 1 Cup Flour - compared to - 2 Cup(s) Flour)
            */

        $html = '';

        foreach($confirmedIngredient as $confirmed) {

            $html .= '<li>';
            $html .= $this -> model -> pluralIngredientsAndMeasurements($confirmed['measurement'], $confirmed['quantity'], $confirmed['extraInfo'], $confirmed['ingredient']);
            $html .= '</li>';
        }
        
        return $html;
        
        
    }


}


?>
