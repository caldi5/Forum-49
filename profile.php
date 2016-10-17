<?php
	session_start();
	require_once("includes/dbconn.php"); 
    require_once("functions/get.php");
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
				<div class="profile-userpic">
					<img src="img/testprofilepic.jpg" class="img-responsive" alt="">
				</div>
				<!-- END SIDEBAR USERPIC -->
				<!-- SIDEBAR USER TITLE -->
				<div class="profile-usertitle">
					<div class="profile-usertitle-name">
						William Achrenius
					</div>
					<div class="profile-usertitle-userType">
						Administrator
					</div>
				</div>
				<!-- END SIDEBAR USER TITLE -->
				<!-- SIDEBAR BUTTONS -->
				<div class="profile-userbuttons">
					<button type="button" class="btn btn-success btn-sm">Add Friend</button>
					<button type="button" class="btn btn-danger btn-sm">Message</button>
				</div>
				<!-- END SIDEBAR BUTTONS -->
				<!-- SIDEBAR MENU -->
				<div class="profile-usermenu">
					<ul class="nav">
						<li class="active">
							<a href="#">
							<i class="glyphicon glyphicon-home"></i>
							Overview </a>
						</li>
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-user"></i>
							Account Settings </a>
						</li>
						<li>
							<a href="#" target="_blank">
							<i class="glyphicon glyphicon-ok"></i>
							Tasks </a>
						</li>
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-flag"></i>
							Help </a>
						</li>
					</ul>
				</div>
				<!-- END MENU -->
			</div>
	
		<div class="col-md-9">
            <div class="profile-content">
			   Some user related content goes here...
                
               
            </div>
		</div>
	</div>
</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>