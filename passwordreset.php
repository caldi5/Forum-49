<?php

	require_once("includes/init.php");
	
	//-----------------------------------------------------
	// Send Password Reset Email
	//-----------------------------------------------------
	if(isset($_POST['username']))
	{
		sendPasswordResetEmail($_POST['username']);
		$success[] = "A password reset link was sent to your email.";
	}

	//-----------------------------------------------------
	// Validate Hash
	//-----------------------------------------------------

	/*
	 * Aperently a form submission keeps the getvariables, 
	 * therefor we need to check if this has already been done.
	 */
	if(isset($_GET['id']) && isset($_GET['hash']) && !isset($_SESSION['allowPasswordChange']))
	{
		/*
		 * We select id because well, we need to select something.
		 * We could also do Count(*), bind and count === 1...
		 */

		$stmt = $conn->prepare('SELECT id FROM passwordReset WHERE id = ? AND hash = ?');
		$stmt->bind_param('is', $_GET['id'], $_GET['hash']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows == 1)
		{
			/*
			 * Set sessionvariable to allow password change
			 * If this is set, we will display the form to allow the user to enter a new password
			 * We also store the userID here to keep track of which user to set the new password on
			 */
			$_SESSION['allowPasswordChange'] = $_GET['id'];

			//Delete the hash to prevent the link from beeing used more than once
			$stmt = $conn->prepare('DELETE FROM passwordReset WHERE id = ?');
			$stmt->bind_param('i', $_GET['id']);
			$stmt->execute();
		}
		else
		{
			$error[] = "Reset link has expired or is not valid";
		}

		$stmt->close();
	}

	//-----------------------------------------------------
	// Set New Password
	//-----------------------------------------------------
	if(isset($_POST['password']) && isset($_SESSION['allowPasswordChange']))
	{
		$currentUser->setPassword($_SESSION['allowPasswordChange'], $_POST['password']);
		$success[] = "You have sucessfully set you new password";
		session_destroy(); //because it's easyer than unset($_SESSION[])
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include("includes/standard_head.php"); ?>
		<title>Password Reset</title>
	</head>
	<body>
<?php include("includes/navbar.php");?>
		<!-- Content start -->		
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
<?php displayAlerts(); ?>
<?php 
	if(!isset($success) && !isset($error) && !isset($_SESSION['allowPasswordChange']))
	{
?>
			<h1>Forgott your password?</h1>
			<form method="post">
				<div class="form-group">
					<label>Username or Email:</label>
					<input type="text" maxlength="50" class="form-control" name="username" placeholder="Username/Email" required autofocus>
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit" name="PasswordReset">Reset Password</button>
			</form>
<?php
	}
?>
<?php 
	if(isset($_SESSION['allowPasswordChange']) && !isset($success))
	{
?>
			<h1>Choose New Password</h1>
			<form id="passwordResetForm" method="post">
				<div class="form-group">
					<label>New Password:</label>
					<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" data-rule-minlength="8" required>
				</div>
				<div class="form-group">				
					<label>Repeat New Password:</label>
					<input type="password" maxlength="50" class="form-control" name="confirmPassword" placeholder="Password again" data-rule-equalTo="#password" required>
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit" name="PasswordReset">Reset Password</button>
			</form>
<?php
	}
?>
			</div>
		</div>
		<!-- Content End -->		
<?php include("includes/standard_footer.php"); ?>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script>
		<script src="js\custom\jquery.validator.custom.bootstrapcompability.js"></script>
		<script src="js\custom\jquery.validator.custom.methods.js"></script>
		<!-- Content end -->
		<script>
		$("#passwordResetForm").validate(
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