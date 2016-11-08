		<!-- Standard footer start; javascript at the bottom, aperently... -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="/js/cookie/src/jquery.cookie.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/custom/friendrequests.js"></script>

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-86417974-1', 'auto');
			ga('send', 'pageview');

		</script>
		<!-- Standard footer end -->
		<?php 
			if ($currentUser->loggedIn) 
			{
				require_once("chat.php"); 
			}
		?>