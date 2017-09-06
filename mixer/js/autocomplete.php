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

    if($_POST['mixItUpReset']) {
        unset($_SESSION['liveRecipes']);
        unset($_SESSION['finalized']);
    }

    if($_POST['recipesRequested']) {

        $db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);

        unset($_SESSION['liveRecipes']);

        $confirmedIngredients = array();

        foreach($_SESSION['finalized'] as $id => $ingredient) {
            $ingredientInfo['ingredientID'] = $ingredient['ingredientID'];                
            $ingredientInfo['ingredient'] = $ingredient['ingredientName'];
            $confirmedIngredients[] = $ingredientInfo;
        }

        // echo json_encode($confirmedIngredients);

        // [{"ingredientID":"13","ingredient":"Flour"},{"ingredientID":"1","ingredient":"Egg"},{"ingredientID":"84","ingredient":"Honey"},{"ingredientID":"2","ingredient":"Milk"}]

        $result = array();



        foreach($confirmedIngredients as $confirmed) {

            $thisId = $confirmed['ingredientID'];

              // $db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);

                $qry ="SELECT recipeID FROM recipeingredients WHERE ingredientID = '$thisId'";

                $rs = $db -> query($qry);

                if($rs) {

                    if($rs -> num_rows > 0) {

                        $rr = array();

                        while($row = $rs -> fetch_assoc()) {
                            // $rr[] = $row;
                            $rr[] = array('id' => $row['recipeID']);

                        }

                     } 

                    } else {

                    return false;

                }

            if(is_array($rr)) {
                $result[] = $rr;
            }
            
        }

        /*Explodes the array - bringing all the buried results to one top layer*/
        $recipeIDiterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($result));
        $recipeIDArray = array();



        foreach($recipeIDiterator as $key=>$value) {

            $recipeIDArray[] = $value;

            $recipeIDInstances = (array_count_values($recipeIDArray));

        }

            
        // echo json_encode($recipeIDInstances);

            $uniqRecipeID = array_unique($recipeIDArray);

             // echo json_encode($uniqRecipeID);

            foreach ($uniqRecipeID as $uniqRecipe) {

                    $recipeID = $uniqRecipe;

                    // echo json_encode($recipeID);

                    $qry = "SELECT count(ingredientID) FROM recipeingredients WHERE recipeID = $uniqRecipe";

                    $rs = $db -> query($qry);

                    if($rs -> num_rows > 0) {
                            $count = array();

                        while($row = $rs -> fetch_assoc()) {
                            // $ingredientCount[] = $row;
                            $ingredientCount[] = array($uniqRecipe => $row['count(ingredientID)']);
                        }

                    }

                // echo json_encode($ingredientCount);

                // $countIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($ingredientCount));

                foreach($ingredientCount as $key=>$value) {
                    $count = $value;
                }

                // echo json_encode($count);

                if($recipeIDInstances[$uniqRecipe] == $count[$uniqRecipe]) {
                    $_SESSION['liveRecipes'][$uniqRecipe] = $uniqRecipe;
                    echo json_encode($uniqRecipe);
                } 
            }

}




?>