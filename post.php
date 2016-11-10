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
					<?php if($currentUser->isLoggedIn()){ echo '<a href="#" data-toggle="modal" data-target="#confirm-report" data-id="'. $post->id .'" data-onClick="javascript:reportPost()">Report</a> | ';}?>
					<?php if($currentUser->id === $user->id || $currentUser->isadmin() || $currentUser->isModerator($forum->id)){ echo '<a href="#" data-toggle="modal" data-target="#confirm-delete" data-onclick="javascript:deletePost('. $post->id .','. $forum->id .');">Delete</a>';}?>
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
			if($currentUser->isLoggedIn()){ echo '<a href="#" data-toggle="modal" data-target="#confirm-report" data-id="'. $comment->id .'" data-onClick="javascript:reportComment()">Report</a> | ';}
			if($currentUser->id === $user->id || $currentUser->isadmin() || $currentUser->isModerator($forum->id)){ echo '<a href="#" data-toggle="modal" data-target="#confirm-delete" data-onclick="javascript:deleteComment('. $comment->id .');">Delete</a>';}
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
			<div class="col-md-8 col-md-offset-2">
				<h3>Reply</h3>
				<form action="post.php?id=<?php echo $_GET['id'] .'&page='. $page; ?>" method="post">
						<div class="form-group">
							<textarea name="comment" rows="5" class="form-control" required></textarea>
						</div>
						<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
				</form>
			</div>
<?php
	}
?>
		</div>
		<!-- Content end -->
		<!-- Modal confirmation Start -->
		<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3>Warning!<h3>
					</div>
					<div class="modal-body">
						You're about to delete a comment OR post this can not be undone.
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a class="btn btn-danger btn-ok" data-dismiss="modal">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal confirmation End -->
		<!-- Modal confirmation Start -->
		<div class="modal fade" id="confirm-report" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3>Report!<h3>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Reason for reporting:</label>
							<textarea id="reason" class="form-control" maxlength="5000" placeholder="Write the reasoson for this report" required></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a class="btn btn-primary btn-ok" data-dismiss="modal">Report</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal confirmation End -->
<?php include("includes/standard_footer.php"); ?>
		<script>
			//Körs när modal'en visas
			$('#confirm-delete').on('show.bs.modal', function(e) 
			{
				//sätt mordalens (btn-ok) report knapps onClick till det som fans i den som kallede på funktions data-onClick
				$(this).find('.btn-ok').attr('onclick', $(e.relatedTarget).data('onclick'));
			});
			//Körs när modal'en visas
			$('#confirm-report').on('show.bs.modal', function(e) 
			{
				$('textarea#reason').val(''); //Clear any text that might have been in the textarea
				$(this).find('.btn-ok').attr('onclick', $(e.relatedTarget).data('onclick'));
				$(this).find('.btn-ok').attr('id', $(e.relatedTarget).data('id')); //Samma här fast med id't, antingen comment ID eller post ID
			});

			function reportPost()
			{
				var reason = $('textarea#reason').val();
				var id = $('#confirm-report').find('.btn-ok').attr('id');

				$.ajax(
					{
						url: "/ajax/report-post.php?id=" + id + "&message=" + reason
					})
			}
			
			function reportComment()
			{
				var reason = $('textarea#reason').val();
				var id = $('#confirm-report').find('.btn-ok').attr('id');

				$.ajax(
					{
						url: "/ajax/report-comment.php?id=" + id + "&message=" + reason
					})
			}

			function deleteComment(commentID)
			{
				$.ajax(
					{
						url: "/ajax/delete-comment.php?id=" + commentID,
						success : function(response)
						{
							if(response)
							{
								//remove the comment from the list without reloading the page
								$("#commentid" + commentID).remove();
							}
						}
					})
			}

			function deletePost(postID, forumID)
			{
				$.ajax(
					{
						url: "/ajax/delete-post.php?id=" + postID,
						success : function(response)
						{
							if(response)
							{
								//Redirect til forum since post does not exist anymore
								window.location.href = "/forum.php?id=" + forumID;
							}
						}
					})
			}

		</script>
	</body>
</html>
