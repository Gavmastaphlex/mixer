<?php

class UserListView extends View {

        
    protected function displayContent() {

    	unset($_SESSION['selectedUserRecipes']);

    	/*
		First of all checking to see if the admin is logged in as he is the only one
		authorized to view this page.
    	*/
    	if($this -> model -> adminLoggedIn) {

    		/*
			If the $_GET['id'] is false then display a list of all users with their userID
			as the $_GET['id'] in the link that resubmits the page so that then that particular
			users details can be displayed for further analysis.
    		*/
    	if(!$_GET['id']) {


    				$html = '<h2>User List</h2>'."\n";
    				$html .= '<div id="userListContainer">'."\n";

					$users = $this -> model -> getAllUsers();

					foreach ($users as $user) {
					$html .= '<a href="index.php?page=userList&amp;id='.$user['userName'].'" class="userList">'.$user['userName'].'</a> <br />'."\n";
					}

					$html .= '</div>'."\n";
				} else {

					/*
					Else we'll have a userID come through in the variable $_GET['id'], so the function getUserByUserName($_GET['id'])
					is run straight away to get that users details from the database.
					*/
					$user = $this -> model -> getUserByUserName($_GET['id']);

					if(is_array($user)) {
						extract($user);
					}
					$recipesCount = $this -> model -> countUsersRecipes($userID);

					$countIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($recipesCount));

                                        foreach($countIterator as $key=>$value) {
                                            $count = $value;
                                        }

                    /*
					If the deleteUserButton is pressed, displaying a box just to get the user to doule check if
					they want to go ahead with the deletion.
                    */
                    if($_POST['deleteUserButton']) {
                            $html .= '<div id="deleteUserBox">'."\n";
                            $html .= '<form method="post" action="index.php?page=userPanel&amp;id='.$_GET['id'].'" enctype="multipart/form-data">'."\n";
                            $html .= '<p><strong>Are you sure you want to delete this user?</strong></p>'."\n";
                            $html .= '<a href="index.php?page=userList&amp;id='.$_GET['id'].'" id="cancelDeleteUser" class="green-button">Cancel</a>'."\n";
                            $html .= '<input type="submit" name="deleteUser" value="Delete User" id="confirmDeleteUser" class="red-button" />'."\n";
                            $html .= '</div>'."\n";
                        }


					$html .= '<div id="userSummaryContainer">'."\n";
					$html .= '<ul>'."\n";
					$html .= '<li>Username : <em>'.$userName.'</em></li>'."\n";
					$html .= '<li>First Name : <em>'.$userFirstName.'</em></li>'."\n";
					$html .= '<li>Last Name : <em>'.$userLastName.'</em></li>'."\n";
					$html .= '<li>Email : <em>'.$userEmail.'</em></li>'."\n";
					$html .= '<li>Date Joined : <em>'.$userJoinDate.'</em></li>'."\n";
					$html .= '<li>Recipes Added : <em>'.$count.'</em></li>'."\n";
					$html .= '</ul>'."\n";
					$html .= '<div id="adminOptionButtonsContainer">'."\n";
					$html .= '<form method="post" action="index.php?page=userList&amp;id='.$userName.'">'."\n";
					$html .= '<a href="index.php?page=userList" id="backToUserList" class="adminOptionButtons blue-button">Back to User List</a>'."\n";
					$html .= '<a href="index.php?page=recipes&amp;browseUserRecipes='.$userID.'" id="usersRecipes" class="adminOptionButtons green-button">Users Recipes</a>'."\n";
			        $html .= '<input type="submit" name="deleteUserButton" id="deleteUserBtn" value="Delete User" class="adminOptionButtons red-button" />'."\n";
					$html .= '</form>'."\n";

					$html .= '</div>'."\n";

					$html .= '<div id="clearDiv"></div>'."\n";		
					$html .= '</div>'."\n";
				}

			} else {
				$html = '<h3>You are not authorized to view this page!</h3>'."\n";
				$html .= '<img src="images/danger.gif" alt="Unauthorized Page" id="unauthorizedPic">'."\n";
			}

        return $html;        
    }
            
        
}


?>