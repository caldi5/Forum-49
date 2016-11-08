<?php
	require_once  __DIR__.'/../includes/init.php';

	class report
	{
		public $id;
		public $reportedBy;
		public $forum;
		public $message;
		public $reportedAt;

		function __construct($id, $reportedBy, $forum, $message, $reportedAt)
		{
			$this->id = $id;
			$this->reportedBy = $reportedBy;
			$this->forum = $forum;
			$this->message = $message;
			$this->reportedAt = $reportedAt;
		}
	}

	class postReport extends report
	{
		public $postID;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, postID, reportedBy, forum, message, reportedAt FROM reportedPosts WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');		
			$stmt->bind_result($id, $postID, $reportedBy, $forum, $message, $reportedAt);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->postID = $postID;
			parent::__construct($id, $reportedBy, $forum, $message, $reportedAt);
		}
	}

	class commentReport extends report
	{
		public $commentID;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, commentID, reportedBy, forum, message, reportedAt FROM reportedComments WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');		
			$stmt->bind_result($id, $commentID, $reportedBy, $forum, $message, $reportedAt);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->commentID = $commentID;
			parent::__construct($id, $reportedBy, $forum, $message, $reportedAt);
		}
	}

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

		if(empty($message))
			$message = "User didn't specify a message";

		$time = time();
		$stmt = $conn->prepare('INSERT INTO reportedPosts(reportedBy, postID, forum, message, reportedAt) VALUES (?,?,?,?,?)');
		$stmt->bind_param('iiisi', $currentUser->id, $postID, $post->forum, $message, $time);
		$stmt->execute();
		if(!empty($stmt->error))
			return false;
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
			return false;
		$stmt->close();
		return true;
	}

	function removePostReport($id)
	{
		global $conn;
		$stmt = $conn->prepare('DELETE FROM reportedPosts WHERE id=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		if(!empty($stmt->error))
			return false;
		$stmt->close();
		return true;
	}

	function removeCommentReport($id)
	{
		global $conn;
		$stmt = $conn->prepare('DELETE FROM reportedComments WHERE id=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		if(!empty($stmt->error))
			return false;
		$stmt->close();
		return true;
	}