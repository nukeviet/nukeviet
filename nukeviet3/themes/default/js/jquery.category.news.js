var obj = null;

function checkHover() {
	if (obj) {
		obj.find('ul').fadeOut('fast');	
	} //if
} //checkHover

$(document).ready(function() {
	$('#category_news > li').hover(function() {
		if (obj) {
			obj.find('ul').fadeOut('fast');
			obj = null;
		} //if
		
		$(this).find('ul').fadeIn('fast');
	}, function() {
		obj = $(this);
		setTimeout(
			"checkHover()",
			400);
	});
});