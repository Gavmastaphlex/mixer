<?php

class ContactView extends View {
        
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

    	if($_POST['contactSubmit']) {
					$vresult = $this -> model -> validateContactForm();

					    }

					    if(is_array($vresult)) {
			            extract($vresult);
			        }
        
        $html = '<h2>'.$this -> pageInfo['pageHeading'].'</h2>'."\n";

        if($emailSent == true) {
        	$html .= '<h3>You message has been successfully sent!</h3>'."\n";
        }


		$html .= '<script type="text/javascript">'."\n";
		$html .= 'function adjust_textarea(h) {'."\n";
		$html .= 'h.style.height = "20px";'."\n";
		$html .= 'h.style.height = (h.scrollHeight)+"px";'."\n";
		$html .= '}'."\n";
		$html .= '</script>'."\n";




		$html .= '<div class="form-style-8">'."\n";
		$html .= '<h2>Send us a message</h2>'."\n";
		$html .= '<form method="post" action="index.php?page=contact">'."\n";
		$html .= '<input type="text" name="contactFullName" placeholder="Full Name" value="'.$_POST['contactFullName'].'" />'."\n";
		$html .= '<div class="contactError">'.$FullNameMsg.'</div>';

		$html .= '<input type="email" name="contactEmail" placeholder="Email" value="'.$_POST['contactEmail'].'" />'."\n";
		$html .= '<div class="contactError">'.$emailMsg.'</div>';

		$html .= '<!--input type="url" name="field3" placeholder="Website" /-->'."\n";


		$html .= '<textarea name="contactMessage" placeholder="Message" onkeyup="adjust_textarea(this)">'.$_POST['contactMessage'].'</textarea>'."\n";
		$html .= '<div class="contactError">'.$messageMsg.'</div>';

		$html .= '<input type="submit" name="contactSubmit" id="contactSubmit" value="Submit" />'."\n";
		

		$html .= '</form>'."\n";


		$html .= '</div>'."\n";







        $html .= '<!--div id="contactForm">'."\n";
		$html .= '<form method="post" action="index.php?page=contact">'."\n";

		$html .= '<label for="contactTitle">Title:</label>'."\n";

		$html .= '<input type="text" name="contactTitle" id="contactTitle" value="'.$_POST['contactTitle'].'" /><br />'."\n";
		$html .= '<div class="contactError">'.$titleMsg.'</div>';
		$html .= '<br />'."\n";





		$html .= '<label for="contactFirstName">First Name:</label>'."\n";
		$html .= '<input type="text" name="contactFirstName" id="contactFirstName" value="'.$_POST['contactFirstName'].'" /><br />'."\n";
		$html .= '<div class="contactError">'.$firstNameMsg.'</div>';
		$html .= '<br />'."\n";





		$html .= '<label for="contactLastName">Family Name:</label>'."\n";
		$html .= '<input type="text" name="contactLastName" id="contactLastName" value="'.$_POST['contactLastName'].'" /><br />'."\n";
		$html .= '<div class="contactError">'.$lastNameMsg.'</div>';
		$html .= '<br />'."\n";





		$html .= '<label for="contactEmail">Email Address:</label>'."\n";
		$html .= '<input type="text" name="contactEmail" id="contactEmail" value="'.$_POST['contactEmail'].'" /><br />'."\n";
		$html .= '<div class="contactError">'.$emailMsg.'</div>';
		$html .= '<br />'."\n";





		$html .= '<label for="contactMessage">Message:</label>'."\n";

		$html .= '<textarea id="contactMessage" name="contactMessage" rows="3" cols="18">'.$_POST['contactMessage'].'</textarea>'."\n";

		$html .= '<br />'."\n";





		$html .= '<div class="clearDiv"></div>'."\n";
		$html .= '<div class="contactError">'.$messageMsg.'</div>';
		$html .= '<br />'."\n";





		$html .= '<div class="clearDiv"></div>'."\n";
		$html .= '<input type="submit" name="contactSubmit" id="contactSubmit" value="Submit" />'."\n";

		$html .= '<div class="clearDiv"></div>'."\n";

		$html .= '<p><strong>*All fields required*</strong></p>'."\n";

		$html .= '</form>'."\n";

		$html .= '<div class="clearDiv"></div>'."\n";

		$html .= '</div-->';
                
        return $html;        
    }
            
        
}


?>