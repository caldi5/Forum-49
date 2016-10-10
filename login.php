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

	include("includes/dbconn.php");
	session_start();

	if(isset($_POST["loginForm"])) 
	{
		$stmt = $conn->prepare('SELECT password, role, username FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $_POST["username"], $_POST["username"]);
		$stmt->execute();
		
		if($stmt->error !== "")
			$error = "SQL error: " . $stmt->error;
		
		$stmt->bind_result($passwordHash, $role, $username);
		$stmt->fetch();
		
		if(password_verify($_POST["password"] , $passwordHash))
		{
			/*
			 * You might ask yourself, why bother taking the username from the database?
			 * $_SESSION["username"] = $_POST["username"];
			 * Well, that's because then the user can login with whatever case combenation they want. like uSERnAmE
			 */
			$_SESSION["username"] = $username;
			$_SESSION["role"] = $role;
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
<?php
	include("includes/standard_head.php");
?>
		<title>Login</title>
	</head>
	<body>
<?php
	include("includes/navbar.php");
?>
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
						echo "<strong>Wellcome</strong> " . $_SESSION["username"] . ". Your role is: " . $_SESSION["role"];
						echo "</div>";
					}	
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
	</body>
</html>