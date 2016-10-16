<?php
/*
	//======================================================================
	//register.php 
	//======================================================================
	# Code by Anton Roslund
	
	//-----------------------------------------------------
	//Features
	//-----------------------------------------------------

	# Beeing able to login with both username and email.
	# Clientside field valedation with jQuery Validate.
	# Serverside valedation with php and regex.
	# Store errors in array so you'll see all of them at once.
	# Set session variables on sussessfull regristration.
	# Custom errormessages for letting the user know what chatacters their password needs
	# Maybe add seperate check for the password...
	# Adds the username and email back to the form IF (god knows how) the regristration fails.
	# Email Regex (RFC 5322 Official Standard)

	//-----------------------------------------------------
	//ToDo
	//-----------------------------------------------------
	
	# Use ajax to check if that username already exists.
	# Redirect to the index page on successfull regristration.

*/

	session_start();
	require_once("includes/dbconn.php");
	require_once("functions/user.php");

	// These two lines are important for captcha to work!
	require ('includes/recaptcha/src/autoload.php');
	$recaptcha = new \ReCaptcha\ReCaptcha('6LcuUwkUAAAAAFZS92ePbIhLGBo265zcTQ5e-WIW');

	//======================================================================
	//Regristration
	//======================================================================
	if(isset($_POST["registrationForm"])) 
	{
		//Captcha
		$response = $recaptcha->verify($_POST['g-recaptcha-response'],  $_SERVER['REMOTE_ADDR']);

		//-----------------------------------------------------
		//Validate form
		//-----------------------------------------------------
		
		//Username
		if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["username"]) !== 1)
			$error[] = "Only alfanumeric characters allowed in Username";
		if(strlen($_POST["username"]) < 5)
			$error[] = "Your Username must contain at least 5 characters";
		
		//Password
		if(preg_match("/.*[A-Za-z].*/", $_POST["password"]) !== 1)
			$error[] = "Your password must contain a letter";
		if(preg_match("/.*[0-9].*/", $_POST["password"]) !== 1)
			$error[] = "Your password must contain a number";
		if(preg_match("/.*[^A-Za-z0-9].*/", $_POST["password"]) !== 1)
			$error[] = "Your password must contain a special character";
		if(strlen($_POST["password"]) < 8)
			$error[] = "Your password must contain at least 8 characters";
		if($_POST["password"] !== $_POST["confirmPassword"])
			$error[] = "Passwords does not match";

		//Email
		if(preg_match('/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i', $_POST["email"]) !== 1)
			$error[] = "Email does not look valid";

		//Captcha
		if (!$response->isSuccess())
			$error[] = "Captcha failed";

		//-----------------------------------------------------
		//Insert the data in the database
		//-----------------------------------------------------
		if(!isset($error))
		{
			//Hash the password
			$passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

			if($passwordHash !== false)
			{
				$stmt = $conn->prepare('INSERT INTO users(username, password, email) VALUES (?,?,?)');
				$stmt->bind_param('sss', $_POST["username"], $passwordHash, $_POST["email"]);
				$stmt->execute();
				if($stmt->error !== "")
				{
					$error[] = "SQL error: " . $stmt->error;
				}
				//Sucessfull regristration.
				else
				{
					//Set session variables.
					$_SESSION["id"] = getUserID($_POST["username"]);

					//Redirect to index page.
					header("Location: index.php");
					die();
				}
				$stmt->close();
			}

		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include("includes/standard_head.php"); ?>
		<title>Register</title>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
<?php include("includes/navbar.php");?>

		<!-- Content start -->
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
				<h1>Registration</h1>
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
					//Temporary to show that you've sucessfully regristrerd.
					else if(isset($_POST["registrationForm"]))
					{
						echo "<div class=\"alert alert-success\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "<strong>Sucsess</strong> ";
						echo "</div>";
					}	
				?>
				<form id="registrationForm" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Username:</label>
						<input type="text" maxlength="50" class="form-control" name="username" placeholder="Username" data-rule-minlength="5" pattern="[a-zA-Z0-9]+" <?php if(isset($error) && isset($_POST["username"])){ echo "value=\"" . $_POST["username"] . "\" ";}?>required autofocus>
					</div>
					<div class="form-group">
						<label>Password:</label>
						<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" data-rule-minlength="8" required>
					</div>
					<div class="form-group">				
						<label>Repeat Password:</label>
						<input type="password" maxlength="50" class="form-control" name="confirmPassword" placeholder="Password again" data-rule-equalTo="#password" required>
					</div>
					<div class="form-group">				
						<label>Email Address:</label>
						<input type="email" maxlength="50" class="form-control" name="email" placeholder="anna.andersson@example.com" <?php if(isset($error) && isset($_POST["email"])){ echo "value=\"" . $_POST["email"] . "\" ";}?>required>
					</div>
					<input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
					<div class="g-recaptcha" data-sitekey="6LcuUwkUAAAAAP4mcb-qcOJOs_gdrjKRdXzlilHX"></div>
					<br>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="registrationForm">Submit</button>
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
		$("#registrationForm").validate(
		{
			ignore: ".ignore",
			rules: 
			{
				password: 
				{
					required: true,
					minlength: 8,
					containsLetter: true,
					containsNumber: true,
					containsSpecial: true
				},
				hiddenRecaptcha: 
				{
					required: function () 
					{
						if (grecaptcha.getResponse() == '')
							return true;
						else 
							return false;
					}
				},
				username: 
				{
					required: true,
					minlength: 5,
					pattern: "[a-zA-Z0-9]+"
				},
				email:
				{
					required: true
				}
			},
			messages: 
			{
				username: 
				{
					minlength: "Your username must contain at least 5 characters" ,
					pattern: "Only alfanumeric characters allowed"
				},
				hiddenRecaptcha:
				{
					required: "Please enter the captcha"
				}
			}
		});
		</script>
	</body>
</html>