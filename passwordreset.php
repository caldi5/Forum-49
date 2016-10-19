<?php
	session_start();
	require_once("functions/alerts.php");
	require_once("functions/security.php");
	require_once("includes/dbconn.php");

	if(isset($_POST['username']))
	{
		sendPasswordResetEmail($_POST['username']);
		$success[] = "A password reset link was sent to your email.";
	}

	if(isset($_POST['password']) && isset($_SESSION['allowPasswordChange']))
	{
		setPassword($$_SESSION['allowPasswordChange'], $_POST['password']);
		$success[] = 
	}

	if(isset($_GET['id']) && isset($_GET['hash']))
	{
			$stmt = $conn->prepare('SELECT id FROM passwordReset WHERE id = ? AND hash = ?');
			$stmt->bind_param('is', $_GET['id'], $_GET['hash']);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows == 1)
				$_SESSION['allowPasswordChange'] = $_GET['id'];
			else
				$error[] = "Reset link is not valid";
			$stmt->close();
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
<?php if(!isset($success) && !isset($error))
{
?>
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
<?php if(isset($_SESSION['allowPasswordChange']))
{
?>
			<form id="passwordResetForm" method="post">
				<div class="form-group">
					<label>Password:</label>
					<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" data-rule-minlength="8" required>
				</div>
				<div class="form-group">				
					<label>Repeat Password:</label>
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