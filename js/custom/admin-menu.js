$(function() 
{
		var url = window.location.href;
		// passes on every "a" tag
		$(".adminMenu a").each(function() {
				// checks if its the same on the address bar
				if (url == (this.href)) {
						$(this).closest("li").addClass("active");
				}
		});
});        
