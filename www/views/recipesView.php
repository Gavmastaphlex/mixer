<?php

class RecipesView extends View {

            
    protected function displayContent() {

        /*
        If $_GET['recipes'] is true, that means the user has requested to view ALL RECIPES,
        so we have need of the session ids that hold the information for the mixer search.
        */
        if($_GET['recipes']) {
            unset($_SESSION['mixItUp']);
            unset($_SESSION['finalized']);
        }

        /*
        The below parameters are all generated through either the "Add New Recipe" or "Edit Recipe" pages,
        so if they've viewing the "Recipes" page, it means that that they've aborted the process and
        therfore we can reset all the parameters ready for when they commence it again another time.
        */

            unset($_SESSION['confirmed']);
            unset($_SESSION['newRecipe']);
            unset($_SESSION['updateRecipe']);
            unset($_SESSION['newIngredient']);

            /*
            Checking to see if the user has pressed that "Mix It Up" button before caryying out the function that
            will check for matched recipes.
            */
            if($_POST['mixItUp'] || $_GET['mixerSearch'] == true || $_POST['backToMixerResults']) {

                /*
                $_SESSION['finalized'] holds any ingredients that were inputted on the home screen. If it is false, that means
                that no ingredients were selected
                */
                if($_SESSION['finalized']) {

                    $this -> model -> checkForMatchedRecipes();
                    
                }
            }

        
        /*
            The following conditions are checking to see if only one users recipes are going to be displayed
        */
        if(isset($_GET['id']) || $_GET['uploadedRecipe'] || $_GET['updatedRecipe'] || $_GET['browseUserRecipes'] == true) {
            //get the record from database

            unset($_SESSION['allRecipes']);

            $_SESSION['userRecipes']['userID'] = $_GET['id'];

            $html = '';

           
           /*
            Checking to see if the admin has clicked on Browse User Recipes from the User List Page.
            If so then we're running a query to get that particular users recipes displayed
           */

            if ($_GET['browseUserRecipes'] == true) {

                /*
                If $_GET['browseUserRecipes'] is true, then the userID of the user whos recipes we are going to be
                looking at will also be contained in that variable, so that is going to be stored in the $_SESSION
                variable of $_SESSION['selectedUserRecipes']['userID'] so that we can retain it and consistently
                display the chosen users recipes until the viewing user (which will be admin) brings up a page
                that unsets the variable ready for when they view a different users recipes.
                */
                $_SESSION['selectedUserRecipes']['userID'] = $_GET['browseUserRecipes'];

                $user = $this -> model -> getUserByID($_GET['browseUserRecipes']);

                $html .= '<h2>'.$user['userName'].'s Recipes</h2>'."\n";
                } else {
                    $html .= '<h2>Your Recipes</h2>'."\n";
                }

                /*
                If a recipe has either been updated or initially uploaded, run an IF statement to generate a success message.
                */
                
                if($_GET['uploadedRecipe'] == true) {
                    $html .= '<h3>Your recipe has been succesfully uploaded.</h3>'."\n";
                } else if ($_GET['updatedRecipe'] == true) {
                    $html .= '<h3>Your recipe has been successfully updated.</h3>'."\n";
                }

                /*
                Giving the user the option to go back to the page that they've just come from that displays all the chosen users details,
                rather than going back to the User Panel and having to negotiate there way back to the same screen.
                */
                if ($_GET['browseUserRecipes'] == true) {
                    $html .= '<a href="index.php?page=userList&amp;id='.$user['userName'].'" id="backToUserDetails">Back to User Details</a>'."\n";
                }

                $html .= '<div id="searchSummary">'."\n";

                /*
                If $_GET['browseUserRecipes'] is true, then the admin has chosen another users recipes to look at,
                in all other cases, the recipes that will be shown will be of the user who is viewing the page,
                whether that is admin or another registered user.
                */

                if($_GET['browseUserRecipes'] == true) {
                    $userRecipeIDs = $this -> model -> getUserRecipesIDs($_GET['browseUserRecipes']);
                } else {
                    $userRecipeIDs = $this -> model -> getUserRecipesIDs($_SESSION['userID']);
                }
                   

                /*
                Checking to see if the user has any recipes stored in the database if they don't then $userRecipeIDs
                won't be an array since there was nothing returned to it.
                */
                if(is_array($userRecipeIDs)) {

                $userRecipeCount = count($userRecipeIDs);

                
                $count = array();

                //running a for each loop in order to seperate the different recipe ID that were returned from the getUserRecipesID query.
                foreach($userRecipeIDs as $value) {

                    $recipeIDs[] = $value['recipeID'];

                }

                /*
                the following query breaks up the returned recipe IDs into segments of 12, since its only 12 recipes at a time that
                are to be displayed on the Recipes page. Then those 12 recipeIDs are run through the database to get the
                information for those 12 recipes. If another page number is chosen, then the next 12 recipeIDs are generated
                and then returned etc.
                */
                $selectedUserRecipes = $this -> selectRecipesToDisplay($recipeIDs);
                                        
                /*
                Now we're going to go to the database to run the 12 (or less) recipe IDs that were chosen in the previous query
                to return the information to then display on the page.
                */
                $this -> recipes = $this -> model -> getMixedRecipes($selectedUserRecipes);

                $recipeInfo = $this -> recipes;

                $html .= $this -> displayRecipes($userRecipeCount, $recipeInfo);
                /*displayRecipes function runs on line 431 - this function runs and displays only the returned recipes of the user
                whose userID has previously been passed through the database to select their recipes to display*/

                $html .= '<div class="clearDiv"></div>'."\n";
                $html .= '</div>'."\n";
            } else {


                /*
                if $userRecipeIDs is not an array, then there wasn't any recipes stored in the database that matched the
                userID that was passed through.
                */
                if($_SESSION['userAccess'] == 'admin') {
                    $html .= '<p>This user hasn\'t uploaded any recipes yet.</p>';
                } else {
                    $html .= '<p>Sorry, you haven\'t uploaded any recipes yet!</p>';
                }

                
                $html .= '<a href="index.php?page=userList">Back to User List</a>'."\n";
                $html .= '<img src="images/noFoodPic.jpg" id="noRecipesImage" alt="Empty Plate Picture" />'."\n";
                $html .= '</div>'."\n";

            } 

            if($userRecipeCount > 12) {

                $html .= $this -> displayPageNumbers($userRecipeCount, $selectedUserRecipes);
                $html .= '<div class="clearDiv"></div>'."\n";
            }


            return $html;


            /*
            Now we're checking to see if the Mix It Up button was pressed, and then subsequently whether any recipes
            were matched by the queries that were then activated, and then if those matched recipes were loaded into
            $_SESSION['mixItUp'], or if there wasn't any matched recipes then we'll still check to see if the $_SESSION['finalized']
            was instantiated to see if the user had selected some ingredients to try to match to a recipe.

            In both cases $_GET['recipes'] will be false since that variable is ONLY passed when the user has selected an option
            that enables them to view EVERY SINGLE recipe, not just the ones that much up with ingredients entered.
            */
        } else if($_SESSION['mixItUp'] && !$_GET['recipes'] || $_SESSION['finalized'] && !$_GET['recipes']) {


            unset($_SESSION['userRecipes']);

            /*
            Checking to see there was any recipes matched to the ingredients that the user inputted (if there was the query
            that is run stores any matched recipes into the $_SESSION['mixItUp'] variable )
            */
            if($_SESSION['mixItUp']) {

                $html = '<h2>Recipe Results</h2>'."\n";
                $html .= '<div id="searchSummary">'."\n";

                $html .= '<div id="searchOptions">'."\n";
                $html .= '<a href="index.php?page=home&mixerReset=true#ingredients" id="newSearchBtn">New Search</a>'."\n";
                $html .= '<a href="index.php?page=home&previousSearch=true#ingredients" id="previousSearchBtn">Previous Search</a>'."\n";
                $html .= '</div>'."\n";
                
                $html .= '<div class="clearDiv"></div>'."\n";
                    
                //Checking to see exactly how many recipes were matched so as display the result for the user.
                $numRecipes = count($_SESSION['mixItUp']);

                $html .= '<p><strong>'.$numRecipes.'';

                if($numRecipes > 1 ) {
                    $html .= ' recipes';
                } else {
                    $html .= ' recipe';
                }

                $html .= ' matched.</strong></p>'."\n";

                /*
                Now running the function that takes all the matched recipes and seperates them into chunks that is
                dependant on how many recipes have been configured to be displayed at one time (in this case 12)
                and the values that are returned to $selectedRecipes will be 12 (or less) recipeIDs that we're
                going to display in this instance
                */
                $selectedRecipes = $this -> selectRecipesToDisplay($_SESSION['mixItUp']);

                /*
                displaying what selection of recipes are being viewed at this time
                e.g Displaying Recipes 13 - 24
                */
                $html .= $this -> displayMixedRecipesViewed($numRecipes);
                
                $html .= '</div>'."\n";

                $html .= '<div id="recipeResultsContainer">'."\n";

                /*
                Getting the rest of the recipe information for the recipes that were selected
                in the previous functions (recipeTitle, recipeDescription etc)
                */
                $this -> recipes = $this -> model -> getMixedRecipes($selectedRecipes);

                /*
                Now running the function to interpret that information and convey it in a logical and
                presentable way for the user.
                */
                $html .= $this -> displayReturnedRecipes($this -> recipes);
                //This function runs on line 399

            } else {
                /*
                In the case where mixItUp is false (because there was no matched results) but $_SESSION['finalized'] is
                true (because the user has selected ingredients and pressed the Mix It Up! button hoping to get a match)
                we let the user know that there were no matched recipes:
                */

                $html = '<h2>No results!</h2>'."\n";
                $html .= '<div id="searchOptions">'."\n";
                $html .= '<a href="index.php?page=home&amp;mixerReset=true#ingredients" id="newSearchBtn">New Search</a>'."\n";
                $html .= '<a href="index.php?page=home&amp;previousSearch=true#ingredients" id="previousSearchBtn">Previous Search</a>'."\n";
                $html .= '</div>'."\n";
                $html .= '<div id="searchSummary">'."\n";
                $html .= '<p>Sorry, there aren\'t any recipes that match those ingredients!</p>';
                $html .= '<a href="index.php?page=home&amp;mixerReset=true#ingredients">Back Home</a>'."\n";
                $html .= '<img src="images/noFoodPic.jpg" id="noResultsImage" alt="Empty Plate Picture" />'."\n";

            }

            $html .= '<div class="clearDiv"></div>'."\n";
            $html .= '</div>'."\n";

            /*
            If theres been more than 12 recipes returned, then the function that enables pagination will be called
            so that only 12 recipes will be displayed at one time and the rest of the recipes will be displayed if
            the user clicks on the link that is generated to take them to the next page.
            */
            if($numRecipes > 12) {

                $html .= $this -> displayPageNumbers($numRecipes);

            }

            $html .= '<div class="clearDiv"></div>'."\n";

            return $html;

        } else if($_POST['mixItUp'] && !$_SESSION['finalized']) {
            /*
            In the scenario where $_POST['mixItUp'] is true (showing the user has pressed the Mix It Up! button)
            but $_SESSION['finalized'] is false (showing that the user hasn't entered any ingredients beforehand)
            we let the user know that they have to enter ingredients AND THEN press the Mix It Up button in order
            for the website to have ingredients to at least try to match with potential recipes.
            */

                    $html = '<h2>No results!</h2>'."\n";
                    $html .= '<div id="searchSummary">'."\n";
                    $html .= '<p>Try entering some ingredients!</p>';
                    $html .= '<a href="index.php?page=home&amp;mixerReset=true#ingredients">Back Home</a>'."\n";
                    $html .= '<img src="images/noFoodPic.jpg" id="noResultsImage" alt="Empty Plate Picture" />'."\n";
                    $html .= '</div>'."\n";

        } else {
            /*
            This last part of the else statement will display all the stored recipes from the database (taking into
            account pagination of course) since in this case there was no searches completed and no user IDs passed
            through, so by eliminating all the other possible outcomes, we know to display all the recipes.
            */

            /*
            we unset this variable in case the user was just viewing a specific user recipes and then clicked
            on the ALL RECIPES navigation option
            */
            unset($_SESSION['userRecipes']);

            $html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";

            $html .= '<div id="searchSummary">'."\n";     

            //-----------------PAGINATION CODE BELOW-------------------------

            //instantiating the limit for the recipes to display on the page
            $limit = 12;


            if(isset($_GET['pageNum'])) {
                /*if $_GET['pageNum']) is set, by multiplying that page number with the limit we
                previously set we get the last recipe that will be displayed on that page, and then
                by subtracting the limit from that returned amount, we get the recipe number that
                is the first recipe to be displayed on that page*/
                $start = $_GET['pageNum'] * $limit - $limit;
                $page = $_GET['pageNum'];

            } else {
                /*if $_GET['pageNum']) is not set, then it must be the users first time coming to the
                the page, so we set the first recipe to be displayed as 0 and page number 1 to be the
                page to be displayed*/
                $start = 0;
                $page = 1;
            }

            //calling the function to see the total of number of recipes in the database
            $recipeCount = $this -> model -> countRecipes();

            //storing that value in the $totalNumRecipes variable
            $totalNumRecipes = $recipeCount[0]['count(recipeID)'];

            /*
            using the variables that were instantiated previously to only return a range of recipes that
            will then be displayed on the Recipes Page that is in line with the limit that was previously
            set on line 326
            */
            $this -> recipes = $this -> model -> getRecipes($start, $limit);

            //Checking to see that there was recipes to display, and then running the function to display the returned recipes
            if(is_array($this -> recipes)) {
                $recipeInfo = $this -> recipes;
                $recipesSelected = $this -> displayRecipes($totalNumRecipes, $recipeInfo);
                $html .= $recipesSelected;

            } else {
                //for some reason there was no recipes returned, informing the user that this is the case

                $html .= '<div id="searchSummary">'."\n";
                $html .= '<p>Sorry, there are no recipes available here! Please try again.</p>';
                $html .= '<a href="index.php?page=home&amp;mixerReset=true#ingredients">Back Home</a>'."\n";
                $html .= '<img src="images/noFoodPic.jpg" id="noResultsImage" alt="Empty Plate Picture" />'."\n";

            }

            $html .= '<div class="clearDiv"></div>'."\n";
            $html .= '</div>'."\n";

            /*if more than 12 recipes are returned, calling the function to generate page numbers
            so that the user can decide which range of 12 recipes he wants to look at
            */
            if($totalNumRecipes > 12) {

                $numRecipesSelected = count($recipesSelected);
                $html .= $this -> displayPageNumbers($totalNumRecipes, $numRecipesSelected);

            }

            $html .= '<div class="clearDiv"></div>'."\n";
            
        } 

        return $html;
    }

        
   

