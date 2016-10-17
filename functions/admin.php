<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/errors.php';
	require_once __DIR__.'/get.php';

	function newForum($forumName, $description, $categoryName, $ordering)
	{
		global $conn;
		global $error;

		$categoryID = getCategoryID($categoryName);

		if($categoryID === false)
			return false;

		$stmt = $conn->prepare('INSERT INTO forums(name, description, category, ordering) VALUES (?,?,?,?)');
		$stmt->bind_param('ssii', $forumName, $description, $categoryID, $ordering);
		$stmt->execute();
		if($stmt->error !== "")
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}

	function deleteForum($forumID)
	{
		//for each post in form
			//Delete coments postID
			//delte post
		//Delete forum
	}
	function newCategory($categoryName, $ordering)
	{
		global $conn;
		global $error;
		
		$stmt = $conn->prepare('INSERT INTO categories(name, ordering) VALUES (?,?)');
		$stmt->bind_param('si', $categoryName, $ordering);
		$stmt->execute();
		if($stmt->error !== "")
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}
	
	function deleteCategory($categoryID)
	{
		//only allow deletion of categories with no forums in them.
		if(getNumberOfForums($categoryID) !== 0)
		{
			return false;
		}

		global $conn;
		$stmt = $conn->prepare('DELETE FROM categories WHERE id = ?');
		$stmt->bind_param('i', $categoryID);
		$stmt->execute();
		if($stmt->error !== "")
		{
			return false;
		}
		$stmt->close();
		return true;
	}

	function changeCategory($forumID, $categoryID)
	{

	}

	function deleteUser($userID)
	{
		global $conn;
		$stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		if($stmt->error !== "")
		{
			return false;
		}
		$stmt->close();
		return true;
	}

?>