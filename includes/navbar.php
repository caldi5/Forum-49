<?php require_once(__DIR__ . "/../functions/user.php"); ?>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span> 
					</button>
					<a href="/index.php" class="navbar-brand">Forum</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
<?php 			
	if($currentUser->loggedIn)
	{
?>
						<li><a href="/messages.php">Messages</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="glyphicon glyphicon-user"></span> <?php echo $currentUser->username; ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="/profile.php">Profile</a></li>
								<li><a href="/usersetting.php">Settings</a></li>
<?php 
if($currentUser->isAdmin())
{
	echo "\t\t\t\t\t\t\t\t<li><a href=\"/admin/\">Admin</a></li>\r\n"; 
}
?>
								<li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							</ul>
						  </li>
<?php	
	}
	else
	{ 
?>
						<li><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
						<li><a href="/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
<?php
	}
?>
					</ul>
				</div>
			</div>
		</nav>