    private function displayReturnedRecipes($recipes) {
        /*
            This function displays each seperate recipe in a seperate clickable box on the recipes page.
            The foreach loop will run until there is no more recipes to return.
            |
            |
            V
            It is used to display recipes that were previously returned by the Mix It Up search.
        */


        // foreach($recipes as $recipe){

        //     $html .= '<div class="recipeResult">'."\n";

        //     $html .= '<h4 class="recipeTitle"><a href="index.php?page=recipe&amp;id='.$recipe['recipeID'].'#backToMixerResults" id="recipe'.$recipe['recipeID'].'">'.$recipe['recipeName'].'</a></h4>'."\n";       

        //     $html .= '<a href="index.php?page=recipe&amp;id='.$recipe['recipeID'].'#backToMixerResults" >'."\n";       

        //     if($recipe['recipeImage']) {
        //         $html .= '<img src="uploads/thumbnails/'.$recipe['recipeImage'].'" class="recipeImage" alt = "'.htmlspecialchars($recipe['recipeName']).' Picture" />'."\n";
        //     } else {
        //         $html .= '<img src="images/recipeView/noImage.png" class="recipeImage" alt="No Image" />'."\n";
        //     }
        //     $html .= '</a>'."\n";

        //     $html .= '</div>'."\n";

        // }

        foreach($recipes as $recipe){
            
            $html .= '<figure class="recipeResult effect-bubba">'."\n";

            if($recipe['recipeImage']) {
                $html .= '<img src="uploads/thumbnails/'.$recipe['recipeImage'].'" class="recipeImage" alt = "'.htmlspecialchars($recipe['recipeName']).' Picture" />'."\n";
            } else {
                $html .= '<img src="images/recipeView/noImage.png" class="recipeImage" alt="No Image" />'."\n";
            }

            $html .= '<a href="index.php?page=recipe&amp;id='.$recipe['recipeID'].'#backToMixerResults" >'."\n";   

            $html .= '<figcaption>'."\n";
            $html .= '<h2 class="recipeTitle">'.$recipe['recipeName'].'</h2>'."\n";       
            $html .= '</figcaption>'."\n";

            $html .= '</a>'."\n";
            $html .= '</figure>'."\n";


        }

        return $html;       
        
    }

