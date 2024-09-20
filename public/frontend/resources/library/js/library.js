$(window).scroll(function() {
	if($(this).scrollTop() > 50) $('#goTop').stop().animate({ bottom: '10px' }, 200);
	else $('#goTop').stop().animate({ bottom: '-60px' }, 200);
});
$(document).ready(function() {
	$('#goTop').click(function(event) {
		event.preventDefault();
		$('html, body').animate({scrollTop: 0},500);
	});
});