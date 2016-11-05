<?php
	require_once __DIR__.'/../includes/dbconn.php';

	// Returns the name of a category given the category ID
	function getCategoryName ($categoryID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT name FROM categories WHERE id = ?');
		$stmt->bind_param('i', $categoryID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name);

		if ($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $name;
	}

	//returns an array with all categories names
	function getAllCategoryIDs()
	{
		global $conn;
		$stmt = $conn->prepare('SELECT id FROM categories');
		$stmt->execute();
		$stmt->bind_result($id);
		while ($stmt->fetch()) 
		{
			$categoryIDs[] = $id;
		}
		$stmt->close();

		return $categoryIDs;
	}

	// Returns the name of a forum given the forum ID
	function getForumName ($forumID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT name FROM forums WHERE id = ?');
		$stmt->bind_param('i', $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name);

		if ($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $name;
	}

	// Returns number of replies a post has gotten given the post ID
	function numberOfReplies ($postID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT COUNT(id) as count FROM comments WHERE postID = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);

		// If this was not here, the function would return false and not 0 when there's no comments on a post.
		if ($stmt->num_rows == 0)
			return 0;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		return $count;
	}

	// Returns number of subforums to a specific category given the categoryID
	function getNumberOfForums ($categoryID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT COUNT(*) FROM forums WHERE category = ?');
		$stmt->bind_param('i', $categoryID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);

		if ($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $count;
	}

	// Returns number of posts in a forum given the forum ID
	function numberOfPosts ($forumID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT COUNT(*) FROM posts WHERE forum = ?');
		$stmt->bind_param('i', $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);

		if ($stmt->num_rows == 0)
			return false;

		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $count;
	}

	// Returns the id of the category that a forum belongs to
	function forumBelongsTo ($forumID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT category FROM forums WHERE id = ?');
		$stmt->bind_param('i', $forumID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $id;
	}

	function postBelongsTo ($postID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT forum FROM posts WHERE id = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($forum);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $forum;
	}

	function getPostTitle ($postID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT title FROM posts WHERE id = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($title);
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();

		return $title;
	}
?>