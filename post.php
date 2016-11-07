<?php

	require_once("includes/init.php");

	if(isset($_POST['comment']))
		if(!$currentUser->newComment($_GET['id'], $_POST['comment']))
			$alerts[] = new alert("danger", "Error:", "Your comment was not sent");

	try 
	{
		$post = new post($_GET['id']);
	}
	catch (Exception $e) 
	{
		header('Location: index.php');
		die;
	}
	$post->view();

	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	$comments_per_page = 9;
	$commentsOffset = ($comments_per_page*$page)-$comments_per_page;
	$count = $post->getNumberOfComments();
	$comments = $post->getComments($comments_per_page, $commentsOffset);

	$user = new user($post->creator);
	$forum = new forum($post->forum);
	$category = new category($forum->category);

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
<?php displayAlerts(); ?>
			<h3 class="post-title">
<?php
	echo '<a href="category.php?id='.$category->id.'">'.htmlspecialchars($category->name).'</a> / ';
	echo '<a href="forum.php?id='.$forum->id.'">'.htmlspecialchars($forum->name).'</a> / ';
	echo $post->title; 
?>
			</h3>
			<div class="row post-post">
				<div class="col-lg-2 post-profile">
					<h4><?php echo '<a class="profile-name" href="profile.php?user='.$user->username.'">'.$user->username.'</a>'; ?></h4>
				</div>
				<div class="col-lg-8 post-text">
					<p><?php echo nl2br(htmlspecialchars($post->text)); ?></p>
				</div>
				<div class="col-lg-2">
					<span class="post-time"><?php echo date('H:i d/m/y', $post->createdAt); ?></span>
					<br>
					<?php if($currentUser->isLoggedIn()){ echo '<a href="#">Report</a> | ';}?>
					<?php if($currentUser->id === $user->id || $currentUser->isadmin()){ echo '<a href="#">Delete</a>';}?>
				</div>
			</div>
<?php
	if(isset($comments))
	{
		foreach ($comments as $comment)
		{
			$user = new user($comment->creator);
			echo '<div id="commentid'. $comment->id.'" class="row post-reply">';
			echo '<div class="col-lg-2 post-profile">';
			echo '<h4><a class="profile-name" href="profile.php?user='.$user->username.'">'.$user->username.'</a></h4>';

			echo '</div>';
			echo '<div class="col-lg-8 post-text">';
			echo '<p>'.nl2br(htmlspecialchars($comment->text)).'</p>';
			echo '</div>';
			echo '<div class="col-lg-2">';
			echo '<span class="post-time">'.date('H:i d/m/y', $comment->createdAt).'</span><br>';
			if($currentUser->isLoggedIn()){ echo '<a href="#">Report</a> | ';}
			if($currentUser->id === $user->id || $currentUser->isadmin()){ echo '<a href="#" onclick="javascript:deleteComment('. $comment->id .');">Delete</a>';}
			echo '</div>';
			echo '</div>';
		}
	}

	if ($count > $comments_per_page)
	{
		echo '<nav aria-label="Page navigation">';
		echo '<div class="row">';
		echo '<ul class="pagination">';
		if ($page == 1)
		{
			echo '<li class="page-item disabled">';
			echo '<a class="page-link" href="#" aria-label="Previous">';
		}
		else 
		{
			echo '<li class="page-item">';
			echo '<a class="page-link" href="post.php?id='.$_GET['id'].'&page='.($page-1).'" aria-label="Previous">';
		}
		echo '<span aria-hidden="true">&laquo;</span>';
		echo '</a></li>';

		// Number of pages we need, rounded up.
		$pages = ceil($count / $comments_per_page);

		for ($i = 1; $i <= $pages; $i++)
		{
			// Makes the current page active.
			if ($i == $page)
				echo '<li class="page-item active"><a class="page-link" href="post.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
			else
				echo '<li class="page-item"><a class="page-link" href="post.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
		}
		if ($page == $pages)
		{
			echo '<li class="page-item disabled">';
			echo '<a class="page-link" href="#" aria-label="Next">';
		}
		else 
		{
			echo '<li class="page-item">';
			echo '<a class="page-link" href="post.php?id='.$_GET['id'].'&page='.($page+1).'" aria-label="Next">';
		}		
		echo '<span aria-hidden="true">&raquo;</span>';
		echo '</a></li>';
		echo '</ul>';
		echo '</div>';
		echo '</nav>';
	}
?>


<?php
	if ($currentUser->isLoggedIn())
	{
?>
				<h3>Reply</h3>
				<form action="post.php?id=<?php echo $_GET['id'] .'&page='. $page; ?>" method="post">
						<div class="form-group">
							<textarea name="comment" rows="5" class="form-control" required></textarea>
						</div>
						<button class="btn btn-lg btn-success btn-block" type="submit">Submit</button>
				</form>
<?php
	}
?>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	<script>
		function deleteComment(commentID)
		{
			$.ajax(
				{
					url: "/ajax/deleteComment.php?id=" + commentID,
					success : function(response)
					{
						if(response)
						{
							$("#commentid" + commentID).remove();
						}
					}
				})
		}
	</script>
	</body>
</html>