<?php
/*
    // the database class will contain all the information
    // about connecting to the database and to query tables
    // on the database
*/

include '../config.php';

class Dbase {
    
    private $db;
    
    public function __construct() {
        
        try {
            
            //host user password database
            $this -> db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);
            
            if(mysqli_connect_errno()) {
                throw new Exception('Unable to establish database connection');   
            }            
            
        } catch(Exception $e) {
            die($e -> getMessage());
        }
        
    }
    
    /*this function retrieves all the page information from the database and in effect alters the viewClass (index.php)
    with the specific content the user has requested*/

    public function getPageInfo($page) {
        
        $qry = "SELECT pageName, pageTitle, pageHeading, pageKeywords, pageDescription, pageContent FROM pages WHERE pageName = '$page'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $pageInfo = $rs -> fetch_assoc();
                return $pageInfo;
            } else {
                echo 'This page does not exist';   
            }            
        } else {
            echo 'Error getPageInfo executing query';
        }
        
    }

    /*This function checks that the inputted username and password entered by a user are correct. If it is, then the function
    acquires all the user details for that user so as to user it for various operations which can only be performed by logged in
    users*/

    public function getUser() {
        
        $this -> sanitizeInput();
        extract($_POST);
        $password = sha1($userPassword);

        $qry = "SELECT userID, userName, userPassword, userAccess, userFirstName FROM users WHERE userName = '$userName' AND userPassword = '$password'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return $user;
            }
            
        } else {
            echo 'Error Executing getUser Query';
        }     
        return false;        
    }

    public function checkUserPasswordById($userID, $userPassword) {

        $qry = "SELECT userID FROM users WHERE userID = '$userID' AND userPassword = SHA1('$userPassword')";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return true;
            } else {
                return false;
            }
            
        } else {
            echo 'Error Executing checkUserPasswordById Query';
        }     
               
    }

    public function updatePasswordById($userID) {
        
        extract($_POST);

        $qry = "UPDATE users SET userPassword = SHA1('".$newPassword."') WHERE userID = '$userID'";

        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                return false;
            }
            
        } else {
            echo 'Error Executing updatePasswordById Query';
        }     
               
    }


    public function getUserByID($userID) {

        $qry = "SELECT userID, userName, userFirstName, userLastName, userEmail, userJoinDate FROM users WHERE userID = '$userID'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return $user;
            }
            
        } else {
            echo 'Error Executing getUser Query';
        }     
        return false;        
    }


    public function getUserByUserName($userName) {

        if(!get_magic_quotes_gpc()) {
            $userName = $this -> db -> real_escape_string($userName);
        }  

        $qry = "SELECT userID, userName, userFirstName, userLastName, userEmail, userJoinDate FROM users WHERE userName = '$userName'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return $user;
            }
            
        } else {
            echo 'Error Executing getUser Query';
        }     
        return false;        
    }

    public function countUsersRecipes($userID) {


        $qry = "SELECT count(recipeID) FROM recipe WHERE userId = $userID";

        $rs = $this -> db -> query($qry);

            if($rs -> num_rows > 0) {
                    $count = array();

                while($row = $rs -> fetch_assoc()) {
                    $count[] = $row;
                }

                return $count;
            } else {
                echo 'Error Executing countUsersRecipes Query';
            }
            return false;
        }

        public function getUserRecipesIDs($userID) {


        $qry = "SELECT recipeID FROM recipe WHERE userId = $userID";

        $rs = $this -> db -> query($qry);

            if($rs -> num_rows > 0) {
                    $count = array();

                while($row = $rs -> fetch_assoc()) {
                    $count[] = $row;
                }

                return $count;
            } 
            return false;
        }

    public function validateUserName($userName) {

        if(!get_magic_quotes_gpc()) {
            $userName = $this -> db -> real_escape_string($userName);
        } 

            $qry = "SELECT userName FROM users WHERE userName = '$userName'";

            $rs = $this -> db -> query($qry);

            if($rs -> num_rows > 0) {
                $msg = 'Username already exists!';
            } else {
            }
            return $msg;
        }
    

    public function addNewUser($registerUserName, $registerPassword, $registerFirstName, $registerLastName, $registerEmail) {

        if(!get_magic_quotes_gpc()) {
            $registerUserName = $this -> db -> real_escape_string($registerUserName);
            $registerFirstName = $this -> db -> real_escape_string($registerFirstName);
            $registerLastName = $this -> db -> real_escape_string($registerLastName);
        }  
        
        $qry = "INSERT INTO users VALUES(NULL, '".$registerUserName."', SHA1('".$registerPassword."'), 'user', '".$registerFirstName."', '".$registerLastName."', '".$registerEmail."', CURRENT_TIMESTAMP)";
        
        $rs = $this -> db -> query($qry);
        
        if($rs && $this -> db -> affected_rows > 0) {
            $result['newUser'] = true;
        } else {
            $result['newUser'] = false;
        }
        return $result;      
    }

    public function updateUser($userID, $updatedUserName, $updatedFirstName, $updatedLastName, $updatedEmail) {

        if(!get_magic_quotes_gpc()) {
            $updatedUserName = $this -> db -> real_escape_string($updatedUserName);
            $updatedFirstName = $this -> db -> real_escape_string($updatedFirstName);
            $updatedLastName = $this -> db -> real_escape_string($updatedLastName);
        } 

        $qry = "UPDATE users SET userName = '$updatedUserName', userFirstName = '$updatedFirstName', userLastName = '$updatedLastName', userEmail = '$updatedEmail' WHERE userID = '$userID'";

        $rs = $this -> db -> query($qry);
        
            if($rs) {
                if($this -> db -> affected_rows > 0) {
                       $result = true;
                } else {
                       $result = false;
                }            
            } else {
                echo 'Error updating user';    
            }
            return $result;
        }

         public function deleteUser($userName) {

            if(!get_magic_quotes_gpc()) {
            $userName = $this -> db -> real_escape_string($userName);
             } 
               
        $qry = "DELETE FROM users WHERE userName = '$userName'";
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
            return $result;
        } else {
            echo 'Error executing deleteUser query';   
        }
    }

        public function getAllUsers() {
            $qry = "SELECT userName FROM users WHERE userAccess != 'admin'";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $users = array();

                while($row = $rs -> fetch_assoc()) {
                    $users[] = $row;
                }

                return $users;
            } else {
                echo 'No users found';
            }
        } else {
            echo 'Error Executing getAllUsers Query';
        }
        return false;
        }

    

    public function getBasicIngredients() {

        $qry = "SELECT ingredientID, ingredientName FROM ingredients WHERE basicIngredient = '1' ORDER BY ingredientName";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $basicIngredients = array();

                while($row = $rs -> fetch_assoc()) {
                    $basicIngredients[] = $row;
                }

                return $basicIngredients;
            } else {
                echo 'No ingredients found';
            }
        } else {
            echo 'Error Executing getBasicIngredients Query';
        }
        return false;
    }

    public function getSpecializedIngredients() {

        $qry = "SELECT ingredientID, ingredientName FROM ingredients WHERE basicIngredient = '0' ORDER BY ingredientName";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $specializedIngredients = array();

                while($row = $rs -> fetch_assoc()) {
                    $specializedIngredients[] = $row;
                }

                return $specializedIngredients;
            } else {
                echo 'No ingredients found';
            }
        } else {
            echo 'Error Executing getSpecializedIngredients Query';
        }
        return false;
    }

    public function mixItUp($ingredientID) {

        $qry ="SELECT recipeID FROM recipeingredients WHERE ingredientID = $ingredientID";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $id = array();

            while($row = $rs -> fetch_assoc()) {
                    $id[] = $row;
                }

                return $id;

             } 

            } else {

            return false;

        }

     }

     public function countRecipeIngredients($recipeID) {


        $qry = "SELECT count(ingredientID) FROM recipeingredients WHERE recipeID = $recipeID";

        $rs = $this -> db -> query($qry);

            if($rs -> num_rows > 0) {
                    $count = array();

                while($row = $rs -> fetch_assoc()) {
                    $count[] = $row;
                }

                return $count;
            } else {
                echo 'Error Executing countRecipeIngredients Query';
            }
            return false;
        }

         public function countRecipes() {

        $qry = "SELECT count(recipeID) FROM recipe";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {
                $count = array();

                while($row = $rs -> fetch_assoc()) {
                    $count[] = $row;
                }

                return $count;
            } else {
                echo 'No recipes were able to be counted';
            }
        } else {
            echo 'Error Executing countRecipes Query';
        }
        return false;
    }

    public function getRecipes($start, $limit) {

        $qry = "SELECT recipeID, recipeName, recipeImage, recipeDescription FROM recipe LIMIT ".$start.", ".$limit;

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $recipes = array();

                while($row = $rs -> fetch_assoc()) {
                    $recipes[] = $row;
                }

                return $recipes;
            } else {

            }
        } else {

        }
        return false;
    }


    public function getUserRecipes($id, $recipeIDs) {

        $qry = "SELECT recipe.recipeID, recipe.recipeName, recipe.recipeImage, recipe.recipeDescription
        FROM recipe
        LEFT JOIN users on (recipe.userId = users.userID)
        WHERE users.userID = $id AND recipe.recipeID IN ($recipeIDs)";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $userRecipes = array();

                while($row = $rs -> fetch_assoc()) {
                    $userRecipes[] = $row;
                }

                return $userRecipes;
            } else {
                return false;
            }
        } else {
            echo 'Error Executing getUserRecipes Query';
        }
        return false;
    }

    /*
    This function is used to return all the recipe information for a select numer
    */
    public function getMixedRecipes($recipeIDs) {

        $qry = "SELECT recipeID, recipeName, recipeImage, recipeDescription
        FROM recipe
        WHERE recipeID IN ($recipeIDs)";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $matchedRecipes = array();

                while($matchedRecipe = $rs -> fetch_assoc()){

                    $matchedRecipes[] = $matchedRecipe;

                }
                
                return $matchedRecipes;
                
            } else {
                return false;
            }
        } else {
            echo 'Error Executing getMixedRecipes Query';
        }
        return false;
    }


        public function getRecipeByID($id) {
        
        $qry = "SELECT users.userName, recipe.recipeName, recipe.recipeImage, recipe.recipeDescription, recipe.recipeMethod, recipe.recipeUploadDate
        FROM recipe
        LEFT JOIN users ON (recipe.userId = users.userID)
        WHERE recipe.recipeID = $id";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                
                $recipe = $rs -> fetch_assoc();
                
                return $recipe;
                
            } 
                     
        } else {
            echo 'Error executing getRecipeByID query';   
        }
        
        return false;        
    }

    public function getRecipeIngredientsByID($id) {
                
        $qry = "SELECT recipe.recipeID, ingredients.ingredientID, ingredients.ingredientName, recipeingredients.ingredientQuantity, measurement.measurement, recipeingredients.extraIngredientInfo
        FROM recipe
        LEFT JOIN recipeingredients ON (recipeingredients.recipeID = recipe.recipeID)
        LEFT JOIN ingredients ON (ingredients.ingredientID = recipeingredients.ingredientID)
        LEFT JOIN measurement ON (recipeingredients.measurementID = measurement.measurementID)
        WHERE recipe.recipeID = $id";

        $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                
                while($recipeIngredients[] = $rs -> fetch_assoc());

                return $recipeIngredients;
                
            } else {
                echo 'Recipe Ingredients not found';   
            }            
        } else {
            echo 'Error executing getRecipeIngredientsByID query';   
        }
        
        return false;        
    }



