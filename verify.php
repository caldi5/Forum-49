<?php

	require_once("includes/init.php");

	if(isset($_GET['username']) && isset($_GET['email']))
	{
		sendValidationEmail($_GET['username'], $_GET['email']);
	}

	if(isset($_GET['email']) && isset($_GET['hash']))
	{
		if($_GET['hash'] === md5($_GET['email'] . 'SuperSiecretEmailVerificationStuff'))
		{
			$stmt = $conn->prepare('UPDATE users SET validEmail = 1 WHERE email = ?');
			$stmt->bind_param('s', $_GET['email']);
			$stmt->execute();

			if(!empty($stmt->error))
				$alerts[] = new alert("danger", "Error:", "SQL error: " . $stmt->error);
			else
			{
				$_SESSION['id'] = getUserID($_GET['email']);
				$alerts[] = new alert("success", "Success:", "Your verified your email!");
			}
			$stmt->close();
		}
		else
			$alerts[] = new alert("danger", "Error:", "Something is wrong");
	}
	else
	{
		$alerts[] = new alert("danger", "Error:", "Something is wrong");
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