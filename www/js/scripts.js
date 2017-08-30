$(document).ready(function() {
	$('#hamburger-container').click(function() {
		$('#perspective').toggleClass("modalview animate"); 
		if($('.hamburger').hasClass( "is-active" )) {
			$('.hamburger').removeClass( "is-active" );
			$('.container').trigger("click");
		} else {
			$('.hamburger').addClass( "is-active" );
		}
	})

	$('.login-box button').click(function(event) {
		event.preventDefault();
		$('#loginButton').click();

	})

	$('.fa-sign-in').click(function(){
		$(this).toggleClass("down"); 
		if($('.login-box').css('display') != 'block') {
			$('.login-box').slideDown('slow');
			setTimeout(function(){
			  $(".login-box input:first").focus();
			}, 500);
		} else {
			$('.login-box').slideUp('slow');
		}
		
	})

	$('.fa-sign-out').click(function(){
		$('#logoutButton').click();
	})


	$('.animate .container').click(function() {
		$('.animate').removeClass('animate');
		$('.modalview').removeClass('modalview');
	})

	$(document).click(function() {
		if($('.login-box').css('display') == 'block') {
			$('.login-box').slideUp('slow');
			$('.fa-sign-in').toggleClass("down"); 
		}
	});

	$(".fa-sign-in").click(function(e) {
		e.stopPropagation(); // This is the preferred method.
		return false;        // This should not be used unless you do not want
		// any click events registering inside the div
	});

	$(".login-box").click(function(e) {
		e.stopPropagation(); // This is the preferred method.
	});

});