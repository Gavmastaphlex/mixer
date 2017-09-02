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

    $(window).scroll(function() {

    	clearTimeout($.data(this, 'scrollTimer'));
	    $.data(this, 'scrollTimer', setTimeout(function() {
	       	
	       	if ($(window).scrollTop() > 12) {
	    		activateHeader();
	    	} else {
	    		deactivateHeader();
	    	}

	    }, 50));

	});

	$('#header').hover(function(){
		activateHeader();
	}, function(){
		deactivateHeader();
	});


	$('.login-box button').click(function(event) {
		event.preventDefault();
		$('#loginButton').click();

	})

	$('.fa-sign-in').click(function(){
		if($('.login-box').css('display') != 'block') {
			$('.login-box').fadeIn();
			$('#header').addClass('login-logout-box-active');
			setTimeout(function(){
			  $(".login-box input:first").focus();
			}, 500);
		} else {
			$('.login-box').fadeOut();
			$('#header').removeClass('login-logout-box-active');
		}
		
	})

	$('.fa-sign-out').click(function(){
		$(this).toggleClass("down"); 
		if($('.logout-box').css('display') != 'block') {
			$('.logout-box').fadeIn();
			$('#header').addClass('login-logout-box-active');
		} else {
			$('.logout-box').fadeOut();
			$('#header').removeClass('login-logout-box-active');
		}
		
	})

	$('#logoutYes').click(function(){
		$('#logoutButton').click();
	})

	$('#logoutNo').click(function(){
		$('.logout-box').fadeOut('slow');
	})


	$('.animate .container').click(function() {
		$('.animate').removeClass('animate');
		$('.modalview').removeClass('modalview');
	})

	$(document).click(function() {
		if($('.login-box').css('display') == 'block') {
			$('.login-box').fadeOut('slow');
			// $('.fa-sign-in').toggleClass("down"); 
		}

		if($('.logout-box').css('display') == 'block') {
			$('.logout-box').fadeOut('slow');
			// $('.fa-sign-in').toggleClass("down"); 
		}
		$('#header').removeClass('login-logout-box-active');

		if ($(window).scrollTop() < 12) {
    		deactivateHeader();
    	}
		
		$('.ui-tooltip').hide();

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

});

function activateHeader() {

	$('#header').stop().animate({backgroundColor: 'rgba(20, 20, 20, 0.63)'}, 200);

	setTimeout(function(){
		$('#logo a').css({color: '#FFF'});
		$('#header .fa').css({color: '#FFF'});
		$('#hamburger-container').css({borderColor: '#FFF'});
		$('.hamburger-inner').addClass('whiteHamburger');
	}, 50);
}

function deactivateHeader() {

	if(!$('#header').hasClass('login-logout-box-active') && $(window).scrollTop() < 12) {

		$('#header').stop().animate({backgroundColor: 'transparent'}, 200);

		setTimeout(function(){
			$('#logo a').css({color: '#000'});
			$('#header .fa').css({color: '#000'});
			$('#hamburger-container').css({borderColor: '#000'});
			$('.hamburger-inner').removeClass('whiteHamburger');
		}, 100);
		
	}

}