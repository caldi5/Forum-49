<?php

	require_once("includes/init.php");

	if(isset($_POST["loginForm"])) 
	{
		if($currentUser->login($_POST["username"], $_POST["password"]) === true)
		{
			header("Location: /index.php");
			die();
		} 
	}
?>
<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title>Login</title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="col-md-8 col-md-offset-2">
				<h1>Login</h1>
				<form id="loginForm" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Username or Email:</label>
						<input type="text" maxlength="50" class="form-control" name="username" placeholder="Username/Email" required autofocus>
					</div>
					<div class="form-group">
						<label>Password:</label>
						<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" required>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="loginForm">Submit</button>
				</form>
				<a href="/passwordreset.php">Forgot your password?</a>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>