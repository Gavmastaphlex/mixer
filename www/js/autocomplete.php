<?php

        session_start();

        


        include '../../config.php';

        // Get the customer from the URL
        $ingredient = $_GET['ingredient'];

        // If a name has been sent
        if($ingredient) {
        	//connect to the database
        	$db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);

        	//run a query to return names starting with whatever the user has typed into the text field

        	// % is the wildcard character that will match any character any number of times. a.k.a begins with.
        	$qry = "SELECT ingredientID, ingredientName FROM ingredients WHERE ingredientName LIKE '%$ingredient%' ORDER BY ingredientName LIMIT 9";

                $rs = $db -> query($qry);

                if($rs) {

                    if($rs -> num_rows > 0) {

                        $ingredients = array();

                        while($row = $rs -> fetch_assoc()) {

                            $ingredients[] = array('id' => $row['ingredientID'], 'name' => $row['ingredientName']);

                        }

                        echo json_encode($ingredients);

                    } else {
                    return false;
                }   

                } else {
                    return false;
                }		
        	

        } 

// $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

echo json_encode($_SESSION['finalized']);



if($recipesRequested) {

        $confirmedIngredients = array();

        foreach($_SESSION['finalized'] as $id => $ingredient) {
            $ingredientInfo['ingredientID'] = $ingredient['ingredientID'];                
            $ingredientInfo['ingredient'] = $ingredient['ingredientName'];
            $confirmedIngredients[] = $ingredientInfo;
        }

        $result = array();

        foreach($confirmedIngredients as $confirmed) {



            $thisId = $confirmed['ingredientID'];

              $db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);

                $qry ="SELECT recipeID FROM recipeingredients WHERE ingredientID = '%$thisId'";

                $rs = $this -> db -> query($qry);

                if($rs) {

                    if($rs -> num_rows > 0) {

                        $rr = array();

                        while($row = $rs -> fetch_assoc()) {
                            $rr[] = $row;
                        }

                     } 

                    } else {

                    return false;

                }

            if(is_array($rr)) {
                $result[] = $rr;
            }


        }

        $recipeIDiterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($result));
        $recipeIDArray = array();

        foreach($recipeIDiterator as $key=>$value) {

            $recipeIDArray[] = $value;
            $recipeIDInstances = (array_count_values($recipeIDArray));
            $uniqRecipeID = array_unique($recipeIDArray);

            foreach ($uniqRecipeID as $uniqRecipe) {

                $recipeID = $uniqRecipe;

                    $qry = "SELECT count(ingredientID) FROM recipeingredients WHERE recipeID = $recipeID";

                    $rs = $this -> db -> query($qry);

                    if($rs -> num_rows > 0) {
                            $count = array();

                        while($row = $rs -> fetch_assoc()) {
                            $ingredientCount[] = $row;
                        }

                    }
 
                $countIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($ingredientCount));

                foreach($countIterator as $key=>$value) {
                    $count = $value;
                }

                if($recipeIDInstances[$recipeID] == $count) {
                    $_SESSION['liveRecipes'][$recipeID] = $recipeID;
                } 
            }          
        }

}




?>