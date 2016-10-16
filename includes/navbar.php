<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/user.php"); ?>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span> 
					</button>
					<a href="http://<?php echo $_SERVER['SERVER_NAME'];?>/index.php" class="navbar-brand">Forum</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
<?php 			
	if(isLoggedIn())
	{
?>
						<li><a href="messages.php">Messages</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="glyphicon glyphicon-user"></span> <?php echo getUsername(); ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#">Profile</a></li>
								<li><a href="#">Settings</a></li>
<?php 
if(isadmin() === true)
{
}
?>
								<li><a href="http://<?php echo $_SERVER['SERVER_NAME'];?>/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							</ul>
						  </li>
<?php	
	}
	else
	{ 
?>
						<li><a href="http://<?php echo $_SERVER['SERVER_NAME'];?>/login.php"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
						<li><a href="http://<?php echo $_SERVER['SERVER_NAME'];?>/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
<?php
	}
?>
					</ul>
				</div>
			</div>
		</nav>
