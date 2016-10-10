<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand">Forum</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
			<?php
				if(isset($_SESSION["username"]))
					echo "<li><a href=\"logout.php\">Logout</a></li>";
				else
				{
					echo "<li><a href=\"login.php\">Sign In</a></li>";
					echo "<li><a href=\"register.php\">Sign Up</a></li>";
				}
			?>
			</ul>
		</div>
	</div>
</nav>