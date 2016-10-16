<?php
	require_once '/var/www/DVA231_Project/includes/dbconn.php';
	
	// Checks if a user with the given ID exists
	function userIDExists ($userID)
	{

		return true;
	}

	// Checks if a user with the given username exists
	function usernameExists ($username)
	{

		return true;
	}

	// Returns the username.
	function getUsername ()
	{
		if (isset($_SESSION["id"]))
		{
			global $conn;
			$stmt = $conn->prepare('SELECT username FROM users WHERE id = ?');
			$stmt->bind_param('i', $_SESSION["id"]);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($username);
			$stmt->fetch();
			$stmt->close();
			return $username;
		}
		else
		{
			return false;
		}
	}

	// Returns the username.
	function getUsernameID ($userID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT username FROM users WHERE id = ?');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($username);
		$stmt->fetch();
		$stmt->close();
		return $username;
	}

	// Returns the user ID.
	function getUserID ($username)
	{
		return 1;
	}

	// Checks if the user is logged in.
	function isLoggedIn()
	{
		return isset($_SESSION["id"]);
	}

	// Checks if a user is an admin, returns true if he is, false if he's not logged in or not an admin.
	function isAdmin ()
	{
		if (isset($_SESSION["id"]))
		{
			global $conn;
			$stmt = $conn->prepare('SELECT role FROM users WHERE id = ?');
			$stmt->bind_param('i', $_SESSION["id"]);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($role);
			$stmt->fetch();
			if($role === "admin")
				return true;
			else
				return false;
			$stmt->close();
		}
		else
		{
			return false;
		}
	}

	// Checks if a user with the given user ID is an admin.
	function isAdminID ($userID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT role FROM users WHERE id = ?');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($role);
		$stmt->fetch();
		if($role === "admin")
			return true;
		else
			return false;
		$stmt->close();
	}

	// Checks if a user with the given username is an admin.
	function isAdminUsername ($username)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT role FROM users WHERE username = ?');
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($role);
		$stmt->fetch();
		if($role === "admin")
			return true;
		else
			return false;
		$stmt->close();
	}

	// Checks if a user is a moderator, returns true if he is, false if he's not logged in or not a moderator.
	function isModerator ($forumID)
	{
		return true;
	}

	// Checks if the user with the given user ID is moderator for the forum with the given forum ID.
	function isModeratorID ($userID, $forumID)
	{
		return true;
	}

	// Checks if the user with the given username is moderator for the forum with the given forum ID.
	function isModeratorUsername ($username, $forumID)
	{
		return true;
	}

	// Returns an array of all the forums that the user with the given user ID is moderator for.
	function isModeratorFor ($userID)
	{
		return $array;
	}

?>