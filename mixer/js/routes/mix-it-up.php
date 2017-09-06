<?php
    // do any authentication first, then add POST variable to session

	session_start();

	if($_POST['removeFinalized']) {
	        $id = $_POST['ingredientID'];
	        unset($_SESSION['finalized'][$id]);
    }


	if($_POST['ingredientName'] && $_POST['ingredientID']) {

		if(!isset($_SESSION['finalized'][$_POST['ingredientID']])) {

			$_SESSION['finalized'][$_POST['ingredientID']] = array(
            'ingredientID' => $_POST['ingredientID'],
            'ingredientName' => $_POST['ingredientName'],
            'basic' => $_POST['basic']
            );
	            
	    }
		
	}

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

?>