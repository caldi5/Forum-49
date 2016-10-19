<?php
	require_once __DIR__.'/../includes/dbconn.php';
	
	// Checks if a user with the given ID exists
	function userIDExists ($userID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT id from users WHERE id = ?');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			return false;
		else
			return true;
	}

	// Checks if a user with the given username exists
	function usernameExists ($username)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT id from users WHERE username = ?');
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			return false;
		else
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
			$stmt->free_result();
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

		if($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		return $username;
	}

	// Returns the user ID.
	function getUserID ($usernameOrEmail)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT id from users where username = ? OR email = ?');
		$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id);

		if($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $id;
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
			$stmt->free_result();
			$stmt->close();
			if($role === "admin")
				return true;
			else
				return false;
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
		$stmt->free_result();
		$stmt->close();
		if($role === "admin")
			return true;
		else
			return false;
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
		$stmt->free_result();
		$stmt->close();
		if($role === "admin")
			return true;
		else
			return false;
	}

	// Checks if a user is a moderator, returns true if he is, false if he's not logged in or not a moderator.
	function isModerator ($forumID)
	{
		global $conn;
		if (!isset($_SESSION["id"]))
			return false;

		$stmt = $conn->prepare('SELECT COUNT(*) from moderators WHERE userID = ? AND forumID = ?');
		$stmt->bind_param('ii', $_SESSION["id"], $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		if ($count == 0)
			return false;
		else
			return true;
		
	}

	// Checks if the user with the given user ID is moderator for the forum with the given forum ID.
	function isModeratorID ($userID, $forumID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT COUNT(*) from moderators WHERE userID = ? AND forumID = ?');
		$stmt->bind_param('ii', $userID, $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		if ($count == 0)
			return false;
		else
			return true;
	}

	// Checks if the user with the given username is moderator for the forum with the given forum ID.
	function isModeratorUsername ($username, $forumID)
	{
		global $conn;
		$userID = getUserID($username);
		if ($userID == false)
			return false;

		$stmt = $conn->prepare('SELECT COUNT(*) from moderators WHERE userID = ? AND forumID = ?');
		$stmt->bind_param('ii', $userID, $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		if ($count == 0)
			return false;
		else
			return true;
	}

	// Returns an array of all the forums that the user with the given user ID is moderator for.
	function isModeratorFor ($userID)
	{
		return $array;
	}

    // Check if two users are friends
    function areFriends ($userID, $userID2)
    {
        global $conn;
		$stmt = $conn->prepare('SELECT userid from friends WHERE userid = ? AND userid2 = ?');
		$stmt->bind_param('ii', $userID, $userID2);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			return false;
		else
			return true;
    }

?>