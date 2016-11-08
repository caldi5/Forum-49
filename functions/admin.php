<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/alerts.php';

	function newForum($forumName, $description, $categoryID, $guestAccess, $ordering)
	{
		global $conn;
		global $error;

		if($categoryID === false)
			return false;

		$stmt = $conn->prepare('INSERT INTO forums(name, description, category, guestAccess, ordering) VALUES (?,?,?,?,?)');
		$stmt->bind_param('ssiii', $forumName, $description, $categoryID, $guestAccess, $ordering);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}

	function newCategory($categoryName, $ordering)
	{
		global $conn;
		global $error;
		
		$stmt = $conn->prepare('INSERT INTO categories(name, ordering) VALUES (?,?)');
		$stmt->bind_param('si', $categoryName, $ordering);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}
	
	function deleteUser($userID)
	{
		global $conn;
		$stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			return false;
		}
		$stmt->close();
		return true;
	}

?>