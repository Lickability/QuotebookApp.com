$(function() {	
	//Load Font
	try {
		Typekit.load();
	}catch(e) {}
	
	//Video Controls
	$('.hidden-phone .video, .hidden-phone .play').bind('click touchstart', toggleVideo);
	$('.hidden-phone .video').bind('ended', stopVideo);
	
	var playing = false;
	function toggleVideo() {
		if(!playing)
			startVideo();
		else
			pauseVideo();
	}
	function startVideo() {
		if(!playing) {
			$('.hidden-phone .video').get(0).play();
			$('.hidden-phone .blurb').animate({
				width: '0px',
				paddingLeft: '0px',
				paddingRight: '0px'
			});
			//$('.hidden-phone .video').attr('controls', 'controls');
			$('.hidden-phone .play').fadeOut(100);
			playing = true;
		}
	}
	function pauseVideo() {
		if(playing) {
			$('.hidden-phone .video').get(0).pause();
			$('.hidden-phone .blurb').animate({
				width: '270px',
				paddingLeft: '15px',
				paddingRight: '15px'
			});
			//$('.hidden-phone .video').removeAttr('controls');
			$('.hidden-phone .play').fadeIn(100);
			playing = false;
		}
	}
	function stopVideo() {
		pauseVideo();
		$('.hidden-phone .video').attr('posterframe', 'img/posterframe.jpg');
	}
	
	$('.video-container .play').mouseover(function() {
		$(this).animate({
			opacity: 1
		}, 150);
	}).mouseout(function() {
		$(this).animate({
			opacity: 0.7
		}, 150);
	});
		
		
	//Sublime-specific functions
	if (typeof sublimevideo != 'undefined') {
		sublimevideo.ready(function(){
			sublimevideo.onStart(function(sv){
				setTimeout(function() {startVideo();}, 900);
			});
		});
	}
	
	//FAQ / Updates navigation	
	$('.main .category').live('click', function() {
		$('body').animate({
			scrollTop: $($(this).attr('href')).offset().top + 'px'
		});
	});
	$(".collapse").collapse({
		toggle: false
	});
	
	//Contact Form
	$('#contact').modal({
		show: false
	})
	$('#contact .send').live('click', function() {
		var firstName = $('#contact .name-first').val(),
			lastName = $('#contact .name-last').val(),
			email = $('#contact .email').val(),
			message = $('#contact .message').val(),
			action = $('#contact .action').val();
			
		if(validateEmail(email)) {
			$('#contact .email').removeClass('invalid');
			if(message != '') {
				$('#contact .message').removeClass('invalid');
				$.ajax({
					type: 'post',
					url: action,
					data: 'Field107='+firstName+'&Field108='+lastName+'&Field2='+email+'&Field1='+message,
					success: function(data) {
						$('#contact').modal('hide');
					}
				});
			}else {
				$('#contact .message').addClass('invalid');
			}
		}else {
			$('#contact .email').addClass('invalid');
		}
		
		return false;
	});
	
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 
		
});