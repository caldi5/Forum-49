<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/../functions/alerts.php';

	class user 
	{ 
		public $id;
		public $username;
		public $role;
		public $email;
		public $validEmail;
		public $banned;
		public $loggedIn;

		function __construct()
		{
			global $conn;
			global $error;
			$this->loggedIn = false;

			if(isset($_SESSION['id']))
			{

				$stmt = $conn->prepare('SELECT id, username, role, email, validEmail, banned FROM users WHERE id = ?');
				$stmt->bind_param('i', $_SESSION['id']);
				$stmt->execute();
				$stmt->store_result();

				//if username does not exist in database, detroy session and redirect to index.
				if ($stmt->num_rows == 0)
				{
					unset($_SESSION['id']);
					session_destroy();
					header("Location: /index.php");
					die();
				}
				
				
				$stmt->bind_result($id, $username, $role, $email, $validEmail, $banned);
				$stmt->fetch();
				$stmt->free_result();
				$stmt->close();
				if($validEmail === 1)
				{
					$this->id = $id;
					$this->username = $username;
					$this->role = $role;
					$this->email = $email;
					$this->validEmail = $validEmail;
					$this->banned = $banned;
					$this->loggedIn = true;
				}
				else
				{
					$error[] = "You have not verified your email. <a href='/verify.php?username=" . $username ."&email=" . $email ."'>Resend Verification Email</a>";
					unset($_SESSION['id']);
					session_destroy();
				}
			}
		}
	

		private function setPassword($newPassword)
		{
			global $conn;
			//Hash new password
			$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

			$stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
			$stmt->bind_param('si', $passwordHash, $this->id);
			$stmt->execute();
			$stmt->close();
		}

		public function changePassword($userID, $oldPassword, $newPassword)
		{
			global $conn;
			global $error;
			global $success;

			//Validate old password
			$stmt = $conn->prepare('SELECT password FROM users WHERE id=?');
			$stmt->bind_param('i', $userID);
			$stmt->execute();			
			$stmt->bind_result($passwordHash);
			$stmt->fetch();
			$stmt->close();
			
			if(password_verify($password , $passwordHash))
			{
				$this->setPassword($userID, $newPassword);
				$success[] = "You've sucessfully changed your password";
			}
			else
			{
				$error[] = "Wrong current password";
			}
		}

		public function login($usernameOrEmail, $password)
		{
			global $conn;
			global $error;

			$stmt = $conn->prepare('SELECT id, username, password, role, email, validEmail, banned FROM users WHERE username = ? OR email = ?');
			$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
			$stmt->execute();
			
			if($stmt->error !== "")
				$error[] = "SQL error: " . $stmt->error;
			
			$stmt->bind_result($id, $username, $passwordHash, $role, $email, $validEmail, $banned);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();
			
			if(password_verify($password , $passwordHash))
			{
				$_SESSION["id"] = $id;
				$this->id = $id;
				$this->username = $username;
				$this->role = $role;
				$this->email = $email;
				$this->validEmail = $validEmail;
				$this->banned = $banned;
				$this->loggedIn = ture;
				return true;
			}
			else
			{
				$error[] = "Login Failed";
				return false;
			}
		}
		public function areFriendsWith($userID2)
		{
			global $conn;
			$stmt = $conn->prepare('SELECT userid from friends WHERE userid = ? AND userid2 = ?');
			$stmt->bind_param('ii', $this->id, $userID2);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				return false;
			else
				return true;
		}

	} 

	
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

	// Check if user1 are friends with user2
	function areFriends ($userID, $userID2)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT userid from friends WHERE (userid = ? AND userid2 = ?) OR (userid = ? AND userid2 = ?)');
		$stmt->bind_param('iiii', $userID, $userID2, $userID2, $userID);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			return false;
		else
			return true;
	}
	// Check if users already exists in frendsRequest table
	function requestExists ($userID, $userID2)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT userid from friendRequests WHERE (userid = ? AND userid2 = ?) OR (userid = ? AND userid2 = ?)');
		$stmt->bind_param('iiii', $userID, $userID2, $userID2, $userID);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			return false;
		else
			return true;
	}
	// Add userID and userID2 to friendsRequest table 
	function addFriendRequest ($userID, $userID2)
	{
		if(areFriends($userID, $userID2) || requestExists($userID, $userID2) || !userIDExists($userID) || !userIDExists($userID2)){
			return false;
		}
		global $conn;
		$created_at = time(); // Unix time
		$stmt = $conn->prepare('INSERT INTO friendRequests (userid, userid2, created_at) VALUES (?, ?, ?)');
		$stmt->bind_param('iii', $userID, $userID2, $created_at);
		$stmt->execute();
		$stmt->store_result();
		if (PRINT @@ROWCOUNT == 0)
			return false;
		else
			return true;
	}

?>