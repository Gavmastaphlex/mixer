<?php

session_start();

include 'views/viewClass.php';
include 'classes/modelClass.php';

class PageSelector {
	//this class decides what page information to display via index.php

	//check to see if the user has decided which page to go to

		public function run() {

		if(!$_GET['page']) {
			$_GET['page'] = 'home';
		}

		
		/*If $_GET['page'] is false, then that signifies that there is nothing
		in the $_GET variable due to the user not selecting a link that would then
		fill that variable, so we can safely assume that the user has visited the site
		for the first time in this session and thus we will display the home page*/

		$model = new Model;

		$pageInfo = $model -> getPageInfo($_GET['page']);
		
			switch($_GET['page']) {
				case 'home':
					include 'views/homeView.php';
					$view = new HomeView($pageInfo, $model);
					break;
				case 'recipes':
					include 'views/recipesView.php';
					$view = new RecipesView($pageInfo, $model);
					break;
				case 'about':
					include 'views/aboutView.php';
					$view = new AboutView($pageInfo, $model);
					break;
				case 'contact':
					include 'views/contactView.php';
					$view = new ContactView($pageInfo, $model);
					break;
				case 'sitemap':
					include 'views/sitemapView.php';
					$view = new SitemapView($pageInfo, $model);
					break;
				case 'recipe':
					include 'views/recipeView.php';
					$view = new RecipeView($pageInfo, $model);
					break;
				case 'addRecipe':
					include 'views/addRecipeView.php';
					$view = new AddRecipeView($pageInfo, $model);
					break;
				case 'editRecipe':
					include 'views/editRecipeView.php';
					$view = new EditRecipeView($pageInfo, $model);
					break;
				case 'register':
					include 'views/registerView.php';
					$view = new RegisterView($pageInfo, $model);
					break;
				case 'userPanel':
					include 'views/userPanelView.php';
					$view = new UserPanelView($pageInfo, $model);
					break;
				case 'userList':
					include 'views/userListView.php';
					$view = new UserListView($pageInfo, $model);
					break;
				case 'userSummary':
					include 'views/userSummary.php';
					$view = new UserSummaryView($pageInfo, $model);
					break;
			}

			echo $view -> displayPage();
	}

}

$pageSelect = new PageSelector();
$pageSelect -> run();

?>