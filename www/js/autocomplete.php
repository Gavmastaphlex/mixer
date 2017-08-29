<?php

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
        	

        } else { //in the case that the user backspaces so there isn't anything in the input box
        	return false;
        }



?>