<?php
	session_start();
	require_once 'functions/get.php';
	require_once 'functions/user.php';

	if (isset($_GET['new']) && isset($_GET['forum']))
	{
		if ($forumName = getForumName($_GET['forum']))
		{

		}
		else
		{
			header('Location: index.php');
		}
	}
	else
	{
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title>Forum</title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>		
		<!-- Content start -->
		<div class="container">
			<h2 class="post-title">Post title</h2>
			<div class="row post-post">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdf</p>
				</div>
			</div>
			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf</p>
				</div>
			</div>
			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdf</p>
				</div>
			</div>
			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdf</p>
				</div>
			</div>
			<div class="row">
				<ul class="pagination">
				  <li class="active"><a href="#">1</a></li>
				  <li><a href="#">2</a></li>
				  <li><a href="#">3</a></li>
				  <li><a href="#">4</a></li>
				  <li><a href="#">5</a></li>
				</ul>
			</div>
			<div class="row post-reply-form">
				<h3>Reply</h3>
				<form action="post.php" method="post">
					<textarea name="comment" maxlength="1500" required></textarea>
					<br>
					<input type="submit">
				</form>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>