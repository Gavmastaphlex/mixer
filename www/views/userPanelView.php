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
                            $html .= '<a href="index.php?page=userPanel" id="cancelDeleteUser">Cancel</a>'."\n";
                            $html .= '<input type="submit" name="deleteProfile" value="Delete User" id="confirmDeleteUser" />'."\n";
                            $html .= '</form>'."\n";
                            $html .= '</div>'."\n";
                        }
            
            if($_POST['deleteRecipe']) {
                $html .= '<h3 id="deleteSuccessful"><strong>Recipe successfully deleted!</strong></h3>'."\n";
            }

            $html .= '<div id="userOptionsContainer">'."\n";
            $html .= '<a href="index.php?page=addRecipe&amp;step=one" id="addRecipeOption" class="userOptions">Add Recipe</a>'."\n";
            $html .= '<a href="index.php?page=recipes&amp;id='.$_SESSION['userID'].'" id="viewRecipesOption" class="userOptions">View Your Recipes</a>'."\n";
            $html .= '<a href="index.php?page=register&amp;id='.$_SESSION['userID'].'" id="editDetailsOption" class="userOptions">Edit Details</a>'."\n";
            $html .= '<a href="index.php?page=register&amp;id='.$_SESSION['userID'].'&amp;changePassword=true" id="changePasswordOption" class="userOptions">Change Password</a>'."\n";
            $html .= '<a href="index.php?page=userPanel&amp;deleteUser=true" id="deleteProfileOption" class="userOptions">Delete Profile</a>'."\n";
            $html .= '</div>'."\n";

        } else if($this -> model -> adminLoggedIn) {

            if($_POST['deleteRecipe']) {
                $html .= '<h3 id="deleteSuccessful"><strong>Recipe successfully deleted!</strong></h3>'."\n";
            }

            $html .= '<div id="userOptionsContainer">'."\n";
            $html .= '<a href="index.php?page=addRecipe&amp;step=one" id="addRecipeOption" class="userOptions">Add Recipe</a>'."\n";
            $html .= '<a href="index.php?page=recipes&amp;id='.$_SESSION['userID'].'" id="viewRecipesOption" class="userOptions">View Your Recipes</a>'."\n";
            $html .= '<a href="index.php?page=register&amp;id='.$_SESSION['userID'].'" id="editAdminDetailsOption" class="userOptions">Edit Admin Details</a>'."\n";
            $html .= '<a href="index.php?page=register&amp;id='.$_SESSION['userID'].'&amp;changePassword=true" id="changePasswordOption" class="userOptions">Change Admin Password</a>'."\n";
            $html .= '<a href="index.php?page=userList" id="viewAllUsersOption" class="userOptions">View All Users</a>'."\n";
            $html .= '</div>'."\n";

        } else {

            $html = '<h3>You are not authorized to view this page!</h3>'."\n";
            $html .= '<img src="images/danger.gif" alt="Unauthorized Page" id="unauthorizedPic" \>'."\n";


        }
        
        return $html;

        }
    
    
    
}






?>