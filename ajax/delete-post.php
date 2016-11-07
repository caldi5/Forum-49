	<?php

	require_once("../includes/init.php");

	if(!isset($_GET['id']))
		die();

	try
	{
		$post = new post($_GET['id']);
	}
	catch(Exception $e)
	{
		echo "false";
		die();
	}

	if($currentUser->id === $post->creator || $currentUser->isAdmin())
	{
		//Urgh, implement post->delete you lazy ass...
		echo deletePost($post->id);
	}
	else
	{
		echo "false";
		die();
	}

	//Urgh, implement post->delete you lazy ass...
	function deletePost($postID)
	{
		global $conn;

		//Delete all comments beloning to $postID
		$stmt = $conn->prepare('DELETE FROM comments WHERE postID = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			return false;
		}
		$stmt->close();

		//Delete the post
		$stmt = $conn->prepare('DELETE FROM posts WHERE id = ?');
		$stmt->bind_param('i', $postID);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			return false;
		}
		$stmt->close();

		return true;
	}

?>