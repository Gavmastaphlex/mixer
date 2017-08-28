<?php

/*
    // will contain any processing of page information either
    //before it goes into the database or after..
    //e.g. validation, uploading/resizing of images, e.t.c
*/

include 'classes/dbClass.php';
include 'classes/uploadClass.php';
include 'classes/resizeClass.php';

class Model extends Dbase {
    
    public $adminLoggedIn;
    public $userLoggedIn;
    public $loginMsg;
    private $validate;
    
    public function __construct() {
        parent::__construct();
        
        $validationPages = array('addProduct', 'editProduct', 'editPage', 'checkout');
        
        if(in_array($_GET['page'], $validationPages)) {
            include 'classes/validateClass.php';
            $this -> validate = new Validate;
        } 
        
    }


    public function checkUserSession() {
        
        /*
        If the user has logged out we reset every single variable and array
        that was linked to them so theres no risk of unauthorize access
        */
        if($_POST['logoutButton']) {
            unset($_SESSION['userName']);
            unset($_SESSION['userAccess']);
            unset($_SESSION['userID']);
            unset($_SESSION['userFirstName']);
            unset($_SESSION['confirmed']);
            unset($_SESSION['newRecipe']);
            unset($_SESSION['updateRecipe']);
            unset($_SESSION['finalized']);
            $this -> userLoggedIn = false;
            $this -> adminLoggedIn = false;
            $this -> loginMsg = 'You have successfully logged out.';
            
        } 


        /*
        Running a query that checks the users login name and password with
        whats stored in the database to check they've entered the right username
        and password, and if they have, setting the appropriate sessions to signify
        that they are now logged in.

        Conversely generating validation messages if the login process fails.
        */
        if($_POST['loginButton']) {
            if($_POST['userName'] && $_POST['userPassword']) {
                $this -> loggedIn = $this -> validateUser();
                if($this -> loggedIn == false) {
                    $this -> loginMsg = 'Login Failed!';   
                }               
                
            } else {
                $this -> loginMsg = 'Please enter a username and password!';   
            }
            
        //check to see if the login form was submitted
        }   

            /*
            Setting consistant elements that we can constantly check against whenever
            double checking whether a user is authorized to a particular part of the
            website that they may attempt to gain access too, or just to then display
            information thats appropriate for them.
            */            
            if($_SESSION['userAccess'] == 'admin') {
                $this -> adminLoggedIn = true;  
            } else if($_SESSION['userAccess'] == 'user') {
                $this -> userLoggedIn = true; 
            }
                   
    }   


    private function validateUser() {
        
        //validate
        
        //try to login
        $user = $this -> getUser();
        
        if(is_array($user)) {
            $_SESSION['userName'] = $user['userName'];
            $_SESSION['userAccess'] = $user['userAccess'];
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userFirstName'] = $user['userFirstName'];
            //userID
            header("location:index.php?page=userPanel");

        } else {
            return false;  
        }        
        
    }

