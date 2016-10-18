<?php
	require_once __DIR__.'/../includes/dbconn.php';
	require_once __DIR__.'/alerts.php';
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

	function deleteForum($forumID)
	{
		global $conn;

		//Delete all posts in forum
		$stmt = $conn->prepare('SELECT id FROM posts WHERE forum = ?');
		$stmt->bind_param('i', $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($postID);
		while($stmt->fetch())
		{
			deletePost($postID);
		}

		$stmt->free_result();
		$stmt->close();

		//Delete forum
			$stmt = $conn->prepare('DELETE FROM forums WHERE id = ?');
		$stmt->bind_param('i', $forumID);
		$stmt->execute();
		if($stmt->error !== "")
		{
			return false;
		}
		$stmt->close();
	}

	//allow moderators to use this function?
	function deletePost($postID)
	{
		global $conn;

		//Delete all comments beloning to $postID
		$stmt = $conn->prepare('DELETE FROM comments WHERE postID = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		if($stmt->error !== "")
		{
			return false;
		}
		$stmt->close();

		//Delete the post
		$stmt = $conn->prepare('DELETE FROM posts WHERE id = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		if($stmt->error !== "")
		{
			return false;
		}
		$stmt->close();

		return true;
	}

	//hmm, allow users to delete their own comments?
	function deleteComment($commentID)
	{
		global $conn;
		$stmt = $conn->prepare('DELETE FROM comments WHERE id = ?');
		$stmt->bind_param('i', $commentID);
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

?>