    private function displayRecipes($totalNumRecipes, $recipeInfo) {

        /*
        This function displays each recipeID that has been passed through in the parameters
        which will either be

            1 - To display ALL recipes (but only 12 at a time will be passed through due to pagination)

            2 - To only display the already chosen recipes of a specific user that has also been divided
            up into chunks of 12 recipe IDs to display at a time
        */
        
        $html = '';

        /*
        Getting the total number of recipes to display so as to inform the user, and if it
        is over 1 recipe, displaying the plular of recipe (recipes)
        */
        $html .= '<p><strong>'.$totalNumRecipes.'';

        if($totalNumRecipes > 1 ) {
            $html .= ' Recipes ';
        } else {
            $html .= ' Recipe ';
        }

        $html .= 'Total</strong></p>'."\n";

        /*
        The below function is called to ascertain what range of recipes is currently being called
        e.g Recipes 1 - 12 being displayed, or Recipes 13 - 24
        */
        $html .= $this -> displayTotalRecipesViewed($totalNumRecipes);

        $html .= '</div>'."\n";

        $html .= '<div id="recipeResultsContainer">'."\n";

        
        /*
        Looping through each recipe so as to display it
        */
        foreach($recipeInfo as $recipe) {



       $html .= '<figure class="recipeResult effect-bubba">'."\n";

        

        if($recipe['recipeImage']) {
            $html .= '<img src="uploads/thumbnails/'.$recipe['recipeImage'].'" class="recipeImage" alt = "'.htmlspecialchars($recipe['recipeName']).' Picture" />'."\n";
        } else {
            $html .= '<img src="images/recipeView/noImage.png" class="recipeImage" alt="No Image" />'."\n";
        }
        

        $html .= '<a href="index.php?page=recipe&amp;id='.$recipe['recipeID'];

        if(isset($_GET['id']) || $_GET['browseUserRecipes']) {
            $html .= '&amp;userID='.$_GET['browseUserRecipes'].'#backToUserRecipes" ';
        } else {
            $html .= '#backToRecipes" ';
        }

        $html .= 'id="recipe'.$recipe['recipeID'].'">'."\n";

        $html .= '<figcaption>'."\n";

        $html .= '<h2 class="recipeTitle">'.$recipe['recipeName'].'</h2>'."\n";       
        $html .= '</figcaption>'."\n";
        $html .= '</a>'."\n";
        $html .= '</figure>'."\n";


        }   

        return $html;       
        
    }

