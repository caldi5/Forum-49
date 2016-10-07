<!-- regrister.php 

----ToDo----

* Enforce wich characters can be used in username
* Enforce password policy
* Maybe more errorhandling.
* Use ajax to check if that username allready exists.
* Redirect to the index page on successfull regristration.

--Final touches--
* Change min password lenth to 12 (data-rule-minlength="4")


-->
<?php
	include("dbconn.php");
	session_start();

	//Newsupload
	if(isset($_POST["registrationForm"])) 
	{
		//valedate form

		//Sanatize imput. probably not needed since we add the imput as parameters
		$username = $conn->real_escape_string($_POST["username"]);
		$password = crypt($_POST["password"], $dbPasswordSalt);
		$email = $conn->real_escape_string($_POST["email"]);

		if(!isset($error))
		{
			$stmt = $conn->prepare('INSERT INTO users(username, password, email) VALUES (?,?,?)');
			$stmt->bind_param('sss',$username,$password,$email);
			$stmt->execute();
			if($stmt->error !== "")
				$error = "SQL error: " . $stmt->error;
		}
	}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Register</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-md-8 col-md-offset-2">
				<h1>Registration</h1>
				<?php
					if(isset($error))
					{
						echo "<div class=\"alert alert-danger\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "<strong>Error</strong> ".$error;
						echo "</div>";
					}
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
						<input type="text" maxlength="50" class="form-control" name="username" placeholder="Username" data-rule-minlength="5" required autofocus>
					</div>
					<div class="form-group">
						<label>Password:</label>
						<input type="password" maxlength="50" class="form-control" name="password" id="password" placeholder="Password" data-rule-minlength="4" required>
					</div>
					<div class="form-group">				
						<label>Repeat Password:</label>
						<input type="password" maxlength="50" class="form-control" name="confirmPassword" placeholder="Password again" data-rule-equalTo="#password" required>
					</div>
					<div class="form-group">				
						<label>Email Address:</label>
						<input type="email" maxlength="50" class="form-control" name="email" placeholder="anna.andersson@example.com" required>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="registrationForm">Submit</button>
				</form>
				<script>
				$.validator.setDefaults({
				    highlight: function(element) {
				        $(element).closest('.form-group').addClass('has-error');
				    },
				    unhighlight: function(element) {
				        $(element).closest('.form-group').removeClass('has-error');
				    },
				    errorElement: 'span',
				    errorClass: 'help-block',
				    errorPlacement: function(error, element) {
				        if(element.parent('.input-group').length) {
				            error.insertAfter(element.parent());
				        } else {
				            error.insertAfter(element);
				        }
				    }
				});
				$("#registrationForm").validate();
				</script>
			</div>
		</div>
	</body>
</html>