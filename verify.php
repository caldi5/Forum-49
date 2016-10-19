<?php
	session_start();
	require_once("includes/dbconn.php");
	require_once("functions/user.php");
	require_once("functions/alerts.php");

	if(isset($_GET['email']) && isset($_GET['hash']))
	{
		if($_GET['hash'] === md5($email . 'SuperSiecretEmailVerificationStuff'))
			$success[] = "valid email";
	}
	else
	{
		$error[] = "Something is wrong"
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include("includes/standard_head.php"); ?>
		<title>Email Verification</title>
	</head>
	<body>
<?php include("includes/navbar.php");?>

		<!-- Content start -->
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
<?php displayAlerts(); ?>
			</div>
		</div>
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>