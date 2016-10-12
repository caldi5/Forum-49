		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span> 
					</button>
					<a href="index.php" class="navbar-brand">Forum</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
<?php 			if(isset($_SESSION["username"]))
				{
?>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["username"]; ?> <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<?php echo "<li><a href=\"#\">Profile</a></li>"; ?>
									<?php echo "<li><a href=\"#\">Settings</a></li>"; ?>
									<?php echo "<li><a href=\"logout.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Logout</a></li>"; ?>

								</ul>
							  </li>
<?php	
						}
						else
						{
							echo "<li><a href=\"login.php\"><span class=\"glyphicon glyphicon-log-in\"></span> Sign In</a></li>";
							echo "<li><a href=\"register.php\"><span class=\"glyphicon glyphicon-user\"></span> Sign Up</a></li>";
						}
?>
					</ul>
				</div>
			</div>
		</nav>
