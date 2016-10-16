<?php	
	session_start();

	require_once '../includes/dbconn.php';

	// Number of posts we want to display per page.
	$users_per_page = 10;

	// If the GET variable page isn't set, we just send them to the first page.
	if (isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	else
	{
		$page = 1;
	}

	/*
	 * The variable posts are basically the offset. 
	 * If we are on page 2 and are showing 10 results per page, we don't want to get the first 10.
	 * Then we want to start from 11.
	 */
	$users = ($users_per_page*$page)-$users_per_page;

	//-----------------------------------------------------
	//Get Users
	//-----------------------------------------------------
	$stmt = $conn->prepare('SELECT id, username, role, banned FROM users ORDER BY id LIMIT ? OFFSET ?');
	$stmt->bind_param('ii', $users_per_page, $users);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $username, $role, $banned);

?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Admin</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container-fluid"">
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
				<div class="col-sm-10">
<?php 
if(isset($error))
	displayErrors($error); 
?>
<!-- Users Start -->
					<div id="pannelAdminUsers" class="panel panel-default">
						<div class="panel-heading">
							Users
						</div>
						<div class="panel-body">
							<!-- List existing users start-->
							<table class="table">
						    <thead>
						        <tr>
						        	<th>ID</th>
						        	<th>Username</th>
						        	<th>Role</th>
						        	<th>Banned</th>
						        	</tr>
						    </thead>
						    <tbody>
<?php
	while($stmt->fetch())
	{
?>
									<tr>
										<td><?php echo $id; ?></td>
										<td><?php echo $username; ?></td>
										<td><?php echo $role; ?></td>
										<td><?php
										if($banned === 0)
											echo "No"; 
										else
											echo "yes";
										?></td>
										<td><span class="input-group-btn"><button type="button" class="btn btn-xs btn-danger pull-right">Delete</button><button type="button" class="btn btn-xs btn-success pull-right">Edit</button></span></td>
									</tr>
<?php
	}
	$stmt->free_result();
	$stmt->close();
?>
								</tbody>
							</table>
							<!-- List existing users end-->
						</div>
					</div>
					<!-- Users End -->
				</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
	</body>
</html>