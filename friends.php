<?php

	require_once("includes/init.php");	

	if(!$currentUser->isLoggedIn())
	{
		// GO TO index.php
		header("Location: index.php");
		die();
	}

    // SELECT alla user ID som är vänner med varandra. Ta sedan ut alla IDs som != currentUser
	$friends = $conn->prepare('
        SELECT * FROM
        (
	       SELECT created_at, 
	           CASE 
                WHEN userid = ? THEN userid2 
                WHEN userid2 = ? THEN userid 
                ELSE null
	           END as userID
	       FROM friends as t1
        ) as friends
        WHERE userID IS NOT NULL');
  $friends->bind_param('ii', $currentUser->id, $currentUser->id);
	$friends->execute();
	$friends->store_result();
	$friends->bind_result($friendsTime, $friendID);
	echo $currentUser->getUsernameID($friendID);
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
							if ($friends->num_rows > 0)
							{
								while ($friends->fetch())
								{
									echo '<a href="profile.php?user='.$currentUser->getUsernameID($friendID).'">';
									echo '<div class="col-lg-12 profile-comment">';
									
                  echo htmlspecialchars($currentUser->getUsernameID($friendID));
									
                                    echo '<br><span class="post-time"> Friends Since: '.date('H:i d/m/y', $friendsTime).'</span>';
									echo '</div>';
									echo '</a>';
								}
								
							}
							else
							{
								echo '<div class="col-lg-12 profile-comment">';
								echo '<p>You have no friends... :(</p>';
								echo '</div>';
							}
							$friends->free_result();
							$friends->close();
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