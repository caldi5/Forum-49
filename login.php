<?php
/*
	//======================================================================
	//login.php 
	//======================================================================
	Just a test to see if the regristrerd accounts works.

	# Code by Anton Roslund

	//-----------------------------------------------------
	//ToDo
	//-----------------------------------------------------

	# Bootstrap allerts can't be closed, figure out why.
	# See if register.php has the same problem.
*/

	session_start();
	require_once "includes/dbconn.php";
	require_once("functions/errors.php");

	if(isset($_POST["loginForm"])) 
	{
		$stmt = $conn->prepare('SELECT id, password, role, username FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $_POST["username"], $_POST["username"]);
		$stmt->execute();
		
		if($stmt->error !== "")
			$error = "SQL error: " . $stmt->error;
		
		$stmt->bind_result($id, $passwordHash, $role, $username);
		$stmt->fetch();
		
		if(password_verify($_POST["password"] , $passwordHash))
		{
			$_SESSION["id"] = $id;
			header("Location: index.php");
			die();
		}
		else
		{
			$error[] = "Login Failed";
		}

   		$stmt->free_result();
		$stmt->close();
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
			<div class="col-md-8 col-md-offset-2">
				<h1>Login</h1>
<?php 
if(isset($error))
	displayErrors($error); 
?>
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
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>