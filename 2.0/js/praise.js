$(function() {
	var pages = 3, pageIndex = 0, timer, pageTime = 10000, resumeTime = 3000, animationDuration = 600;
	timer = setTimeout(switchPraise, pageTime);
	function switchPraise(setTimer) {
		if(typeof setTimer === 'undefined')
			setTimer = true;
		if(++pageIndex > pages-1) pageIndex = 0;
		$('.praise.hidden-phone .wrap').animate({
			'left': -pageIndex * $('.praise.hidden-phone').width() + 'px'
		}, animationDuration);
		$('.praise.hidden-phone .controls .current').removeClass('current');
		$('.praise.hidden-phone .controls .page'+(pageIndex+1)).addClass('current');
		if(setTimer)
			timer = setTimeout(switchPraise, pageTime);
	}
	$('.praise.hidden-phone .controls a').click(function() {
		if(!$(this).hasClass('current')) {
			pageIndex = $(this).attr('href') - 2;
			$('.praise.hidden-phone .controls .pause').removeClass('pause');
			switchPraise(false);
			$('.praise.hidden-phone .controls .current').addClass('pause');
		}
		return false;
	});
	$('.praise.hidden-phone').mouseenter(function() {
		clearTimeout(timer);
		$('.praise.hidden-phone .controls .current').addClass('pause');
	}).mouseleave(function() {
		$('.praise.hidden-phone .controls .pause').removeClass('pause');
		timer = setTimeout(switchPraise, resumeTime);
	});
});