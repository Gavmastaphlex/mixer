$(document).ready(function() {
	$('.hamburger').click(function() {
		if($(this).hasClass( "is-active" )) {
			$('.hamburger').removeClass( "is-active" );
			$('.container').trigger("click");
			console.log('The container should be clicked...');
		} else {
			$('.hamburger').addClass( "is-active" );
		}
	})

	$('.login-box button').click(function(event) {
		event.preventDefault();
		$('#loginButton').click();

	})

	$('#logoutBtn.myButton').click(function(event) {
		event.preventDefault();
		$('#logoutButton').click();

	})


var search = 0;
var searchRunning = 0;
var scrollThreshold = 0;

	$(window).scroll(function() {

		 var navHeight =  120;

		 $('.pulse-down').fadeOut('slow');
	 
	    clearTimeout($.data(this, 'scrollTimer'));
	    $.data(this, 'scrollTimer', setTimeout(function() {
	       
			if ($(window).scrollTop() > navHeight && scrollThreshold == 0) {

				$('#header').css({position:'fixed', top: '-114px', backgroundColor: 'rgba(106, 106, 106, 0.58)', height: '80px'});
				$('#header').animate({top: '0px'}, 300);
				$('#header h1').animate({width: '192px', height:'64px'}, 300);
				$('#header h1 a').animate({fontSize: '70px'}, 300);

				scrollThreshold = 1;

			} else if ($(window).scrollTop() == 0 && scrollThreshold == 1) {

				$('#header').css({backgroundColor: 'transparent', position:'absolute', top: '0px'});
				$('#header h1').animate({ width: '292px', height:'124px'}, 300);
				$('#header h1 a').animate({fontSize: '110px'}, 300);
				$('#header').animate({height: '130px'}, 300);

				scrollThreshold = 0;

			} else if ($(window).scrollTop() < navHeight && scrollThreshold == 1) {

				$('#header').css({backgroundColor: 'transparent'});

				$( "#header" ).animate({
				    top: '-114px',
				    backgroundColor: 'transparent',
				    fontSize : '13px',
				    height: '80px'
				  }, 300, function() {
				    $('#header').css({position:'absolute', top: '0px', });
				  });

				$('#header h1').animate({width: '192px', height:'64px'}, 300);
				$('#header h1 a').animate({fontSize: '70px'}, 300);

				scrollThreshold = 0;

			}
	    }, 100));
	});


});