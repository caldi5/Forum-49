<?php
	require_once __DIR__.'/../includes/dbconn.php';

	function sendValidationEmail($username, $email)
	{

		$hash = md5($email . 'SuperSiecretEmailVerificationStuff');

		$subject = 'DVA231 Forum - Email Verify'; 
		$message = 'Welcome '. $username .'!
Your account has been created
		 
Please click this link to verify your email:
http://srv247.se/verify.php?email='.$email.'&hash='.$hash.'
		 
';
		                     
		$headers = 'From: noreply@srv247.se' . "\r\n" .
    'Reply-To: noreply@srv247.se' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
		mail($email, $subject, $message, $headers);
	}

	function sendPasswordResetEmail($usernameOrEmail)
	{

		global $conn;
		global $error;

		//Get id if exists
		$stmt = $conn->prepare('SELECT id, username, email FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
		$stmt->execute();
		
		if($stmt->error !== "")
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		
		$stmt->bind_result($id, $username, $email);
		$stmt->fetch();
		$stmt->close();

		if(!isset($id) || empty($id))
			return false;

		//generate a random md5 hash
		$hash = md5(mt_rand());
	
		//Insert 
		$stmt = $conn->prepare('INSERT INTO passwordReset(id, hash) VALUES(?, ?) ON DUPLICATE KEY UPDATE hash=?, timestamp=CURRENT_TIMESTAMP');
		$stmt->bind_param('iss', $id, $hash, $hash);
		$stmt->execute();
		
		if($stmt->error !== "")
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}

		$stmt->close();

		//send email
		$subject = 'DVA231 Forum - Email Reset'; 
		$message = 'Dear '. $username .'!
Someone have requested to reset the password for you account
		 
Please click this link within 30 minutes to reset your password:
http://srv247.se/passwordreset.php?id='.$id.'&hash='.$hash.'
		 
';
		                     
		$headers = 'From: noreply@srv247.se' . "\r\n" .
    'Reply-To: noreply@srv247.se' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
		mail($email, $subject, $message, $headers);
	}
?>