public function showIngredients() {

        $qry = "SELECT ingredientID, ingredientName FROM ingredients ORDER BY ingredientName";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $recipes = array();

                while($row = $rs -> fetch_assoc()) {
                    $ingredients[] = $row;
                }
                return $ingredients;

            } else {
                echo 'No ingredients found';
            }
        } else {
            echo 'Error Executing showIngredients Query';
        }
        return false;
    }

    public function showMeasurements() {

        $qry = "SELECT measurementID, measurement FROM measurement ORDER BY measurement";

        $rs = $this -> db -> query($qry);

        if($rs) {

            if($rs -> num_rows > 0) {

                $recipes = array();

                while($row = $rs -> fetch_assoc()) {
                    $measurements[] = $row;
                }

                return $measurements;
            } else {
                echo 'No measurements found';
            }
        } else {
            echo 'Error Executing showMeasurements Query';
        }
        return false;
    }
    
    
    public function newIngredient($newIngredientName) {

        $ingredientName = ucfirst($newIngredientName);
        
        $qry = "INSERT INTO ingredients VALUES (NULL, '$ingredientName', '0')";
        
        $rs = $this -> db -> query($qry);
        
        if($rs && $this -> db -> affected_rows > 0) {
            $msg = 'Ingredient added.';
        } else {
            $msg = 'Ingredient could not be added';
        }
        return $msg;      
    }

    public function validateNewIngredient() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();   
        }              
        extract($_POST);

        $ingredientName = ucfirst($newIngredientName);

        $qry = "SELECT ingredientName FROM ingredients WHERE ingredientName = '$ingredientName'";

        $rs = $this -> db -> query($qry);

        if($rs -> num_rows > 0) {
            $result['ok'] = false;
        } else {
            $result['ok'] = true;
        }
        return $result;

    }

    public function insertRecipeInfo($userID, $recipeName, $recipeImage, $recipeDescription, $recipeMethod) {

        if(!get_magic_quotes_gpc()) {
            $recipeName = $this -> db -> real_escape_string($recipeName);
            $recipeDescription = $this -> db -> real_escape_string($recipeDescription);
            $recipeMethod = $this -> db -> real_escape_string($recipeMethod);
        }  

            $recipeName = $recipeName;
            $recipeDescription = $recipeDescription;
            $recipeMethod = $recipeMethod;


        $qry = "INSERT INTO recipe VALUES(NULL, '".$userID."', '".$recipeName."', '".$recipeImage."', '".$recipeDescription."', '".$recipeMethod."', CURRENT_DATE)";

        $rs = $this -> db -> query($qry);
        
        if($rs && $this -> db -> affected_rows > 0) {
            $msg = 'Recipe added.';
        } else {
            $msg = 'Recipe could not be added';
        }
        return $msg;  
    }

    public function insertIngredientInfo($recipeID, $ingredientID, $ingredientQuantity, $measurementID, $extraInfo) {
        $qry = "INSERT INTO recipeingredients VALUES(NULL, '".$recipeID."', '".$ingredientID."', '".$ingredientQuantity."', '".$measurementID."', '".$extraInfo."')";

        $rs = $this -> db -> query($qry);
        
        if($rs && $this -> db -> affected_rows > 0) {
            $msg = 'Ingredient added.';
        } else {
            $msg = 'Ingredient could not be added';
        }
        return $msg;  
    }

    public function getRecipeID($recipeName) {

        if(!get_magic_quotes_gpc()) {
            $recipeName = $this -> db -> real_escape_string($recipeName);
        }

         $qry = "SELECT recipeID FROM recipe WHERE recipeName = '$recipeName'";

         $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $id = $rs -> fetch_assoc();
                return $id['recipeID'];
            }
            
        } else {
            echo 'Error Executing getRecipeID Query';
        }     
        return false;

    }

    public function getIngredientID($ingredientName) {

        if(!get_magic_quotes_gpc()) {
            $ingredientName = $this -> db -> real_escape_string($ingredientName);
        }
        

        $qry = "SELECT ingredientID FROM ingredients WHERE ingredientName = '$ingredientName'";

         $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $id = $rs -> fetch_assoc();
                return $id['ingredientID'];
            }
            
        } else {
            echo 'Error Executing getIngredientID Query';
        }     
        return false;
    }

    public function getMeasurementID($measurementName) {

        $qry = "SELECT measurementID FROM measurement WHERE measurement = '$measurementName'";

         $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $id = $rs -> fetch_assoc();
                return $id['measurementID'];
            }
            
        } else {
            echo 'Error Executing getMeasurementID Query';
        }     
        return false;
    }

    public function updateRecipe($recipeID, $userID, $recipeName, $recipeImage, $recipeDescription, $recipeMethod) {



        if(!get_magic_quotes_gpc()) {
            $recipeName = $this -> db -> real_escape_string($recipeName);
            $recipeDescription = $this -> db -> real_escape_string($recipeDescription);
            $recipeMethod = $this -> db -> real_escape_string($recipeMethod);
        }  

        $qry = "UPDATE recipe SET userId = '$userID', recipeName = '$recipeName', recipeImage = '$recipeImage', recipeDescription = '$recipeDescription', recipeMethod = '$recipeMethod' WHERE recipeID = '$recipeID'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                   $msg = '<br />Recipe updated.';
            } else {
                   $msg = '<br />Recipe not updated.';
            }            
        } else {
            echo 'Error updating recipe';    
        }
        return $msg;
    }

    public function deleteRecipe($recipeID) {
               
        $qry = "DELETE FROM recipe WHERE recipeID = '$recipeID'";
        $rs = $this -> db -> query($qry);
        
       if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
            return $result;
            
        } else {
            echo 'Error executing deleteRecipe query';   
        }
    }


    public function deleteRecipeIngredients($recipeID) {
               
        $qry = "DELETE FROM recipeingredients WHERE recipeID = '$recipeID'";
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result = true;
            } else {
                $result = false;
            }
            return $result;
        } else {
            echo 'Error executing deleteRecipeIngredients query';   
        }
    }


    function getContactEmail() {

    $qry = "SELECT userFirstName, userEmail FROM users WHERE userName = 'admin'";

    $rs = $this -> db -> query($qry);
        
        if($rs) {
            
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return $user;
            }
            
        } else {
            echo 'Error Executing getContactEmail Query';
        }     
        return false;        
    }



    

    /*

    public function deleteProduct() {
        
        $qry = 'DELETE FROM products WHERE productID = '.$_POST['productID'];
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['msg'] = 'Product successfully deleted';
                $result['ok'] = true;
            } else {
                $result['msg'] = 'No product deleted';
                $result['ok'] = false;
            }
            return $result;
        } else {
            echo 'Error executing query';   
        }
    }
    
    public function updateProduct() {
        
        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }
        
        extract($_POST);
        
        $qry = "UPDATE products SET productName = '$productName', productDescription = '$productDescription', productPrice = $productPrice, productImage = '$productImage' WHERE productID = $productID";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                   $msg = '<br />Product record updated.';
            } else {
                   $msg = '<br />No product updated.';
            }            
        } else {
            echo 'Error updating product';    
        }
        return $msg;
    }
    
    
    protected function updatePage() {
        
        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }
        
        extract($_POST);
        
        $qry = "UPDATE pages SET pageTitle = '$pageTitle', pageHeading = '$pageHeading', pageKeywords = '$pageKeywords', pageDescription = '$pageDescription', pageContent = '$pageContent' WHERE pageName = '$pageName'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $msg = 'Page updated.';
            } else {
                $msg = 'No page updated.';
            }           
        } else {
            echo 'Error updating page';
        }
        return $msg;        
    }
    
    
    public function createOrder($totalSale, $cartContents, $receipt) {
        
        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }
        
        extract($_POST);
        
        $qry = "INSERT INTO orders VALUES(NULL, '$name', '$email', '$phone', '$hnoStreet', '$suburb', '$city', '$country', '$shipHnoStreet', '$shipSuburb', '$shipCity', '$shipCountry', '$receipt', $totalSale, CURRENT_TIMESTAMP, '', '')";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $orderID = $this -> db -> insert_id;
                $qry1 = "INSERT INTO orderedproducts VALUES ";
                $itemCnt = count($cartContents);
                $i = 0;
                foreach($cartContents as $product) {
                    extract($product);
                    $qry1 .= "(NULL, $orderID, $productID, $productPrice, $quantity)";
                    
                    if(++$i < $itemCnt) {
                        $qry1 .= ',';
                    }
                }
                $rs1 = $this -> db -> query($qry1);
                if($rs1 && $this -> db -> affected_rows > 0) {
                    return true;
                } else {
                    echo 'Error executing ordered products query';   
                }             
            } else {
                echo 'No order record created';
            }
        } else {
            echo 'Error executing order query';
            echo $qry;
        } 
    }
    
    
    
    
    */
    
    
    
    private function sanitizeInput() {
        
        foreach($_POST as &$post) {
            $post = $this -> db -> real_escape_string($post);
        }
        
    }
    
    
    
}




?>