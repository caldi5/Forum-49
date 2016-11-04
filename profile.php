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
		 		<div class="col-md-3">
					<!-- SIDEBAR USERPIC -->
					<div class="profile-userpic">
						<img src="img/testprofilepic.jpg" class="img-responsive" alt="">
					</div>
					<!-- END SIDEBAR USERPIC -->
					<!-- SIDEBAR USER TITLE -->
					<div class="profile-usertitle">
						<div class="profile-usertitle-name">
						
												<b><?php echo $user; ?></b>
					
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
												
						 <a type="button" class="btn btn-success btn-sm" id="friendButton" href="#">Add Friend</a>
											
												<?php }
														elseif (requestExists($currentUser->id, $_GET["user"])) { 
												?>
												
														<a type="button" class="btn btn-success btn-sm" href="#">Request Sent</a>
						
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
				<div id="profile-content-div" class="col-md-9">
					<div class="profile-content-header">
								
						<h2 id="overview-header-text" align="center">Overview</h2>
								
					</div>
					<div class="profile-content">
				 		Some user related/created content goes here... 
								
					</div>
				</div>
				<!-- OVERVIEW CONTENT END -->
			</div>
		</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>