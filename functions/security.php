<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/user.php';

	function validatePassword($usernameOrEmail, $password)
	{
		global $conn;

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
?>
