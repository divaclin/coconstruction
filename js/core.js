document.addEventListener("touchstart", function(){}, true);

$(window).load(function(){
	
});

$(document).ready(function(){
	$('footer').css("margin-top",$(window).height());//html
});

$(window).resize(function(){
	$('footer').css("margin-top",$(window).height());//html
});
