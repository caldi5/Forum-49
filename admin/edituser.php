<?php	

	require_once("../includes/init.php");

	//Kill if users is not admin
	if(!$currentUser->isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//Rediret to users if no ID is set or if no user by that ID exists
	if(!isset($_GET['id']) OR !userIDExists($_GET['id']))
	{
		header("Location: users.php");
		die();
	}

	$user = new user($_GET['id']);


?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Admin - Edit User</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container-fluid"">
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
				<div class="col-sm-10">
<?php if(isset($error)){ displayAlerts($error); } ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							Edit User
						</div>
						<div class="panel-body">
							<form id="editUserForm" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label>Username:</label>
									<input type="text" maxlength="50" class="form-control" name="username" value="<?php echo $user->username; ?>" required>
								</div>
								<div class="form-group">				
									<label>Email Address:</label>
									<div class="input-group">
										<span class="input-group-addon"><?php if($user->validEmail){echo "Vaild";} else{ echo "Not Vaild";} ?></span>
										<input type="email" maxlength="50" class="form-control" name="email" value="<?php echo $user->email; ?>" required>
									</div>
								</div>
								<div class="form-group">	
									<label>User Role</label>
									<select class="form-control" name="role">
										<option<?php if($user->role === "admin"){echo " selected";}	?> value="admin">Administrator</option>
										<option<?php if($user->role === "moderator"){echo " selected";}	?> value="moderator">Moderator</option>
										<option<?php if($user->role === "user"){echo " selected";}	?>  value="moderator">User</option>
									</select>
								</div>
								<div class="form-group">	
									<label>Banned status</label>
									<select class="form-control" name="role" value="test">
										<option<?php if(!$user->banned){echo " selected";}	?> value="0">Not Banned</option>
										<option<?php if($user->banned){echo " selected";}	?> value="1">Banned</option>
									</select>
								</div>
								<button class="btn btn-lg btn-primary btn-block" type="" name="">Cancel</button>
								<button class="btn btn-lg btn-success btn-block" type="submit" name="">Save</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
	</body>
</html>