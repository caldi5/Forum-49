<?php	

	require_once("../includes/init.php");

	//Kill if users is not admin
	if(!$currentUser->isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//Rediret to users if no ID is set or if no user by that ID exists
	if(!isset($_GET['id']) OR !user::getUsernameID($_GET['id']))
	{
		header("Location: users.php");
		die();
	}

	if(isset($_POST["editUserForm"]))
	{
		$stmt = $conn->prepare('UPDATE users SET username=?, email=?, role=?, banned=? WHERE id=?');
		$stmt->bind_param('sssii', $_POST["username"], $_POST["email"], $_POST["role"], $_POST["banned"], $_GET['id']);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$alerts[] = new alert("danger", "Error:", "SQL error: " . $stmt->error);
		}
		else
		{
			$alerts[] = new alert("success", "Success:", "Successfully Updated User");
		}
		$stmt->close();
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
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
				<div class="col-sm-10">
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
										<option<?php if($user->role === "Admin"){echo " selected";}	?> value="admin">Administrator</option>
										<option<?php if($user->role === "Moderator"){echo " selected";}	?> value="moderator">Moderator</option>
										<option<?php if($user->role === "User"){echo " selected";}	?>  value="moderator">User</option>
									</select>
								</div>
								<div class="form-group">	
									<label>Banned status</label>
									<select class="form-control" name="banned" value="test">
										<option<?php if(!$user->banned){echo " selected";}	?> value="0">Not Banned</option>
										<option<?php if($user->banned){echo " selected";}	?> value="1">Banned</option>
									</select>
								</div>
								<button class="btn btn-lg btn-primary btn-block" type="">Cancel</button>
								<button class="btn btn-lg btn-success btn-block" type="submit" name="editUserForm">Save</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Content end -->
		<script src="/js/custom/admin-menu.js"></script> 
<?php include("../includes/standard_footer.php"); ?>
	</body>
</html>