    /*
    Running the values passed through the Register Form to the validation class to check
    that they meet the guidelines imposed on names and emails for instance.
    */    
    public function validateRegisterForm() {
        include 'validateClass.php';
        $validate = new Validate; 

        $vresult['firstNameMsg'] = $validate -> checkName($_POST['registerFirstName']);
        $vresult['lastNameMsg'] = $validate -> checkName($_POST['registerLastName']);
        $vresult['emailMsg'] = $validate -> checkEmail($_POST['registerEmail']);
        $vresult['usernameMsg'] = $validate -> checkName($_POST['registerUserName']);      

        if($_POST['registerConfirmPassword']) {

            $vresult['passwordMsg'] = $validate -> checkRequired($_POST['registerPassword']);

                if($_POST['registerConfirmPassword'] != $_POST['registerPassword']) {
                $vresult['confirmPasswordMsg'] = 'That password did not match';
            }

        } else if($_POST['updateDetailsBtn']) {
            $vresult['confirmPasswordMsg'] = $validate -> checkRequired($_POST['confirmPassword']);
        }

        if($_POST['registerUserName'] != ($_SESSION['userName'])) {
            $vresult['usernameExistsMsg'] = $this -> validateUserName($_POST['registerUserName']);
        }

        $vresult['ok'] = $validate -> checkErrorMessages($vresult);


        if ($vresult['ok'] == true && $_POST['registerBtn']) {
            $newUser = $this -> addNewUser($_POST['registerUserName'], $_POST['registerPassword'], $_POST['registerFirstName'], $_POST['registerLastName'], $_POST['registerEmail']);


        } else if ($vresult['ok'] == true && $_POST['updateDetailsBtn']) {

            $rightPassword = $this -> checkUserPasswordById($_SESSION['userID'], $_POST['confirmPassword']);

            if($rightPassword == true) {
                $vresult['ok'] = $this -> updateUser($_SESSION['userID'], $_POST['registerUserName'], $_POST['registerFirstName'], $_POST['registerLastName'], $_POST['registerEmail']);

            } else {
                $vresult['confirmPasswordMsg'] = 'The confirmation password did not match';
                $vresult['ok'] = false;
            }

        }

        return $vresult;       
        
    }

    /*
        if the user is trying to change their password, checking the old password they've
        entered with the password thats stored in the database, as well as checking that the
        two times the user entered the new password matched up with each other correctly.
        */
    public function validatePasswordChange() {

        $rightPassword = $this -> checkUserPasswordById($_SESSION['userID'], $_POST['oldPassword']);

        if($rightPassword == true) {

        if($_POST['newPassword'] == $_POST['confirmPassword']) {

                    $vresult['ok'] = $this -> updatePasswordById($_SESSION['userID']);
            
            } else {
                $vresult['confirmPasswordMsg'] = 'The confirmation password did not match';
            }

    } else {
        $vresult['oldPasswordMsg'] = 'The old password did not match';
    }

    return $vresult;

}

    public function processNewIngredient() {

            /*
            This function checks that the New Ingredient has all valid characters.
            */ 
            $vresult = $this -> validateIngredient();


            if($vresult['ok'] == false) {
                return $vresult;
                
            } else {
                /*
                a.k.a if $vresult['ok'] == true,
                */

                /*
                This function checks that the New Ingredient entered by the user does not match any existing
                ingredient that is already in the database. If it does it generates a validation message
                to inform the user appropriately.
                */ 
                $niresult = $this -> validateNewIngredient();

                 if($niresult['ok'] == false) {
                    $niresult['newIngredMsg'] = 'Ingredient already exists!';

                 } else {
                    /*
                    If everythings ok then the first letter of the new ingredients is turned into a capital 
                    letter (if it is not already) and then it is added to $_SESSION['newIngredient']. It is only
                    once the user has completely finalized the recipe that the New Ingredient is then added to the
                    database.
                    */

                    $ingredientName = ucfirst($_POST['newIngredientName']);
                    $_SESSION['newIngredient'][$ingredientName] = $ingredientName;
                    $niresult['newIngredMsg'] = 'Ingredient added to New Ingredient.';

                 }
                return $niresult;

            }

            
        }


    public function validateIngredient() {

        /*
        This function checks that the New Ingredient has all valid characters.
        */ 

        include 'validateClass.php';
        $validate = new Validate;  

        $niresult['notStringMessage'] = $validate -> checkValidCharacters($_POST['newIngredientName']);

        $niresult['ok'] = $validate -> checkErrorMessages($niresult);
        
        return $niresult;
        
    }


