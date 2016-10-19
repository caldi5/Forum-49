<?php

	session_start();
	require_once("includes/dbconn.php");
	require_once("functions/user.php");
	require_once("functions/alerts.php");

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
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
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
<?php displayAlerts(); ?>
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
					pattern: "[a-zA-Z0-9]+",
					remote: "/ajax/exists.php"
				},
				email:
				{
					required: true,
					remote: "/ajax/exists.php"
				}
			},
			messages: 
			{
				username: 
				{
					minlength: "Your username must contain at least 5 characters" ,
					pattern: "Only alfanumeric characters allowed",
					remote: "Sorry that username is already taken"
				},
				hiddenRecaptcha:
				{
					required: "Please enter the captcha"
				},
				email:
				{
					remote: "This email exist in our database, maybe you have already registerd?"
				}
			}
		});
		</script>
	</body>
</html>