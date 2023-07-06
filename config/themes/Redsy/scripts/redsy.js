$(document).ready(function(){
	dropdownHover();
	$(".scroll-to-top").click(function()	{
		$("html, body").animate({ scrollTop: 0 }, 600);
		 return false;
	});
	$(window).scroll(function(){
			
		 var position = $(window).scrollTop();
		
		 if(position >= 200)	{
			$(".scroll-to-top").addClass("active")
		 }
		 else	{
			$(".scroll-to-top").removeClass("active")
		 }
	});
	$( ".navbar-toggle").click(function(){
		$("#menu").addClass("show-menu");
	});
}); 
$(window).resize(function(){
	dropdownHover();
});
function dropdownHover() {
	if ($(window).width() >= 768) { 
		$(".dropdown.first-level").hover(           
			function() {
				$(".dropdown-menu", this).stop( true, true ).fadeIn("fast");
				$(this).toggleClass("open");
			},
			function() {
				$(".dropdown-menu", this).stop( true, true ).fadeOut("fast");
				$(this).toggleClass("open");
		});
	}
	else
	{
		$(".dropdown.first-level .dropdown-toggle").each(function() {                 
			$(this).attr('data-toggle', 'dropdown');
		});
	}
}