<?php

	require_once("includes/init.php");	

	if((!$currentUser->isLoggedIn() && !isset($_GET["user"])) || (isset($_GET["user"]) && !user::usernameExists($_GET["user"])))
	{
		// GO TO index.php
		header("Location: index.php");
		die();
	}

	if(isset($_GET["user"]))
		$user = new user(user::getUserID($_GET["user"]));
	else
		$user = new user($currentUser->id);

	if(isset($_GET["sendReq"])){
		$currentUser->sendFriendRequest($user->id);
	}
	if(isset($_GET["removeFriend"])){
		$currentUser->unFriend($user->id);
	}

	$comments = $conn->prepare('SELECT postID, text, created_at FROM comments WHERE userID = ? ORDER BY created_at DESC LIMIT 10');
	$comments->bind_param('i', $user->id);
	$comments->execute();
	$comments->store_result();
	$comments->bind_result($commentsID, $commentsText, $commentsTime);

	$posts = $conn->prepare('SELECT id, title, created_at FROM posts WHERE creator = ? ORDER BY created_at DESC LIMIT 10');
	$posts->bind_param('i', $user->id);
	$posts->execute();
	$posts->store_result();
	$posts->bind_result($postsID, $postsTitle, $postsTime);

?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once("includes/standard_head.php"); ?>
		<title>Profile</title>
	</head>

	<body>
<?php require_once("includes/navbar.php"); ?>		
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row">
		 		<div class="col-lg-2">
					<!-- SIDEBAR USER TITLE -->
					<div class="profile-usertitle">
						<div class="profile-usertitle-name">
						
												<h1><?php echo $user->username; ?></h1>
					
						</div>
						<div class="profile-usertitle-userType">
												
						<?php  
												if($user->isAdmin())
												{
														echo "Administrator";
												}
												else
												{
														echo "User";
												}
										?>
												
						</div>
					</div>
					<!-- END SIDEBAR USER TITLE -->
						 
								<!-- If logged in and not going to private page: load this  (IN FUTURE check if already a friend?) -->
								<?php if( $currentUser->isLoggedIn() && $user->username !== $currentUser->username ){ 
						 
								?>
						
					<!-- SIDEBAR BUTTONS -->
					<div class="profile-userbuttons">
											<?php if($currentUser->friendRequestExists($user->id)){ 
											?>
												
											 	<a type="button" class="btn btn-success btn-sm" href="#">Req Sent</a>
												
												<?php }
														elseif($currentUser->areFriendsWith($user->id)) { 
												?>
					<div class="btn-group">
												<a type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Friends
												<span class="caret"></span>
    										<span class="sr-only">Toggle Dropdown</span>
												</a>
												
												<ul class="dropdown-menu">
												<li><a href="profile.php?user=<?php echo $user->username;?>&removeFriend">Remove Friend</a></li>
												</ul>
					</div>
												<?php }
														else {
												?>
												
														<a type="button" class="btn btn-success btn-sm" href="profile.php?user=<?php echo $user->username;?>&sendReq">Add Friend</a>
											<?php }
											?>
												
					 <a type="button" class="btn btn-danger btn-sm" href="/messages.php">Message</a>
					</div>
					<!-- END SIDEBAR BUTTONS -->    
						 
								<?php }
								
								?>
								<!-- If logged in and not going to private page: END -->
						 
					<!-- SIDEBAR MENU -->
					<div class="profile-usermenu">
						<ul class="nav">
							<li>
								<a href="#">
								<i class="glyphicon glyphicon-home"></i>
								Overview </a>
							</li>
												

												<!-- If logged in and going to the private page: load this-->
												<?php if( $currentUser->isLoggedIn() && $user->username === $currentUser->username ){ 
						 
												?>
							<li>
							 <a href="/friends.php">
							 <i class="glyphicon glyphicon-user"></i>
							 Friends </a>
							</li>
														
												<?php } 
						
												?>
												<!-- If logged in and going to the private page: END-->
												 
						</ul>
					</div>
					<!-- END MENU -->
				</div>
		
				<!-- OVERVIEW CONTENT -->
				<div class="col-lg-10">
					<div class="col-lg-5 profile-comments">
						<h3>Latest Comments</h3>
						<?php
							if ($comments->num_rows > 0)
							{
								while ($comments->fetch())
								{
									echo '<a href="post.php?id='.$commentsID.'">';
									echo '<div class="col-lg-12 profile-comment">';
									if (strlen($commentsText) > 50)
										echo htmlspecialchars(substr($commentsText, 0, 45)).'...';
									else
										echo htmlspecialchars($commentsText);
									echo '<br><span class="post-time">'.date('H:i d/m/y', $commentsTime).'</span>';
									echo '</div>';
									echo '</a>';
								}
								
							}
							else
							{
								echo '<div class="col-lg-12 profile-comment">';
								echo '<p>User has no comments yet.</p>';
								echo '</div>';
							}
							$comments->free_result();
							$comments->close();
						?>
					</div>

					<div class="col-lg-5 profile-posts">
						<h3>Latest Posts</h3>
						<?php
							if ($posts->num_rows > 0)
							{
								while ($posts->fetch())
								{
									echo '<a href="post.php?id='.$postsID.'">';
									echo '<div class="col-lg-12 profile-comment">';
									if (strlen($postsTitle) > 50)
										echo htmlspecialchars(substr($postsTitle, 0, 45)).'...';
									else
										echo htmlspecialchars($postsTitle);
									echo '<br><span class="post-time">'.date('H:i d/m/y', $postsTime).'</span>';
									echo '</div>';
									echo '</a>';
								}
								
							}
							else
							{
								echo '<div class="col-lg-12 profile-comment">';
								echo '<p>User has no posts yet.</p>';
								echo '</div>';
							}
							$posts->free_result();
							$posts->close();
						?>
					</div>
				</div>
				<!-- OVERVIEW CONTENT END -->
			</div>
		</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>