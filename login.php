<?php
/*
	//======================================================================
	//login.php 
	//======================================================================
	Just a test to see if the regristrerd accounts works.

	# Code by Anton Roslund
*/

	include("dbconn.php");
	session_start();

	if(isset($_POST["loginForm"])) 
	{
		$stmt = $conn->prepare('SELECT password FROM users WHERE username = ?');
		$stmt->bind_param('s', $_POST["username"]);
		$stmt->execute();
		
		if($stmt->error !== "")
			$error = "SQL error: " . $stmt->error;
		
		$stmt->bind_result($passwordHash);
		$stmt->fetch();
		
		if(password_verify($_POST["password"] , $passwordHash))
		{
			//Do some session stuff
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
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
				<h1>Login</h1>
				<?php
					if(isset($error))
					{
						foreach ($error as $err) 
						{
							echo "<div class=\"alert alert-danger\">";
							echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
							echo "<strong>Error</strong> ".$err;
							echo "</div>";
						}
					}
					else if(isset($_POST["loginForm"]))
					{
						echo "<div class=\"alert alert-success\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "<strong>Sucsess</strong> Login = true! <3";
						echo "</div>";
					}	
				?>
				<form id="loginForm" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Username:</label>
						<input type="text" maxlength="50" class="form-control" name="username" placeholder="Username" required autofocus>
					</div>
					<div class="form-group">
						<label>Password:</label>
						<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" required>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="loginForm">Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>