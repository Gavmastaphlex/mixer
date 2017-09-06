$(document).ready(function() {

	$( document ).tooltip({
		position: { my: "center bottom+42", at: "center bottom" }
	});

	$('#hamburger-container').click(function() {

		if($('.hamburger').hasClass("is-active")) {

			$('.hamburger').removeClass( "is-active" );
			$('.container').trigger("click");

		} else {

			$('.hamburger').addClass( "is-active" );

		}

		$('#perspective').toggleClass("modalview animate"); 
	})


	$('.login-box button').click(function(event) {
		event.preventDefault();
		$('#loginButton').click();

	})

	$('.fa-sign-in').click(function(){
		if($('.login-box').css('display') != 'block') {
			$('.login-box').fadeIn();
			setTimeout(function(){
			  $(".login-box input:first").focus();
			}, 500);
		} else {
			$('.login-box').fadeOut();
		}
		
	})

	$('.fa-sign-out').click(function(){
		$(this).toggleClass("down"); 
		if($('.logout-box').css('display') != 'block') {
			$('.logout-box').fadeIn();
		} else {
			$('.logout-box').fadeOut();
		}
		
	})

	$('#logoutYes').click(function(){
		$('#logoutButton').click();
	})

	$('#logoutNo').click(function(){
		$('.logout-box').fadeOut('slow');
	})

	$(document).click(function() {
		if($('.login-box').css('display') == 'block') {
			$('.login-box').fadeOut('slow');
		}

		if($('.logout-box').css('display') == 'block') {
			$('.logout-box').fadeOut('slow');
		}
	});

	$(".fa-sign-in").click(function(e) {
		e.stopPropagation(); // This is the preferred method.
		        // This should not be used unless you do not want
		// any click events registering inside the div
	});

	$(".fa-sign-out").click(function(e) {
		e.stopPropagation(); // This is the preferred method.
		        // This should not be used unless you do not want
		// any click events registering inside the div
	});

	$(".login-box").click(function(e) {
		e.stopPropagation(); // This is the preferred method.
	});


	$('.animate .container').click(function() {
		$('.animate').removeClass('animate');
		$('.modalview').removeClass('modalview');
	})

});