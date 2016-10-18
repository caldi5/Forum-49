<?php

	function validatePasswordID($userID, $password)
	{
		$stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
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

	function changePassword($userID $oldPassword, $newPassword)
	{
		if(validatePasswordID($userID $oldPassword))
		{
			//Hash password
			$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
			$stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
			$stmt->bind_param('ss', $passwordHash, $userID);
			$stmt->execute();

			if($stmt->error !== "")
				$error[] = "SQL error: " . $stmt->error;

			$stmt->close
		}
	}

	function login($usernameOrEmail, $password)
	{
		$stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ? OR email = ?');
		$stmt->bind_param('ss', $usernameOrEmail, $password);
		$stmt->execute();
		
		if($stmt->error !== "")
			$error = "SQL error: " . $stmt->error;
		
		$stmt->bind_result($id, $passwordHash);
		$stmt->fetch();
		$stmt->close();
		
		if(password_verify($password, $passwordHash))
		{
			$_SESSION["id"] = $id;
			header("Location: index.php");
			die();
		}
		else
		{
			$error[] = "Login Failed";
		}
	}
?>
