<?php

class SitemapView extends View {
        
    protected function displayContent() {

    	$html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";


    	$html .= '<div id="sitemapContainer">'."\n";
    	
        

        $html .= '<a href="index.php?page=home">Home</a>'."\n";
        $html .= '<a href="index.php?page=recipes">Recipes</a>'."\n";
        $html .= '<a href="index.php?page=about">About</a>'."\n";
        $html .= '<a href="index.php?page=contact">Contact</a>'."\n";
        $html .= '<a href="index.php?page=register">Register</a>'."\n";

        $html .= '</div>'."\n";
                
        return $html;        
    }
            
        
}


?>