<?php

	require_once("includes/init.php");	

	if( !$currentUser->isLoggedIn() && !usernameExists($_GET["user"]) ){

		// GO TO index.php
		header("Location: index.php");
		die();
	}
	
	if( !isset($_GET["user"]) && $currentUser->isLoggedIn() ) {
			
		header("Location: profile.php?user=" . $currentUser->username);
	}
	
	if( isset($_GET["user"]) ){
	
		if( !usernameExists($_GET["user"]) && $currentUser->isLoggedIn() ){
		
			header("Location: profile.php?user=" . $currentUser->username);
		}
	}

	// Need something like this so we don't fail later checks because of capital/normal letters.
	$user = getUsernameID(getUserID($_GET["user"]));
	$userID = getUserID($_GET["user"]);

	$comments = $conn->prepare('SELECT postID, text FROM comments WHERE userID = ? ORDER BY created_at DESC LIMIT 10');
	$comments->bind_param('i', $userID);
	$comments->execute();
	$comments->store_result();
	$comments->bind_result($commentsID, $commentsText);

	$posts = $conn->prepare('SELECT id, title FROM posts WHERE creator = ? LIMIT 5');
	$posts->bind_param('i', $userID);
	$posts->execute();
	$posts->store_result();
	$posts->bind_result($postsID, $postsTitle);
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
			<div class="row">
		 		<div class="col-lg-2">
					<!-- SIDEBAR USER TITLE -->
					<div class="profile-usertitle">
						<div class="profile-usertitle-name">
						
												<h1><?php echo $user; ?></h1>
					
						</div>
						<div class="profile-usertitle-userType">
												
						<?php  
												if(isAdminUsername($user) === true)
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
								<?php if( $currentUser->isLoggedIn() && $user !== $currentUser->username ){ 
						 
								?>
						
					<!-- SIDEBAR BUTTONS -->

					<div class="profile-userbuttons">
												<?php if( !areFriends($currentUser->id, getUserID($user)) ){ 
												?>
												
						 <a type="button" class="btn btn-success btn-sm" href="#">Add Friend</a>
												
												<?php }
														else { 
												?>
												
														<a type="button" class="btn btn-success btn-sm" href="#">Friends</a>
												
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
												<?php if( $currentUser->isLoggedIn() && $user === $currentUser->username ){ 
						 
												?>
							<li>
							 <a href="#">
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
									echo $commentsText;
									echo '</div>';
									echo '</a>';
								}
								
							}
							else
							{
								echo 'hej';
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
									echo $postsTitle;
									echo '</div>';
									echo '</a>';
								}
								
							}
							else
							{
								echo 'hej';
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