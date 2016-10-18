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
                <!-- SIDEBAR USERPIC -->
				<div class="profile-userpic">
					<img src="img/testprofilepic.jpg" class="img-responsive" alt="">
				</div>
				<!-- END SIDEBAR USERPIC -->
				<!-- SIDEBAR USER TITLE -->
				<div class="profile-usertitle">
					<div class="profile-usertitle-name">
						<b><?php echo getUsername(); ?></b>
					</div>
					<div class="profile-usertitle-userType">
				    <?php  
                        $username = getUsername();
                        if(isAdminUsername($username) === true)
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
				<!-- SIDEBAR BUTTONS -->
				<div class="profile-userbuttons">
					<a type="button" class="btn btn-success btn-sm" href="#">Add Friend</a>
					<a type="button" class="btn btn-danger btn-sm" href="/messages.php">Message</a>
				</div>
				<!-- END SIDEBAR BUTTONS -->
				<!-- SIDEBAR MENU -->
				<div class="profile-usermenu">
					<ul class="nav">
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-home"></i>
							Overview </a>
						</li>
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-user"></i>
							 Friends </a>
						</li>
						<li>
							<a href="/profilesettings.php">
							<i class="glyphicon glyphicon-cog"></i>
							Account Settings </a>
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
	   
        <!-- OVERVIEW CONTENT -->
		<div id="profile-content-div" class="col-md-9">
            <div class="profile-content-header">
			  <h1><?php echo getUsername(); ?></h1>
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