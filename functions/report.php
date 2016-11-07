<?php
	require_once("../includes/init.php");

	function reportPost($postID, $message)
	{
		global $conn;
		global $currentUser;

		if(!$currentUser->isLoggedIn())
			return false;

		try {
			new post($postID);
		} catch (Exception $e) {
			echo 0;
			return false;
		}

		$time = time();
		$stmt = $conn->prepare('INSERT INTO reportedPosts(reportedBy, postID, message, reportedAt) VALUES (?,?,?,?)');
		$stmt->bind_param('iisi', $currentUser->id, $postID, $message, $time);
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
			new post($postID);
		} catch (Exception $e) {
			echo 0;
			return false;
		}

		$time = time();
		$stmt = $conn->prepare('INSERT INTO reportedComments(reportedBy, cp,,emtID, message, reportedAt) VALUES (?,?,?,?)');
		$stmt->bind_param('iisi', $currentUser->id, $commentID, $message, $time);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}