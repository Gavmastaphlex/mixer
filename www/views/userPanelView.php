<?php

class UserPanelView extends View {
    
    protected function displayContent() {

        $html = '';

        if($_POST['deleteUser']) {
            $deleteUser = $this -> model -> deleteUser($_GET['id']);
        }

        if($_GET['cancelledRecipe'] == true) {
            unset($_SESSION['confirmed']);
            unset($_SESSION['newRecipe']);
            unset($_SESSION['updateRecipe']);

            $html .= '<h3>Your recipe has been cancelled.</h3>'."\n";
        }

        if($_POST['deleteRecipe']) {
            $deleteRecipe = $this -> model -> deleteRecipe($_GET['id']);
            $deleteRecipeIngredients = $this -> model -> deleteRecipeIngredients($_GET['id']);
        }
        
            unset($_SESSION['confirmed']);
            unset($_SESSION['newRecipe']);
            unset($_SESSION['updateRecipe']);
            unset($_SESSION['finalized']);
            unset($_SESSION['mixItUp']);
            unset($_SESSION['allRecipes']);
            unset($_SESSION['lastPage']);
            unset($_SESSION['pageNum']);
            unset($_SESSION['newIngredient']);
            
        
        if($this -> model -> userLoggedIn) {

            if($_GET['deleteUser']) {
                $html .= '<div id="deleteUserBox">'."\n";
                $html .= '<form method="post" action="index.php?page=home" enctype="multipart/form-data">'."\n";
                $html .= '<p><strong>Are you sure you want to delete your profile?</strong></p>'."\n";
                $html .= '<a href="index.php?page=userPanel" id="cancelDeleteUser" class="green-button">Cancel</a>'."\n";
                $html .= '<input type="submit" name="deleteProfile" value="Delete User" id="confirmDeleteUser" class="red-button" />'."\n";
                $html .= '</form>'."\n";
                $html .= '</div>'."\n";
            }
            
            if($_POST['deleteRecipe']) {
                $html .= '<h3 id="deleteSuccessful"><strong>Recipe successfully deleted!</strong></h3>'."\n";
            }

            $html .= '<div id="userOptionsContainer">'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=addRecipe&amp;step=one" id="addRecipeOption"><img src="images/loggedIn/addRecipe.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=recipes&amp;id='.$_SESSION['userID'].'" id="viewRecipesOption"><img src="images/loggedIn/yourRecipes.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=register&amp;id='.$_SESSION['userID'].'" id="editDetailsOption"><img src="images/loggedIn/editDetails.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=register&amp;id='.$_SESSION['userID'].'&amp;changePassword=true" id="changePasswordOption"><img src="images/loggedIn/changePassword.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=userPanel&amp;deleteUser=true" id="deleteProfileOption"><img src="images/loggedIn/deleteProfile.jpg" /></a></div>'."\n";
            $html .= '</div>'."\n";

        } else if($this -> model -> adminLoggedIn) {

            if($_POST['deleteRecipe']) {
                $html .= '<h3 id="deleteSuccessful"><strong>Recipe successfully deleted!</strong></h3>'."\n";
            }

            $html .= '<div id="userOptionsContainer">'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=addRecipe&amp;step=one" id="addRecipeOption"><img src="images/loggedIn/addRecipe.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=recipes&amp;id='.$_SESSION['userID'].'" id="viewRecipesOption"><img src="images/loggedIn/yourRecipes.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=register&amp;id='.$_SESSION['userID'].'" id="editAdminDetailsOption"><img src="images/loggedIn/editDetails.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=register&amp;id='.$_SESSION['userID'].'&amp;changePassword=true" id="changePasswordOption"><img src="images/loggedIn/changePassword.jpg" /></a></div>'."\n";
            $html .= '<div class="userOptions"><a href="index.php?page=userList" id="viewAllUsersOption"><img src="images/loggedIn/viewAllUsers.jpg" /></a></div>'."\n";
            $html .= '</div>'."\n";

        } else {

            $html = '<h3>You are not authorized to view this page!</h3>'."\n";
            $html .= '<img src="images/danger.gif" alt="Unauthorized Page" id="unauthorizedPic" \>'."\n";


        }
        
        return $html;

        }
    
    
    
}






?>