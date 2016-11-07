<?php
	require_once("../includes/init.php");

	function reportPost($postID, $message)
	{
		global $conn;
		global $currentUser;

		if(!$currentUser->isLoggedIn())
			return false;

		try {
			$post = new post($postID);
		} catch (Exception $e) {
			echo 0;
			return false;
		}

		$time = time();
		$stmt = $conn->prepare('INSERT INTO reportedPosts(reportedBy, postID, forum, message, reportedAt) VALUES (?,?,?,?,?)');
		$stmt->bind_param('iiisi', $currentUser->id, $postID, $post->forum, $message, $time);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}

	function reportComment($commentID, $message)
	{
		global $conn;
		global $currentUser;

		if(!$currentUser->isLoggedIn())
			return false;

		try {
			$comment = new comment($commentID);
		} catch (Exception $e) {
			echo 0;
			return false;
		}
		$post = new post($comment->post);
		$time = time();
		$stmt = $conn->prepare('INSERT INTO reportedComments(reportedBy, commentID, forum, message, reportedAt) VALUES (?,?,?,?,?)');
		$stmt->bind_param('iiisi', $currentUser->id, $commentID, $post->forum, $message, $time);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}