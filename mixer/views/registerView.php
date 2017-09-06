<?php

class RegisterView extends View {

        
    protected function displayContent() {

    	unset($_SESSION['confirmed']);
            unset($_SESSION['newRecipe']);
            unset($_SESSION['updateRecipe']);
            unset($_SESSION['finalized']);
            unset($_SESSION['mixItUp']);
            unset($_SESSION['allRecipes']);
            unset($_SESSION['lastPage']);
            unset($_SESSION['pageNum']);
            unset($_SESSION['newIngredient']);
            unset($_SESSION['liveRecipes']);

    				if($_GET['changePassword'] == true || $_POST['confirmPasswordBtn']) {

    								/*
									Once the user clicks the confirmPasswordBtn, the page will refresh and the values
									that have been entered will be validated by the function validatePasswordChange()
									to make sure that all fields were entered, the old password matched the password
									that was previously stored in the database, as well as making sure that the two
									times the user is required to enter their new password matches up and they are 
									exactly the same.
    								*/
    								if($_POST['confirmPasswordBtn']) {
    									$vresult = $this -> model -> validatePasswordChange();
    								}
		    					
									if(is_array($vresult)) {
					                	extract($vresult);
				            			}
			 
			    				$html = '<h2>Change Password</h2>'."\n";

			    				if ($ok == true) {

			                        	$html .= '<h3>Your password has been successfully changed!</h3>'."\n";
			                        	$html .= '<img src="images/happy-chef.png" id="registeredImage" alt="Success Picture" />'."\n";

			                        } else {

			                        	/*	
										Displaying the input fields to allow a user to change their password.
			                        	*/	
			                        	$html .= '<div id="registerForm">'."\n";
										$html .= '<form id="edit_form" method="post" action="index.php?page=register" enctype="multipart/form-data">'."\n";
								        $html .= '<div id="registerDetails">'."\n";
								        $html .= '<label for="oldPassword">Old Password</label>'."\n";
								        $html .= '<input type="password" name="oldPassword" id="oldPassword" value="" />'."\n";
								        $html .= '<div class="registerError">'.$oldPasswordMsg.'</div>';
								        $html .= '<label for="newPassword">New Password</label>'."\n";
								        $html .= '<input type="password" name="newPassword" id="newPassword" value="" />'."\n";
								        $html .= '<label for="confirmPassword">Confirm Password</label>'."\n";
								        $html .= '<input type="password" name="confirmPassword" id="confirmPassword" value="" />'."\n";
								        $html .= '<div class="registerError">'.$confirmPasswordMsg.'</div>';     		        		
						        		$html .= '<div class="clearDiv"></div>'."\n";
							        	$html .= '</div>'."\n";
							        	$html .= '<input type="submit" name="confirmPasswordBtn" id="registerBtn" value="Confirm New Password" />'."\n";				
										$html .= '</form>'."\n";
							        	$html .= '</div>'."\n";

			                        }

				        		

    				} else if($_GET['id'] || $_POST['updateDetailsBtn']) {

    					if($_GET['id']) {

    								/*
									Getting all the users information from the database based on their userIDs thats
									been held in $_SESSION['userID'] ever since they logged on, so as to prepopulate all
									the fields with the users details so that then they can make any necessary changes.
    								*/
		    						$this -> user = $this -> model -> getUserByID($_SESSION['userID']);

		    						if(is_array($this -> user)) {
				                	extract($this -> user);
			            			}
		    					}

		    					if ($_POST['updateDetailsBtn']) {

								$userFirstName = $_POST['registerFirstName'];
								$userLastName = $_POST['registerLastName'];
								$userEmail = $_POST['registerEmail'];
								$userName = $_POST['registerUserName'];

								$vresult = $this -> model -> validateRegisterForm();

								if(is_array($vresult)) {
			                	extract($vresult);
		            			}

							}
			 
			    				$html = '<h2>Edit Details</h2>'."\n";

			    				/*
								Main thing is to check that the password the user enters at the time of changing their details matches
								with the password that is stored in the database, and we can tell if its alright by checking to see
								if there is no validation message (a.k.a $confirmPasswordMsg) as this means it was correct.
			    				*/
			    				if ($ok == true && !$confirmPasswordMsg) {
			                        	$html .= '<h3>Your details have been successfully updated.</h3>'."\n";
			                        	$html .= '<img src="images/happy-chef.png" id="registeredImage" alt="Success Picture" />'."\n";
			                        	$_SESSION['userName'] = $_POST['registerUserName'];

			                        } else {

			                        	$html .= '<div id="registerForm">'."\n";
										$html .= '<form id="edit_form" method="post" action="index.php?page=register" enctype="multipart/form-data">'."\n";
								        $html .= '<div id="registerDetails">'."\n";
								       	$html .= '<label for="registerFirstName">First Name</label>'."\n";
								        $html .= '<input type="text" name="registerFirstName" id="registerFirstName" value="'.$userFirstName.'" />'."\n";
								        $html .= '<div class="registerError">'.$firstNameMsg.'</div>';
								        $html .= '<label for="registerLastName">Last Name</label>'."\n";
								        $html .= '<input type="text" name="registerLastName" id="registerLastName" value="'.$userLastName.'" />'."\n";
								        $html .= '<div class="registerError">'.$lastNameMsg.'</div>';
								        $html .= '<label for="registerEmail">Email</label>'."\n";
								        $html .= '<input type="text" name="registerEmail" id="registerEmail" value="'.$userEmail.'" />'."\n";
								        $html .= '<div class="registerError">'.$emailMsg.'</div>';
								        $html .= '<label for="registerUserName">Username</label>'."\n";
								        $html .= '<input type="text" name="registerUserName" id="registerUserName" value="'.$userName.'" />'."\n";
								        $html .= '<div class="registerError">'.$usernameMsg.'</div>';
								        $html .= '<div class="registerError">'.$usernameExistsMsg.'</div>';
								        $html .= '<label for="registerPassword">Confirm password</label>'."\n";
								        $html .= '<input type="password" name="confirmPassword" id="registerPassword" value="" />'."\n";
								        $html .= '<div class="registerError">'.$confirmPasswordMsg.'</div>';    		        		
						        		$html .= '<div class="clearDiv"></div>'."\n";
							        	$html .= '</div>'."\n";
							        	$html .= '<input type="submit" name="updateDetailsBtn" id="registerBtn" value="Update Details" />'."\n";				
										$html .= '</form>'."\n";
							        	$html .= '</div>'."\n";

			                        }

    				} else {

    					/*
						If a registered user isn't changing their details, or their password, then the only other reason
						they would be on the registerView would be if they click the Register button. Therefore, the last
						part of the if statement is to display the Register form.
    					*/

						/*	
						If the user clicks the registerBtn, validation is carried out on all fields.
						*/
			    		if($_POST['registerBtn']) {
						$vresult = $this -> model -> validateRegisterForm();

					    }

        
        			$html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";

        			if ($vresult['ok'] == true) {

                   			$html .= '<h3>Thank you '.$_POST['registerFirstName'].'. You have been registered! Please login now!</h3>'."\n";
                   			$html .= '<img src="images/happy-chef.png" id="registeredImage" alt="Success Picture" />'."\n";

                        } else {

	        		$html .= '<div id="registerForm">'."\n";
					$html .= '<form id="edit_form" method="post" action="index.php?page=register" enctype="multipart/form-data">'."\n";
			        $html .= '<input type="hidden" name="productID" value="" />'."\n";
			        $html .= '<div id="registerDetails">'."\n";
			       	$html .= '<label for="registerFirstName">First Name</label>'."\n";
			        $html .= '<input type="text" name="registerFirstName" id="registerFirstName" value="'.$_POST['registerFirstName'].'" />'."\n";
			        $html .= '<div class="registerError">'.$firstNameMsg.'</div>';
			        $html .= '<label for="registerLastName">Last Name</label>'."\n";
			        $html .= '<input type="text" name="registerLastName" id="registerLastName" value="'.$_POST['registerLastName'].'" />'."\n";
			        $html .= '<div class="registerError">'.$lastNameMsg.'</div>';
			        $html .= '<label for="registerEmail">Email</label>'."\n";
			        $html .= '<input type="text" name="registerEmail" id="registerEmail" value="'.$_POST['registerEmail'].'" />'."\n";
			        $html .= '<div class="registerError">'.$emailMsg.'</div>';
			        $html .= '<label for="registerUserName">Username</label>'."\n";
			        $html .= '<input type="text" name="registerUserName" id="registerUserName" value="'.$_POST['registerUserName'].'" />'."\n";
			        $html .= '<div class="registerError">'.$usernameMsg.'</div>';
			        $html .= '<div class="registerError">'.$usernameExistsMsg.'</div>';
			        $html .= '<label for="registerPassword">Password</label>'."\n";
			        $html .= '<input type="password" name="registerPassword" id="registerPassword" value="" />'."\n";
			        $html .= '<div class="registerError">'.$passwordMsg.'</div>';
			        $html .= '<label for="registerConfirmPassword">Confirm Password</label>'."\n";
			        $html .= '<input type="password" name="registerConfirmPassword" id="registerConfirmPassword" value="" />'."\n";
			        $html .= '<div class="registerError">'.$confirmPasswordMsg.'</div>';       		        		
	        		$html .= '<div class="clearDiv"></div>'."\n";
		        	$html .= '</div>'."\n";
		        	$html .= '<input type="submit" name="registerBtn" id="registerBtn" value="Register" />'."\n";				
					$html .= '</form>'."\n";
		        	$html .= '</div>'."\n";
		        		}
		        	}


        return $html;        
    }
            
        
}


?>