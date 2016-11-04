<?php

	require_once("includes/init.php");


	if (isset($_POST['newPost']) && (isset($_GET['forum']) || isset($_POST['id'])) && $currentUser->isLoggedIn())
	{
		// Insert new post
		if (isset($_POST['id']))
		{
			$forumID = $_POST['id'];
		}
		elseif (isset($_GET['forum']))
		{
			$forumID = $_GET['forum'];
		}
		else
		{
			header('Location: index.php');
			die();
		}

		if (getForumName($forumID) == false)
		{
			header('Location: index.php');
			die();
		}

		if (!isset($_POST['title']) || !isset($_POST['title']))
		{
			header('Location: index.php');
			die();
		}
		else
		{
			$title = $_POST['title'];
			$text = $_POST['post'];

			if (strlen($title) < 2 || strlen($text) < 2 || strlen($text) > 5000 || strlen($title) > 40)
			{
				header('Location: index.php');
				die();
			}
		}


		$stmt = $conn->prepare("INSERT INTO posts(creator, title, text, forum, created_at) VALUES (?,?,?,?,?)");
		$stmt->bind_param('issii', $_SESSION['id'], $title, $text, $forumID, time());
		$stmt->execute();

		if (empty($stmt->error))
		{
			$stmt->close();
			header('Location: forum.php?id='.$forumID.'');
		}
		else
		{
			$stmt->close();
			echo 'Something something SQL error.';
		}

	}
	elseif(isset($_GET['forum']) && $currentUser->isLoggedIn())
	{
		// Write new post

		if ($forumName = getForumName($_GET['forum']))
		{
			$forumID = $_GET['forum'];
		}
		else
		{
			// Forum was not found
			header('Location: index.php');
			die();
		}
	}
	else
	{
		header('Location: index.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("includes/standard_head.php"); ?>
	<title>New Post</title>
</head>
<body>
	<?php include("includes/navbar.php"); ?>

	<div class="container">
		<h2>You are posting in <span class="red-text"><?php echo $forumName; ?></span></h2>
		<br>
		<div class="row post-reply-form">
			<form action="newPost.php" method="post">
				<input type="text" maxlength="40" name="title" placeholder="Title">
				<br>
				<br>
				<textarea name="post" maxlength="5000" placeholder="Write your post here" required></textarea>
				<br>
				<input type="hidden" name="id" value="<?php echo $forumID; ?>">
				<input type="submit" name="newPost">
			</form>
		</div>
	</div>

</body>
</html>