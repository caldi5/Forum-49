<?php

	require_once("includes/init.php");	

	if(!$currentUser->isLoggedIn())
	{
		// GO TO index.php
		header("Location: index.php");
		die();
	}

    // SELECT alla user ID som är vänner med varandra. Ta sedan ut alla IDs som != currentUser
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
						<div class="profile-usertitle-name">
						
												<h1><?php echo $currentUser->username; ?></h1>
					
						</div>
						<div class="profile-usertitle-userType">
												
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
		
				<!-- OVERVIEW CONTENT -->
				<div class="col-lg-10">
					<div class="col-lg-5 profile-comments">
						<h3>Friends</h3>
						<?php
							if (empty($friends))
							{
								echo '<div class="col-lg-12 profile-comment">';
								echo '<p>You have no friends... :(</p>';
								echo '</div>';
							}
							else
							{
                                foreach ($friends as $friends)
								{
									echo '<a href="profile.php?user='.$friends->username.'">';
									echo '<div class="col-lg-12 profile-comment">';
									
                                    echo htmlspecialchars($friends->username);
									
                                    echo '<br><span class="post-time"> Friends Since: '.date('H:i d/m/y', $currentUser->friendsSince($friends->id)).'</span>';
									echo '</div>';
									echo '</a>';
								}
							}
						?>
					</div>

					<div class="col-lg-5 profile-posts">
                        
						<!-- Lägg till vänner här också -->

					</div>
				</div>
				<!-- OVERVIEW CONTENT END -->
			</div>
		</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>