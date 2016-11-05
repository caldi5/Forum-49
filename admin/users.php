<?php	

	require_once("../includes/init.php");
	require_once("../functions/admin.php");

	//Kill if users is not admin
	if(!$currentUser->isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//-----------------------------------------------------
	//Delete user
	//-----------------------------------------------------
	if (isset($_GET['action']) && isset($_GET['id']))
	{
		if($_GET['action'] === "delete" && $_GET['id'] !== $currentUser->id)
			deleteUser($_GET['id']);
	}

	//-----------------------------------------------------
	//Get Users
	//-----------------------------------------------------
	$stmt = $conn->prepare('SELECT id, username, role, banned, validEmail FROM users');
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $username, $role, $banned, $validEmail);

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
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
<!-- Users Start -->
				<div id="pannelAdminUsers" class="panel panel-default">
					<div class="panel-heading">
						Users
					</div>
					<div class="panel-body">
						<!-- List existing users start-->
						<div style="overflow-x: auto;">
							<table class="table">
								<thead>
										<tr>
											<th>ID</th>
											<th>Username</th>
											<th>Role</th>
											<th>Banned</th>
											<th>Valid Email</th>
											<th></th>
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
										if($banned)
											echo "Yes"; 
										else
											echo "No";
										?></td>
										<td><?php
										if($validEmail)
											echo "Yes"; 
										else
											echo "No";
										?></td>
										<td><a href="#", data-href="?action=delete&id=<?php echo $id; ?>" data-toggle="modal" data-target="#confirm-delete" class="btn btn-xs btn-danger pull-right <?php if($id === $currentUser->id){ echo "disabled";} ?>">Delete</a><a href="edituser.php?id=<?php echo $id; ?>" class="btn btn-xs btn-success pull-right">Edit</a></td>
									</tr>
<?php
	}
	$stmt->free_result();
	$stmt->close();
?>
								</tbody>
							</table>
						</div>
						<!-- List existing users end-->
					</div>
				</div>
				<!-- Users End -->
				<!-- Modal confirmation start -->
				<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
							<div class="modal-content">
									<div class="modal-header">
											<h3>Warning!<h3>
									</div>
									<div class="modal-body">
											You're about to delete a user this can not be undone.
									</div>
									<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											<a class="btn btn-danger btn-ok">Delete</a>
									</div>
							</div>
					</div>
				</div>
				<!-- Modal confirmation End -->
			</div>
			<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
		<script src="/js/custom/admin-menu.js"></script> 
		<script>
			$('#confirm-delete').on('show.bs.modal', function(e) {
				$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
			});
		</script>
	</body>
</html>