    private function selectRecipesToDisplay($recipeArray) {

                //PAGINATION FUNCTION

                //Set the limit of recipes to display
                $limit = 12;

                /*
                taking the recipeIDs that have been passed through to the function and using the inbuilt array_chunk 
                function to select whatever the limit we've set (- 12 recipes will be selected)
                */
                $selectedRecipeIDs = array_chunk($recipeArray, $limit);
                
                /*
                Due to splitting an indefinite number of recipes into 12, the last part of the array can have any number
                of recipeIDs in it from 1 - 12 recipeIDs, the below function stores the recipeIDs that are left in the last
                array inside the $selectedRecipeIDs array.
                */
                $lastValues = end($selectedRecipeIDs);

                /*
                And now a count is performed to see how many recipes will be on the last page
                */
                $countLastValues = count($lastValues);

                /*
                These values are now stored into $_SESSION variables
                */
                $_SESSION['lastPage']['pageNum'] = count($selectedRecipeIDs);
                $_SESSION['lastPage']['values'] = $countLastValues;

                /*
                if $_GET['pageNum'] is false, then it will be the users first time to the page so we automatically write
                the code so that they end up naturally on the first page
                */
                if(!$_GET['pageNum']) {
                    $pageNum = 1;
                    $_SESSION['pageNum'] = $pageNum;

                } else {
                    /*
                if $_GET['pageNum'] is true, then they have clicked one of the page numbers and we now need to use the number
                obtained from the $_GET array to make sure the user views the right page, so we store it in the $_SESSION since
                this will be a value that may change as they go between different pages, and having the value stored in the session
                array will mean that if the user goes into a recipe, we can keep track of what page they were on and thus have them
                go back to that page when the go back to the Recipes view
                */
                    $pageNum = $_GET['pageNum'];
                    $_SESSION['pageNum'] = $_GET['pageNum'];
                }

                /*
                Because a numerical array starts at zero, $pageNum is subtracted by one to make
                sure we access the right array that has the right range of recipeIDs stored in it
                */                
                $page = $pageNum - 1;
                $recipeIDs = implode(',', $selectedRecipeIDs[$page]);

                /*
                Now because we have selected the range of recipeIDs that are based on the page number
                that the user has selected (or not selected in case its Page 1) and we have stored those
                recipeIDs inside the variable $recipeIDs, we can now return the range of IDs and then
                query the database to get the information for those particular recipeID's.
                */
                return $recipeIDs;
        }

