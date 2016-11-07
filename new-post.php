<?php

	require_once("includes/init.php");

	try {
		$forum = new forum($_GET['forum']);
	} catch (Exception $e) {
		header('Location: index.php');
		die();
	}


	if(isset($_POST['title']) && isset($_POST['text']) && $currentUser->isLoggedIn())
	{
		if($currentUser->newPost($forum->id, $_POST['title'], $_POST['text']))
		{
			header('Location: forum.php?id='.$forum->id);
			die();
		}
		else
		{
			$alerts[] = new alert("danger", "Error:", "You post was not sent!");
		}
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
<?php displayAlerts(); ?>
		<div class="col-md-8 col-md-offset-2">
			<h2>You are posting in <span class="red-text"><?php echo $forum->name; ?></span></h2>
			<br>
			<form action="new-post.php<?php echo '?forum='. $forum->id;?>" method="post">
				<div class="form-group">
					<label>Title:</label>
					<input type="text" class="form-control" maxlength="40" name="title" placeholder="Title">
				</div>
				<div class="form-group">
					<label>Text:</label>
					<textarea name="text" class="form-control" maxlength="5000" placeholder="Write your post here" required></textarea>
				</div>
				<button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
			</form>
		</div>
	</div>
</body>
</html>