    public function checkForMatchedRecipes() {
        $confirmedIngredients = array();

                //if there's been ingredients logged through the ingredient selecting process on the Home Screen:
                foreach($_SESSION['finalized'] as $id => $ingredient) {

                $ingredientInfo['ingredientID'] = $ingredient['ingredientID'];                
                $ingredientInfo['ingredient'] = $ingredient['ingredientName'];
                $confirmedIngredients[] = $ingredientInfo;
                
                     }

                     /*
                     After this foreach loop runs, the ingredients that are stored in the $_SESSION['finalized'] are stored in
                     a multidimensional array.

                     Here is an example of the results returned inputting Cheese, Egg, Spaghetti & Beef Strip :

                         echo '<pre>';
                         print_r($confirmedIngredients);
                         echo '</pre>'; --------
                                                |
                                                |   
                                                |
                                                |
                                                V
                        Array
                            (
                                [0] => Array
                                    (
                                        [ingredientID] => 1
                                        [ingredient] => Egg
                                    )

                                [1] => Array
                                    (
                                        [ingredientID] => 6
                                        [ingredient] => Cheese
                                    )

                                [2] => Array
                                    (
                                        [ingredientID] => 48
                                        [ingredient] => Spaghetti
                                    )

                                [3] => Array
                                    (
                                        [ingredientID] => 21
                                        [ingredient] => Beef Strip
                                    )
                            )
    
                     */

                            $result = array();
                            //initializing an array called $result

                            foreach($confirmedIngredients as $confirmed) {

                                 $rr = $this -> mixItUp($confirmed['ingredientID']);

                                 /*
                                 This foreach loop takes the inputted ingredients from the user and checks them with all the
                                 ingredients that are matched against recipes in the database, by running them through the
                                 recipeingredients table in the database.
                                 */

                                 if(is_array($rr)) {
                                    $result[] = $rr;

                                    /*
                                    If an ingredient that the user has inputted DOESN'T MATCH UP WITH ANY RECIPES IN THE DATABASE
                                    (i.e Beef Strips isn't assigned to any recipes) then when Beef Strips Ingredient Id is run by the
                                    foreach loop, the result that is retrned and held in $rr won't be an array therefore that ingredient
                                    WON'T be added to the results array. ONLY INGREDIENTS THAT ARE IN AT LEAST ONE RECIPE WILL BE HELD
                                    IN THE $result array
                                    */

                                     }

                                }

                    /*
                     Here is what is contained in the $result array after the above code is executed.

                    Notice that the first element of the array holds 2 values, this is because the ingredient
                    that was being checked in the database at the time (Egg) is included in :

                     Spaghetti Cupcakes (recipeID - 21)
                     Bob's special recipe (recipeID - 29)

                    The other two inputted ingredients (Spaghetti & Cheese) only are in the Spaghetti Cupcake Recipe
                    (recipeID - 21) and no other recipe, so they only have the one value that is being returned in
                    their seperate elements, which equals the Spaghetti Cupcake recipeID of 21.

                        echo '<pre>';
                        print_r($result);
                        echo '</pre>'; --------
                                                |
                                                |   
                                                |
                                                |
                                                V
                        Array
                                (
                                    [0] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [recipeID] => 21
                                                )

                                            [1] => Array
                                                (
                                                    [recipeID] => 29
                                                )

                                        )

                                    [1] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [recipeID] => 21
                                                )

                                        )

                                    [2] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [recipeID] => 21
                                                )

                                        )

                                )
    
                     */


                        $recipeIDiterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($result));
                        /*
                        The above line of code in effect explodes the array, bringing all the buried results
                        in the varied elements to one top layer.

                        See the below comments to show the outputted information from this line of code
                        */
                        $recipeIDArray = array();


