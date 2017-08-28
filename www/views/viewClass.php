<?php

abstract class View {
    
    protected $pageInfo;    // holds the data read from the pages table
    protected $model;       //object for the database class
    
    
    /*
    The construct function is always triggered straight away, as we'll always need
    $pageInfo so as to know what to display, and also $model as each page is coming
    from the database, let alone all the user & recipe information.
    */
    public function __construct($info, $model) {
        
        $this -> pageInfo = $info;
        $this -> model = $model;
        
    }
    
    public function displayPage() {
        
        $this -> model -> checkUserSession();

        $html = $this -> displayHeader();
        $html .= $this -> displayContent();
        $html .= $this -> displayFooter();
        
        return $html;        
    }
    
    abstract protected function displayContent();
    
    private function displayHeader() {

        if($_GET['page'] == 'userPanel') {
            
        }

        /*
        Displaying all the header information
        */
        
        $html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
        $html .=  '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
        $html .= '<head>'."\n";
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
        $html .= '<title>'.$this -> pageInfo['pageTitle'].'</title>'."\n";
        $html .= '<link rel="stylesheet" type="text/css" href="css/styles.css" />'."\n";
        $html .= '<link href="http://fonts.googleapis.com/css?family=Text+Me+One|Raleway:400,200" rel="stylesheet" type="text/css" />'."\n";
        $html .= '<meta name="description" content="'.$this -> pageInfo['pageDescription'].'" />'."\n";
        $html .= '<meta name="keywords" content="'.$this -> pageInfo['pageKeywords'].'" />'."\n";
        $html .= '</head>'."\n";
        $html .= '<body>'."\n";
        $html .= '<div id="container">'."\n";
        $html .= '<div id="header">'."\n";
        $html .= '<h1><a id="logo" href="index.php?page=home">Mixer</a></h1>'."\n";

        /*
        Checking to see if anyone is logged in or not so as to discern whether to display an empty
        login box, or a box that welcomes the user and gives them the option to either logout or
        to visit their user panel.
        */
        if($_SESSION['userName'] && !$_POST['deleteProfile']) {

        $html .= '<div id="loggedIn">'."\n";
        $html .= '<p>Welcome</p>'."\n";
        $html .= '<p>'.$_SESSION['userName'].'</p>'."\n";
        $html .= '<form method="post" action="index.php">';           
        $html .= '<a href="index.php?page=userPanel" id="userPanelButton" class="loginBtn">User Panel</a>'."\n";
        $html .= '<input type="submit" name="logoutButton" id="logoutButton" class="loginBtn" value="Logout" />'."\n";
        $html .= '</form>'."\n";
        $html .= '</div>'."\n";

        } else {

        $html .= '<div id="login">'."\n";
        $html .= '<form method="post" action="index.php">';
        $html .= '<div class="loginFields">'."\n";
        $html .= '<label for="userName">Username:</label>'."\n";
        $html .= '<input type="text" name="userName" id="userName" /></div>'."\n";
        $html .= '<div class="loginFields">'."\n";
        $html .= '<label for="userPassword">Password:</label>'."\n";
        $html .= '<input type="password" name="userPassword" id="userPassword" /></div>'."\n";
        $html .= '<input type="submit" name="loginButton" id="loginButton" class="loginBtn" value="Login" />'."\n";
        $html .= '<a href="index.php?page=register" id="registerButton" class="loginBtn">Register</a>'."\n";
        $html .= '</form>'."\n";
        $html .= '<p>'.$this -> model -> loginMsg.'</p>';
        $html .= '<div class="clearDiv"></div>'."\n";
        $html .= '</div>'."\n";

        }
        
        $html .= '<div class="clearDiv"></div>'."\n";
        
        $html .= $this -> displayNav();

        $html .= '</div>'."\n";
        
        $html .= '<div id="content">'."\n";
        
        return $html;
        
    }

    /*
    The function to display the Navigation located at the top of the page.
    */      
    private function displayNav() {
       
        $html .= '<div id="topNav">'."\n";

            $html .= '<a href="index.php?page=home&amp;mixerReset=true" class="evenTopNav" id="homeNav"><strong>HOME</strong></a>'."\n";
            $html .= '<a href="index.php?page=recipes&amp;recipes=all" class="evenTopNav" id="recipesNav"><strong>ALL RECIPES</strong></a>'."\n";
            $html .= '<a href="index.php?page=contact" class="evenTopNav" id="contactNav"><strong>CONTACT US</strong></a>'."\n";
            $html .= '<a href="index.php?page=about" class="evenTopNav" id="aboutNav"><strong>ABOUT US</strong></a>'."\n";

        $html .= '</div>'."\n";
      
       return $html;       
        
    } //closes display nav

    
    private function displayFooter() {
        

        
        $html = '</div>'."\n";
        $html .= '<div id="footer">'."\n";

        $html .= '<div id="bottomNav">'."\n";
        $html .= '<div class="evenBottomNav"><a href="index.php?page=home&amp;mixerReset=true" id="btmHome"><strong>HOME</strong></a></div>'."\n";
        $html .= '<div class="navSeperator"><p><strong>~</strong></p></div>'."\n";
        $html .= '<div class="evenBottomNav"><a href="index.php?page=recipes&amp;recipes=all" id="btmRecipes"><strong>ALL RECIPES</strong></a></div>'."\n";
        $html .= '<div class="navSeperator"><p><strong>~</strong></p></div>'."\n";
        $html .= '<div class="evenBottomNav"><a href="index.php?page=contact" id="btmContact"><strong>CONTACT US</strong></a></div>'."\n";
        $html .= '<div class="navSeperator"><p><strong>~</strong></p></div>'."\n";
        $html .= '<div class="evenBottomNav"><a href="index.php?page=about" id="btmAbout"><strong>ABOUT US</strong></a></div>'."\n";
        $html .= '<div class="navSeperator"><p><strong>~</strong></p></div>'."\n";
        $html .= '<div class="evenBottomNav"><a href="index.php?page=sitemap" id="btmSiteMap"><strong>SITEMAP</strong></a></div>'."\n";
        $html .= '</div>'."\n";
        $html .= '<p>Copyright 2012, Mixer Corporation Inc.</p>'."\n";
        $html .= '</div>'."\n";
        $html .= '</div>'."\n";
        $html .= '</body>'."\n";
        $html .= '</html>'."\n";


        
        return $html;
    }
    
    
    
}





?>