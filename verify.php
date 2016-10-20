<?php
	session_start();
	require_once("functions/alerts.php");
	require_once("includes/dbconn.php");

	if(isset($_GET['email']) && isset($_GET['hash']))
	{
		if($_GET['hash'] === md5($_GET['email'] . 'SuperSiecretEmailVerificationStuff'))
		{
			$stmt = $conn->prepare('UPDATE users SET validEmail = 1 WHERE email = ?');
			$stmt->bind_param('s', $_GET['email']);
			$stmt->execute();

			if($stmt->error !== "")
				$error[] = "SQL error: " . $stmt->error;
			else
			{
				$_SESSION['id'] = getUserID($_GET['email']);
				$success[] = "Your verified your email!";
			}
			$stmt->close();
		}
		else
			$error[] = "Something is wrong";
	}
	else
	{
		$error[] = "Something is wrong";
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