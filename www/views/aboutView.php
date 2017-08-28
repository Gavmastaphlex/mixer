<?php

class AboutView extends View {
        
    protected function displayContent() {
        
        $html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";

        $html .= '<div id="aboutContainer">'."\n";
		$html .= '<p><em>The Mixer Corporation has a mission to help the student and those who have limited skills in the kitchen, because lets face it, we\'ve all been there before.</em></p> <br />'."\n";
		$html .= '<p><em>Help others out by registering and uploading your own recipes that you\'ve found to be easy to make and that require many ingredients.</em></p>'."\n";
		$html .= '</div>'."\n";
                
        return $html;        
    }
            
        
}


?>