    private function displayPageNumbers($numRecipes) {

        /*
        Now we want to generate the page numbers so that when there is more than 12 recipes to display
        the user can decide which range they want to view by selecting the page link.
        */

        //first we set the recipe limit for each page.
        $limit = 12;

        /*
        if $_GET['pageNum'] is false, then it will be the users first time to the page so we automatically write
        the code so that they end up naturally on the first page
        */
        if(!$_GET['pageNum']) {
            $pageNum = 1;

        } else {
            /*
            if $_GET['pageNum'] is true, then they have clicked one of the page numbers and we now need to use the number
            obtained from the $_GET array to make sure the user views the right page, so we store it in the $_SESSION since
            this will be a value that may change as they go between different pages, and having the value stored in the session
            array will mean that if the user goes into a recipe, we can keep track of what page they were on and thus have them
            go back to that page when the go back to the Recipes view
            */
            $pageNum = $_GET['pageNum'];
        }

        /*
        By dividing the variable $numRecipes (that is a required parameter to run this function) by the limit that we have previously set,
        AND THEN run that through the preprogrammed "ceil" function (which rounds the answer up the nearest whole number) we obtain the
        total of number of pages that there is going to be.
        */
        $pageAmount = ceil($numRecipes / $limit);

        $html .= '<div id="pageNumbers">'."\n";

        $html .= '<ul>'."\n";

        /*
        Coding the quicklink <<Previous. As long as the user isn't on the first page (because there is no page before page 1!) the
        word "Previous" will appear to the left of the generated page numbers and the pagenumber that it will link to will always
        be configured to be one less than the pageNum that the user is currently on.

        We check to see if the variable $_GET['id'] is set -  to see if we're still required to be viewing a specific users recipes,
        and if we are, we continue to pass the value contained therein so as to keep track of the users ID. If there is no $_GET['id']
        then we don't have to worry, and can assume that we are simply going of every single recipe that is stored in the database.
        */
        if($pageNum != 1) {
            $previousPage = $pageNum - 1;
            if(isset($_GET['id'])) {
            $html .= '<li><a href="index.php?page=recipes&amp;id='.$_GET['id'].'&amp;pageNum='.$previousPage.'#content">Previous</a></li>'."\n";
            } else {
               $html .= '<li><a href="index.php?page=recipes&amp;pageNum='.$previousPage.'#content">Previous</a></li>'."\n"; 
            }
            
        }

        //initial, begin at 1 because you can't have 0 pages, always at least 1 page
        //conditional
        //incremental $i - goes up by one at a time ($i = $i + 2) - going up by two at a time
        for($i = 1; $i <= $pageAmount; $i++) {

            if(isset($_GET['id'])) {
                if($i == $pageNum) {
                    $html .= '<li>'.$i.'</li>'."\n";
                } else {
                    $html .= '<li><a href="index.php?page=recipes&amp;id='.$_GET['id'].'&amp;pageNum='.$i.'#content">'.$i.'</a></li>'."\n";
                }
                
            } else {
                if($i == $pageNum) {
                    $html .= '<li>'.$i.'</li>'."\n";
                } else {
                    $html .= '<li><a href="index.php?page=recipes&amp;pageNum='.$i.'#content">'.$i.'</a></li>'."\n";
                }
                
            }

        }

        if($pageNum != $pageAmount) {
            $nextPage = $pageNum + 1;

            if(isset($_GET['id'])) {
                $html .= '<li><a href="index.php?page=recipes&amp;id='.$_GET['id'].'&amp;pageNum='.$nextPage.'#content">Next</a></li>'."\n";
            } else {
                $html .= '<li><a href="index.php?page=recipes&amp;pageNum='.$nextPage.'#content">Next</a></li>'."\n";
            }

            
        }

        $html .= '</ul>'."\n";

        $html .= '</div>'."\n";

        return $html;
    }

