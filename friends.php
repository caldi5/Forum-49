<?php

	require_once("includes/init.php");	

	// If currentUser is NOT logged in. Return to index.php
	if(!$currentUser->isLoggedIn())
	{
		// GO TO index.php
		header("Location: index.php");
		die();
	}

    // Call currentUser function and save result to $friends variable.
	$friends = $currentUser->getFriends();
?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once("includes/standard_head.php"); ?>
		<title>Friends</title>
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
						
						<!-- Display currentUser->username -->
						<div class="profile-usertitle-name">
												<h1><?php echo $currentUser->username; ?></h1>
						</div>
						<div class="profile-usertitle-userType">
												
							<!-- Check if currenUser is Admin to display right usertype (Normal user or admin)-->
						<?php  
							if($currentUser->isAdmin())
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
						  
					<!-- SIDEBAR MENU -->
					<div class="profile-usermenu">
						<ul class="nav">
							<li>
								<a href="/profile.php">
								<i class="glyphicon glyphicon-home"></i>
								Overview </a>
							</li>
							<li>
							 <a href="#">
							 <i class="glyphicon glyphicon-user"></i>
							 Friends </a>
							</li>					 
						</ul>
					</div>
					<!-- END MENU -->
				</div>
		
				<!-- FRIENDS LOAD -->
				<div class="col-lg-10">
					<div class="col-lg-5 profile-comments">
						<h3>Friends</h3>
						<?php
							// If $friends variable is empty the user has no friends
							if (empty($friends))
							{
								echo '<div class="col-lg-12 profile-comment">';
								echo '<p>You have no friends... :(</p>';
								echo '</div>';
							}
							else
							{	// echo html to show each friend and create link to friends profile page
                                foreach ($friends as $friends)
								{
									// create link to friends profile page
									echo '<a href="profile.php?user='.htmlspecialchars($friends->username).'">';
									echo '<div class="col-lg-12 profile-comment">';
																		
									// echo friends username
                                    echo htmlspecialchars($friends->username);
									
									// Show friends since
                                    echo '<br><span class="post-time"> Friends Since: '.date('H:i d/m/y', $currentUser->friendsSince($friends->id)).'</span>';
																		
									echo '</div>';
									echo '</a>';
								}
							}
						?>
					</div>
					<div class="col-lg-5 profile-posts">
							<h3>Add Friend</h3>
							<div class="col-lg-12 profile-comment">
								<div class="input-group">
      								<input id="username" type="text" class="form-control" placeholder="Search User...">
      									<span class="input-group-btn">
											<button class="btn btn-primary" type="button" onclick="addUser()">Search</button>
										</span>
    							</div><!-- /input-group -->
							</div>
							<div id="alertdiv" class="col-lg-12"></div>
						</div>
				</div>
				<!-- FRIENDS LOAD END -->
			</div>
		</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
		<script>
		
			function addUser(){
				var username = $("#username").val();
				$.ajax({
					url: 'ajax/sendFriendRequest.php',
					type: 'post',
					data: {username: username},
					success: function(output){
						$("#alertdiv").html(output);
					}
				});
			}
			
			
		</script>
	</body>
</html>