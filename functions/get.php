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

	// Returns the name of a forum given the forum ID
	function getForumName ($forumID)
	{
		global $conn;
		$stmt = $conn->prepare('SELECT name FROM froums WHERE id = ?');
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
		return 5;
	}

	// Returns number of posts in a forum given the forum ID
	function numberOfPosts ($fourmID)
	{
		return 6;
	}

	// Returns the number of unred messages given the user ID
	function numberOfUnreadMessages ($userID)
	{
		return 1;
	}

	// Returns the id of the category that a forum belongs to
	function forumBelongsTo ($forumID)
	{
		return 1;
	}
?>