<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/user.php';

	function validatePassword($usernameOrEmail, $password)
	{
		global $conn;
		global $error;

		$stmt = $conn->prepare('SELECT password FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
		$stmt->execute();
		
		if($stmt->error !== "")
			$error[] = "SQL error: " . $stmt->error;
		
		$stmt->bind_result($passwordHash);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		
		if(password_verify($password , $passwordHash))
		{
			return true;
		}
		return false;
	}

	function changePassword($userID, $oldPassword, $newPassword)
	{
		global $conn;
		global $error;
		global $success;

		//Validate old password
		if(validatePassword(getUsernameID($userID), $oldPassword))
		{
			//Hash new password
			$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

			$stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
			$stmt->bind_param('si', $passwordHash, $userID);
			$stmt->execute();

			if($stmt->error !== "")
				$error[] = "SQL error: " . $stmt->error;
			else
				$success[] = "You've sucessfully changed your password";
			$stmt->close();
		}
		else
		{
			$error[] = "Wrong current password";
		}
	}

	function login($usernameOrEmail, $password)
	{
		global $error;
		
		if(validatePassword($usernameOrEmail, $password))
		{
			$_SESSION["id"] = getUserID($usernameOrEmail);
			header("Location: index.php");
			die();
		}
		else
		{
			$error[] = "Login Failed";
		}
	}

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
		$stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
		$stmt->execute();
		
		if($stmt->error !== "")
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->close();

		if(!isset($id) || empty($id))
			return false;

		//generate a random md5 hash
		$hash = md5(mt_rand());
	
		//Insert 
		$stmt = $conn->prepare('INSERT INTO passwordReset(id, hash) VALUES(?, ?) ON DUPLICATE KEY UPDATE') ;
		$stmt->bind_param('is', $id, $hash);
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
