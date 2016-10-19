<?php
	session_start();
	require_once("functions/alerts.php");
	require_once("functions/security.php");
	require_once("includes/dbconn.php");

	if(isset($_GET['reset']))
		sendPasswordResetEmail($_GET['reset']);

	if(isset($_GET['id']) && isset($_GET['hash']))
	{
			$stmt = $conn->prepare('SELECT id FROM passwordReset WHERE id = ? AND hash = ?');
			$stmt->bind_param('is', $_GET['id'], $_GET['hash']);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows == 1)
				$success[] = "you should be able to change your password";
			else
				$error[] = "Reset link is not valid";
			$stmt->close();

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