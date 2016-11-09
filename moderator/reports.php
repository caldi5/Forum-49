<?php	
	require_once("../includes/init.php");

	//Kill if users is not admin
	if(!isset($_GET['id']) || (!$currentUser->isAdmin() && !$currentUser->isModerator($_GET['id'])))
	{
		//header("Location: /index.php");
		die();
	}


	if(isset($_GET['removePostReport']))
		removePostReport($_GET['removePostReport']);
	if(isset($_GET['deletePost']))
		(new post($_GET['deletePost']))->delete();

	if(isset($_GET['removeCommentReport']))
		removeCommentReport($_GET['removeCommentReport']);
	if(isset($_GET['deleteComment']))
		(new comment($_GET['deleteComment']))->delete();

?>

<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Admin - Reports</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row">
			<div class="col-sm-6" style="padding:0;">
				<a href="moderate.php?id=<?php echo $_GET['id']?>" class="list-group-item">Temporary bans</a>
			</div>
			<div class="col-sm-6" style="padding:0;">
			<a href="#" class="list-group-item">Reports</a>
			</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Reports
					</div>
					<div class="panel-body">
<?php


	$forum = new forum($_GET['id']);
	$postReports = $forum->getReportedPosts();
	$commentsReports = $forum->getReportedComments();

	if(!empty($postReports) || !empty($commentsReports))
	{
		$category = new category($forum->category);
?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4><a data-toggle="collapse" href="#<?php echo $forum->id;?>">Forum:</a></h4>
								<h4 class="post-title">
									<a href="/category.php?id=<?php echo $category->id;?>"><?php echo $category->name;?></a> / <a href="/forum.php?id=<?php echo $forum->id;?>"><?php echo $forum->name;?></a>
								</h4>
							</div>
							<div id="<?php echo $forum->id;?>" class="panel-body">
<?php
			if(!empty($postReports))
			{
				echo '<h3>Posts:</h3>';

				foreach ($postReports as $postReport)
				{
					
					$reporter = new user($postReport->reportedBy);
					$post = new post($postReport->postID);
					$poster = new user($post->creator);
?>
								<div class="panel panel-default">
									<div class="panel-heading row">
										<div class="col-md-9">
											<h4 class="post-title">
												<a href="/post.php?id=<?php echo $post->id; ?>"><?php echo $post->title;?></a>
											</h4>
										</div>
										<div class="col-md-3">
											<a href="?removePostReport=<?php echo $postReport->id;?>" class="btn btn-sm btn-primary">Remove report</a>
											<a href="?deletePost=<?php echo $post->id;?>" class="btn btn-sm btn-danger">delete</a>
										</div>
									</div>
									<div class="panel-body">
										<div class="well well-sm row">
											<div class="col-md-3">
												Reported By: <?php echo $reporter->username;?>
											</div>
											<div class="col-md-9">
												Reason: <?php echo $postReport->message;?>
											</div>
										</div>
										<div class="row post-post">
											<div class="col-md-1 post-profile">
												<h4><a class="profile-name" href="profile.php?user=<?php echo $poster->username;?>"><?php echo $poster->username;?></a></h4>
											</div>
											<div class="col-md-8 post-text">
												<p><?php echo nl2br(htmlspecialchars($post->text));?></p>
											</div>
											<div class="col-md-3">
												<span class="post-time"><?php echo date('H:i d/m/y', 2345234987); ?></span>
											</div>
										</div>
									</div>
								</div>
<?php
				}
			}
?>
<?php
			if(!empty($commentsReports))
			{
				echo '<h3>Comments:</h3>';

				foreach ($commentsReports as $commentsReport)
				{
					
					$reporter = new user($commentsReport->reportedBy);
					$comment = new comment($commentsReport->commentID);
					$poster = new user($comment->creator);
					$post = new post($comment->post);
					
?>
								<div class="panel panel-default">
									<div class="panel-heading row">
										<div class="col-md-9">
											<h4 class="post-title">
												<a href="/post.php?id=<?php echo $post->id; ?>"><?php echo $post->title;?></a>
											</h4>
										</div>
										<div class="col-md-3">
											<a href="?id=<?php echo $_GET['id'];?>&removeCommentReport=<?php echo $commentsReport->id;?>" class="btn btn-sm btn-primary">Remove report</a>
											<a href="?id=<?php echo $_GET['id'];?>&deleteComment=<?php echo $comment->id;?>" class="btn btn-sm btn-danger">delete</a>
										</div>
									</div>
									<div class="panel-body">
										<div class="well well-sm row">
											<div class="col-md-3">
												Reported By: <?php echo $reporter->username;?>
											</div>
											<div class="col-md-9">
												Reason: <?php echo $commentsReport->message;?>
											</div>
										</div>
										<div class="row post-reply">
											<div class="col-md-1 post-profile">
												<h4><a class="profile-name" href="profile.php?user=<?php echo $poster->username;?>"><?php echo $poster->username;?></a></h4>
											</div>
											<div class="col-md-8 post-text">
												<p><?php echo nl2br(htmlspecialchars($comment->text));?></p>
											</div>
											<div class="col-md-3">
												<span class="post-time"><?php echo date('H:i d/m/y', 2345234987); ?></span>
											</div>
										</div>
									</div>
								</div>
<?php
				}
			}
?>
							</div>
						</div>
<?php
	}
	else
	{
		echo '<div class="alert alert-info">' . "\r\n";
		echo '<h4><strong>Awesome!</strong> There\'s no reports for the moment!</h4>' . "\r\n";
		echo '</div>' . "\r\n";
	}

?>
					</div>
				</div>
				<!-- Users End -->
			</div>
			<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
	</body>
</html>