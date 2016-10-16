<?php	
	session_start();

	require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/dbconn.php");

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
	$posts = ($users_per_page*$page)-$users_per_page;

	//-----------------------------------------------------
	//Get Users
	//-----------------------------------------------------
	$stmt = $conn->prepare('SELECT id, username, role FROM users ORDER BY id LIMIT ? OFFSET ?');
	$stmt->bind_param('ii', $posts_per_page, $posts);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $username, $role);

?>
<!DOCTYPE html>
<html>
	<head>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/standard_head.php"); ?>
		<title>Admin</title>
	</head>
	<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container-fluid"">
			<div class="row">
				<!-- Admin Menu Start--> 
				<div class="col-sm-2">
					<div class="list-group">
						<a href="#" class="list-group-item active" data-toggle="collapse" data-target="#adminMenuColapse">Admin Menu <span class="caret"></span></a>
						<div id="adminMenuColapse" class="collapse in">
							<a href="#" class="list-group-item" data-toggle="collapse" data-target="#pannelAdminUsers">Users</a>
							<a href="#" class="list-group-item" data-toggle="collapse" data-target="#pannelAdminCategories">Categories</a>
							<a href="#" class="list-group-item" data-toggle="collapse" data-target="#pannelAdminForums">Forums</a>
						</div>
					</div>
				</div>
				<!-- Admin Menu End-->
				<div class="col-sm-10">
<?php
	if(isset($error))
	{
		foreach ($error as $err) 
		{
			echo "<div class=\"alert alert-danger\">\r\n";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\r\n";
			echo "<strong>Error</strong> ". $err. "\r\n";
			echo "</div>" . "\r\n";
		}
	}
?>
<!-- Users Start -->
					<div id="pannelAdminUsers" class="panel panel-default collapse">
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
						        	<th></th>
						        	<th></th>
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
										<td><?php echo $name; ?></td>
										<td><?php echo $ordering; ?></td>
										<td>123123</td>
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
<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/standard_footer.php"); ?>
	</body>
</html>