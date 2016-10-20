<?php

	require_once("includes/init.php");

	if(!$user->loggedIn)
	{
		header("Location: /index.php");
		die();
	}
	
	if(isset($_POST["passwordchangeForm"])) 
	{

		//Validate password
		if(preg_match("/.*[A-Za-z].*/", $_POST["newPassword"]) !== 1)
			$error[] = "Your password must contain a letter";
		if(preg_match("/.*[0-9].*/", $_POST["newPassword"]) !== 1)
			$error[] = "Your password must contain a number";
		if(preg_match("/.*[^A-Za-z0-9].*/", $_POST["newPassword"]) !== 1)
			$error[] = "Your password must contain a special character";
		if(strlen($_POST["newPassword"]) < 8)
			$error[] = "Your password must contain at least 8 characters";
		if($_POST["newPassword"] !== $_POST["confirmPassword"])
			$error[] = "Passwords does not match";

		//-----------------------------------------------------
		//Insert the data in the database
		//-----------------------------------------------------
		if(!isset($error))
		{
			$user->changePassword($_SESSION["id"], $_POST['oldPassword'], $_POST['newPassword']);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title>Change Password</title>
	</head>
	<body>
<?php include("includes/navbar.php");?>

		<!-- Content start -->
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
				<h1>Change Password</h1>
<?php displayAlerts(); ?>
				<form id="passwordchangeForm" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Old Password:</label>
						<input type="password" maxlength="50" class="form-control" name="oldPassword" placeholder="Old Password" required>
					</div>
					<div class="form-group">
						<label>New Password:</label>
						<input type="password" maxlength="50" class="form-control" name="newPassword" id="newPassword" placeholder="New Password" data-rule-minlength="8" required>
					</div>
					<div class="form-group">				
						<label>Repeat New Password:</label>
						<input type="password" maxlength="50" class="form-control" name="confirmPassword" placeholder="New Password again" data-rule-equalTo="#newPassword" required>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="passwordchangeForm">Submit</button>
				</form>
			</div>
		</div>
<?php include("includes/standard_footer.php"); ?>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script>
		<script src="js\custom\jquery.validator.custom.bootstrapcompability.js"></script>
		<script src="js\custom\jquery.validator.custom.methods.js"></script>
		<!-- Content end -->
		<script>
		$("#passwordchangeForm").validate(
		{
			rules: 
			{
				newPassword: 
				{
					required: true,
					minlength: 8,
					containsLetter: true,
					containsNumber: true,
					containsSpecial: true
				}
			}
		});
		</script>
	</body>
</html>