    private function displayMixedRecipesViewed($numRecipes) {
                    $limit = 12;

                    if($numRecipes > $limit) {

                        if(!$_GET['pageNum']) {
                            $pageNum = 1;
                        } else {
                            $pageNum = $_GET['pageNum'];
                        }

                        $pageAmount = $_SESSION['lastPage']['pageNum'];

                        $lastRecipeNum = $limit * $pageNum;

                        $firstRecipeNum = $lastRecipeNum - $limit + 1;

                        $lastPageValues = $_SESSION['lastPage']['values'];

                        $html .= '<br />'."\n";

                        if($_GET['pageNum'] == $pageAmount) {

                            //if()

                            if($lastPageValues == 1) {
                                $html .= '<p>Displaying Recipe '.$firstRecipeNum.'</p>'."\n";
                            } else {
                                $lastRecipeResult = $lastPageValues + $firstRecipeNum - 1;

                                $html .= '<p>Displaying Recipes '.$firstRecipeNum.' - '.$lastRecipeResult.'</p>'."\n";
                            }

                        } else {
                            $html .= '<p>Displaying Recipes '.$firstRecipeNum.' - '.$lastRecipeNum.'</p>'."\n";

                        }

                    }

                    return $html;
    }

    private function displayTotalRecipesViewed($numRecipes) {

                    if(!$_GET['id']) {
                        $_SESSION['allRecipes'] = true;
                    }

                     

                    $limit = 12;

                    if($numRecipes > $limit) {

                        if(!$_GET['pageNum']) {
                            $pageNum = 1;
                            $_SESSION['pageNum'] = $pageNum;
                        } else {
                            $pageNum = $_GET['pageNum'];
                            $_SESSION['pageNum'] = $_GET['pageNum'];
                        }

                        // pageNum = 3

                        //if $_GET['pageNum'] == 3
                        //$limit == 6
                        //$numRecipes == 16

                        $pageAmount = ceil($numRecipes / $limit);

                        // = 2.6, rounded up to 3

                        $firstRecipeNum = $pageNum * $limit - $limit + 1;

                        //1 1*6 (=6) - 6 + 1 (=1)
                        //2 2*6 (=12) - 6 + 1 (=7)
                        //3 3*6 (=18) - 6 + 1 (=13)

                        $lastRecipeNum = $firstRecipeNum + $limit - 1;

                        //1 1+6 (=7) - 1 = 6
                        //2 7+6 (=13) - 1 = 12
                        //3 13+6 (=19) - 1 = 18

                        /*echo '<pre>';
                        print_r($numRecipesLastPage);
                        echo '</pre>';*/ 

                        $html .= '<br />'."\n";

                        if($pageNum == $pageAmount) {

                                $availableResults = $pageAmount * $limit;

                                //3 * 6 = 18

                                $startRecipesLastPage = $availableResults - $limit;

                                //18 - 6 = 12

                                $lastRecipeResult = $numRecipes - $startRecipesLastPage;

                                //16 - 12 = 4

                            if($lastRecipeResult == 1) {
                                $html .= '<p>Displaying Recipe '.$firstRecipeNum.'</p>'."\n";
                            } else {

                                $lastRecipeResult = $numRecipes - $startRecipesLastPage;

                                //16 - 12 = 4

                                $lastPageRecipeAmount = $startRecipesLastPage + $lastRecipeResult;

                                $html .= '<p>Displaying Recipes '.$firstRecipeNum.' - '.$lastPageRecipeAmount.'</p>'."\n";
                            }

                        } else {
                            $html .= '<p>Displaying Recipes '.$firstRecipeNum.' - '.$lastRecipeNum.'</p>'."\n";

                        }

                    }

                    return $html;
    }
            
        
}


?>