                        foreach($recipeIDiterator as $key=>$value) {
                            
                                $recipeIDArray[] = $value;

                                /*
                                    echo '<pre>';
                                    print_r($recipeIDArray);
                                    echo '</pre>'; 
                                                    |
                                                    |
                                                    |
                                                    |
                                                    V

                                                Array
                                                (   
                                                    [0] => 21
                                                    [1] => 29
                                                    [2] => 21
                                                    [3] => 21
                                                )

                                    This shows that the recipe with the recipeID of 21 (Spaghetti Cupcakes)
                                    has been picked up 3 times based on the ingredients that the user has
                                    previously inputted, and the recipe with the recipeID of 29 (Bob's special recipe)
                                    has only been picked up once.
                                */


                            $recipeIDInstances = (array_count_values($recipeIDArray));

                            /*
                                    This code lumps the different instances of each recipeID that are in the
                                    recipeIDArray together into seperate elements that are headed by that
                                    particular recipeID, and the value of these new elements is the number of
                                    instances that the recipeID appeared in the recipeIDArray.

                                    echo '<pre>';
                                    print_r($recipeIDInstances);
                                    echo '</pre>';
                                          |
                                          |
                                          V

                                    Array
                                    (
                                        [21] => 3
                                        [29] => 1
                                    )
                            */

                            $uniqRecipeID = array_unique($recipeIDArray);

                            /*

                            The "array_unique" function now takes the recipeIDs in the previous $recipeIDArray
                            array and strips away any duplicates of recipeIDs that appear in the original array, so what
                            is left is an array with the seperate recipeIDs that have been matched with the
                            inputted ingredients and now there is only one instance of each recipeID.

                                echo '<pre>';
                                print_r($uniqRecipeID);
                                echo '</pre>';  
                                        |
                                        |
                                        V

                                        Array
                                        (
                                            [0] => 21
                                            [1] => 33
                                        )

                            */


                            foreach ($uniqRecipeID as $uniqRecipe) {

                                        $recipeID = $uniqRecipe;
                                        $ingredientCount = $this -> countRecipeIngredients($uniqRecipe);

                                        /*
                                            The above function now takes the seperated recipeID's from the $uniqRecipeID array,
                                            and performs a search to find out the number of ingredients contained in that particular
                                            recipe. It will only output a number, and not any other information, which will then
                                            be used below.

                                        */

                                        $countIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($ingredientCount));

                                        foreach($countIterator as $key=>$value) {
                                            $count = $value;

                                        /*
                                            echo '<pre>';
                                            print_r($count);
                                            echo '</pre>'; 


                                        */


                                        }

                                        
                                        if($recipeIDInstances[$recipeID] == $count) {

                                            /*
                                                Now a check is going to be performed to see if the number of times
                                                that the recipeID has been called by using that value called from line 201 - 

                                                $recipeIDInstances = (array_count_values($recipeIDArray))

                                                matches the number of ingredients that that particular recipe includes
                                                by using the query on line 251 to find out the ingredient count for
                                                that recipe.

                                                $ingredientCount = $this -> model -> countRecipeIngredients($uniqRecipe);

                                                If it does then it has been confirmed that all the ingredients from that
                                                recipe HAS been entered by the user in the MixItUp search box, and thus
                                                that recipe needs to then be displayed.

                                            */

                                                    $_SESSION['mixItUp'][$recipeID] = $recipeID;

                                            /*
                                                The code above is loading the $_SESSION with the recipes that has
                                                been confirmed the user has all the ingredients for, to be then displayed
                                                by the function that will call them
                                            */

                                                } 
                                                     
                                         }          
                                   }
    }


public function confirmIngredient() {

    $newIngSes = $_SESSION['newIngredient'];
    $newIng = $_POST['formNewIngredientsSelect'];
    $existIng = $_POST['formIngredientsSelect'];
    $quantity = strip_tags($_POST['formMeasurement']);
    $measure = $_POST['formMeasurementSelect'];
    $extra = strip_tags($_POST['formExtraInfo']);

        if($newIngSes && $newIng == '0' && $existIng != '0' || $newIngSes && $newIng != '0' && $existIng == '0' || !$newIngSes || $newIng == '0' && $existIng == '0') {

                if($existIng != '0' && $quantity || $existIng != '0' && $quantity && $measure != '0' || $newIng != '0' && $quantity || $newIng != '0' && $quantity && $measure != '0') {

                //$id = $this -> validateConfirmedIngredient();

                    if($existIng != '0') {
                        $name = $existIng;
                    } else if ($newIng != '0') {
                        $name = $newIng;
                    }

                if(!$this -> confirmedIngredients($name)) {
                    $_SESSION['confirmed'][$name] = array(

                        'ingredientName' => stripslashes($name),
                        'quantity' => stripslashes($quantity),
                        'measurement' => stripslashes($measure),
                        'extraInfo' => stripslashes($extra)
                        );
                    $result['ok'] = true;
                    }

                } else {
                    $result['ok'] = false;
                    $result['confirmMsg'] = 'Please select at least an ingredient and quantity';

                }

            } else {
                $result['ok'] = false;
                $result['doubleErrorMsg'] = 'Please select only one ingredient!';
            }

        return $result;
    }

    public function confirmedIngredients($recipeID) {
        if(isset($_SESSION['confirmed'][$recipeID])) {
            return true;
        } else {
            return false;
        }
    }

    public function getConfirmedIngredients() {

        $confirmedIngredients = array();
        
        if($_SESSION['confirmed']) {
            
            foreach($_SESSION['confirmed'] as $id => $ingredient) {
               
                $ingredientInfo['ingredient'] = $ingredient['ingredientName'];
                $ingredientInfo['quantity'] = $ingredient['quantity'];
                $ingredientInfo['measurement'] = $ingredient['measurement'];
                $ingredientInfo['extraInfo'] = $ingredient['extraInfo'];
                $confirmedIngredients[] = $ingredientInfo;
            }
            return $confirmedIngredients;
        } else {
            return false;   
        }
    }

    public function pluralIngredientsAndMeasurements($measurement, $quantity, $extraInfo, $ingredient) {

        if($ingredient == 'Strawberry' && $quantity != 1 || $ingredient == 'Strawberry' && $measurement == 'Cup') {

            $ingredient = 'Strawberries';
        }

        if($measurement == 'kg' && $quantity != '1' || $measurement == 'Clove' && $quantity != '1' || $measurement == 'Cup' && $quantity != '1' || $measurement == 'Tsp' && $quantity != '1'  || $measurement == 'Tbsp' && $quantity != '1'  || $measurement == 'Can' && $quantity != '1'  || $measurement == 'Packet' && $quantity != '1'   || $measurement == 'Rasher' && $quantity != '1'  || $measurement == 'Litre' && $quantity != '1'  || $measurement == 'Jar' && $quantity != '1'  || $measurement == 'Slice' && $quantity != '1'){
                            if($quantity == '1' || $quantity == '1/2' || $quantity == '1/3' || $quantity == '1/4' || $quantity == '1/8' || $quantity == '3/4' || $quantity == '2/3') {
                                                $result = ''.$quantity.' '.$measurement.' '.$extraInfo.' '.$ingredient.''; 
                            } else {
                                if($ingredient == 'Beans' || $ingredient == 'Chickpeas' || $ingredient == 'Baked Beans' || $ingredient == 'Corn Chips') {
                                    $result = ''.$quantity.' '.$measurement.' '.$extraInfo.' '.$ingredient.''; 
                                } else {
                                    $result = ''.$quantity.' '.$measurement.'s '.$extraInfo.' '.$ingredient.'';  
                                }                                
                            }
        } else {
        
        if($measurement == '0' || !$measurement) {
                            if($quantity == '1' || $quantity == '1/2' || $quantity == '1/3' || $quantity == '1/4' || $quantity == '1/8' || $quantity == '3/4') {
                                if($measurement) {
                                    $result = ''.$quantity.' '.$measurement.' '.$extraInfo.' '.$ingredient.''; 
                                } else {
                                    $result = ''.$quantity.' '.$extraInfo.' '.$ingredient.'';
                                }     
                            } else {
                                if($ingredient == 'Potato' || $ingredient == 'Tomato'){
                                    $result = ''.$quantity.' '.$extraInfo.' '.$ingredient.'es';
                                    } else {
                                    $result = ''.$quantity.' '.$extraInfo.' '.$ingredient.'s';
                                }
                            }
                        } else {
                            $result = ''.$quantity.' '.$measurement.' '.$extraInfo.' '.$ingredient.'';  
                        }
                     }

                        return $result;
    }

    public function validateRecipeForm() {

        include 'validateClass.php';
        $validate = new Validate;  

        $vresult = array();

        if($_POST['form'] == 'One') {
            $vresult['recipeNameMsg'] = $validate -> checkRequired($_POST['recipeName']);
        }

        if($_POST['form'] == 'Two') {
            if(!$_SESSION['confirmed']) {
                $vresult['ingredientsMsg'] = 'You need to select at least one ingredient!';
            }
        }

        if($_POST['form'] == 'Three') {
            $vresult['recipeMethodMsg'] = $validate -> checkRequired($_POST['recipeMethod']);
        }

        $vresult['ok'] = $validate -> checkErrorMessages($vresult);
        
        return $vresult;

    }

    public function uploadAndResizeImage() {
        
        $imgPath = 'tempUploads';
        $thumbImgsPath = 'tempUploads/thumbnails';
        
        if(!$_FILES['recipeImage']['name']) {
            return false;
        }
        
        //file types
        $fileTypes = array('image/jpeg','image/jpg','image/png','image/gif');
        //file name, file types, folder path
        $upload = new Upload('recipeImage', $fileTypes, $imgPath);
        
        $returnFile = $upload -> isUploaded();
        
        if(!$returnFile) {
            $result['uploadMsg'] = $upload -> msg;
            $result['ok'] = false;
            return $result;
        }
        
        //if we are this point, the image should have uploaded
        //should be on our server. 'images/products'
        //resize it
        
        $fileName = basename($returnFile);
        $bigPath = $imgPath.'/'.$fileName;
        $thumbPath = $thumbImgsPath . '/'.$fileName;
        
        copy($returnFile, $thumbPath);
        
        if(!file_exists($thumbPath)) {
            return false;
        }
        
        $imgInfo = getimagesize($returnFile);
        
        if($imgInfo[0] > 175 || $imgInfo[1] > 175) {
            $resizeObj = new ResizeImage($thumbPath, 175, $thumbImgsPath, '');
            if(!$resizeObj->resize()) {
                echo 'Unable to resize image to 150 pixels';
            }
        }
        
        //resize big image now
        rename($returnFile, $bigPath);
        
        if($imgInfo[0] > 400 || $imgInfo[1] > 400) {
            $resizeObj1 = new ResizeImage($bigPath, 400, $imgPath, '');
            if(!$resizeObj1 -> resize()) {
                echo 'Unable to resize image to 400 pixels';
            }          
        }
        
        if(file_exists($thumbPath) && file_exists($bigPath)) {
            $result['recipeImage'] = basename($thumbPath);
            $result['ok'] = true;
            return $result;
        } else {
            return false;
        }
                
    }

    public function uploadRecipe() {


            /*
            First all the New Ingredients that are stored in $_SESSION['newIngredient'] are inserted into the
            database since they're all obviously needed in the confirmed recipe.

            */
            if($_SESSION['newIngredient']) {

                foreach($_SESSION['newIngredient'] as $newIngredient) {
                    $this -> newIngredient($newIngredient);
                }

            }
            
            /*
            Then all the rest of the recipe values that have been collected in the Add Recipe process
            are stores in easier to read variables.
            */
            $userID = $_SESSION['userID'];
            settype($userID, "integer");    
            $recipeName = $_SESSION['newRecipe']['recipeName'];
            $recipeImage = $_SESSION['newRecipe']['recipeImage'];
            $recipeDescription = $_SESSION['newRecipe']['recipeDescription'];
            $recipeMethod = $_SESSION['newRecipe']['recipeMethod'];

            /*
            First the main information is inserted into the Recipes table.
            */  
            $this -> insertRecipeInfo($userID, $recipeName, $recipeImage, $recipeDescription, $recipeMethod);

            /*
            Now we query the newly created entry to obtain the newly created recipeID so as to log the confirmed
            ingredients against that recipeID in the recipeIngredients table.
            */
            $recipeIDResult = $this -> getRecipeID($recipeName);

            /*
            Now storing all the confirmedIngredients into the variable $confirmedIngredients and running a foreach loop to insert
            each ingredient into the recipeIngredients table one at a time.
            */
            $confirmedIngredients = $this -> getConfirmedIngredients();

            if(is_array($confirmedIngredients)) {
                foreach($confirmedIngredients as $confirmed) {
                    $ingredientIDResult = $this -> getIngredientID($confirmed['ingredient']);
                    $measurementIDResult = $this -> getMeasurementID($confirmed['measurement']);
                    $this -> insertIngredientInfo($recipeIDResult, $ingredientIDResult, $confirmed['quantity'], $measurementIDResult, $confirmed['extraInfo']);
                }
            }

            /*
            If $recipeImage != '' (in other words, if it DOES EXIST) then at this point
            we run the function in order to move it from the temporary folder into the
            uploads folder.
            */
            if($recipeImage != '') {
                $this -> moveMainImage($recipeImage);
                $this -> moveThumbnail($recipeImage);
            }

            return true;

         }

         /*
            The function that copies the main recipe image from tempuploads to uploads and then
            deletes the original picture file.
         */
        public function moveMainImage($recipeImage) {
            $old = 'tempUploads/'.$recipeImage.'';
            $new = 'uploads/'.$recipeImage.'';

            if (copy($old,$new)) {
                  unlink($old);
                }
        }

        /*
            The function that copies the thumbnail recipe image from tempuploads/thumbnails to uploads/thumbnails and then
            deletes the original picture file.
         */
        public function moveThumbnail($recipeImage) {
            $old = 'tempUploads/thumbnails/'.$recipeImage.'';
            $new = 'uploads/thumbnails/'.$recipeImage.'';

            if (copy($old,$new)) {
                  unlink($old);
                }
        }

        
        public function validateContactForm() {
        include 'validateClass.php';
        $validate = new Validate; 

        $vresult['titleMsg'] = $validate -> checkLength($_POST['contactTitle']);
        $vresult['firstNameMsg'] = $validate -> checkName($_POST['contactFirstName']);
        $vresult['lastNameMsg'] = $validate -> checkName($_POST['contactLastName']);
        $vresult['emailMsg'] = $validate -> checkEmail($_POST['contactEmail']);
        $vresult['messageMsg'] = $validate -> checkMessageLength($_POST['contactMessage']);

        $vresult['ok'] = $validate -> checkErrorMessages($vresult);

        

        if ($vresult['ok'] == true) {
            $vresult['emailSent'] = $this -> sendEmail();
        }      

        return $vresult;
    }

    /*
    Generating an email with the information that has been passed through from the contact form.
    */

    public function sendEmail() {

    $to = "Gavin.McGruddy@hotmail.com";

    $subject = "Message from Mixer website";

    $body = 'Someone has sent an enquiry through the contact form.'."\n";

    $body .= 'Title: '.$_POST['contactTitle']."\n";
    $body .= 'First Name: '.$_POST['contactFirstName']."\n";
    $body .= 'Family Name: '.$_POST['contactLastName']."\n";
    $body .= 'Email: '.$_POST['contactEmail']."\n";

    $body .= stripslashes(strip_tags($_POST['contactMessage']));

    $headers = 'From: '.$_POST['contactTitle'].' '.$_POST['contactFirstName'].' '.$_POST['contactLastName'].' <'.$_POST['contactEmail'].'>';

    $sent = mail($to, $subject, $body);

    return true;

    }
    
} 

?>