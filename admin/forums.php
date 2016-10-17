<?php	
	session_start();

	require_once "../includes/dbconn.php";
	require_once "../functions/errors.php";
	require_once "../functions/get.php";
	require_once "../functions/user.php";
	require_once "../functions/admin.php";

	//Kill if users is not admin
	if(!isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//-----------------------------------------------------
	//New Forums
	//-----------------------------------------------------
	if(isset($_POST["newForum"])) 
	{
		newForum($_POST["forumName"], $_POST["description"], $_POST["category"], $_POST["ordering"]);
	}

	//-----------------------------------------------------
	//Get existing Forums
	//-----------------------------------------------------
	$stmt = $conn->prepare('SELECT id, name, category, ordering FROM forums');
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $name, $category, $ordering);

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
					<!-- Forums Start -->
					<div class="panel panel-default">
						<div class="panel-heading">
							Forums
							<button  type="button" class="btn btn-xs btn-success pull-right" data-toggle="collapse" data-target="#newForumWell">New Forum</button>
						</div>
						<div class="panel-body">
							<div id="newForumWell" class="well well-sm collapse">
								<form id="newForum" method="post" enctype="multipart/form-data">
									<div class="row">
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">Name: </span>
												<input type="text" name="forumName" class="form-control" placeholder="Forum name">
											</div>
										</div>
										<div class="col-sm-3">
											<div class="input-group">
												<span class="input-group-addon">Category:</span>
												<select class="form-control" name="category">
<?php
	$CategoryNames =getAllCategoryNames();
	foreach ($CategoryNames as $CategoryName)
	{
		echo "<option>". $CategoryName ."</option>";
	} 
?>
										  	</select>
										  </div>
									  </div>
										<div class="col-sm-2">
											<div class="input-group">
												<span class="input-group-addon">Order:</span>
												<input type="text" name="ordering" class="form-control" size="1" placeholder="1">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="input-group">
												<span class="input-group-addon">Description:</span>
												<textarea name="description" cols="30" rows="2" class="form-control"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<span class="input-group-btn">
												<button type="submit" name="newForum" class="btn btn-sm btn-success">Create Category</button>
									    	<button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#newForumWell">Cancel</button>							
											</span>
										</div>
									</div>
								</form>
							</div>
							<!-- List existing Forums start-->
							<table class="table">
						    <thead>
						        <tr>
						        	<th>ID</th>
						        	<th>Name</th>
						        	<th>Category</th>
						        	<th>Sort Order</th>
						        	<th>#of Posts</th>
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
										<td><?php echo getCategoryName($category); ?></td>
										<td><?php echo $ordering; ?></td>
										<td><?php echo numberOfPosts($id); ?></td>
										<td><span class="input-group-btn"><a href="?action=delete&id=<?php echo $id; ?>" class="btn btn-xs btn-danger pull-right">Delete</a><a href="?action=edit&id=<?php echo $id; ?>" class="btn btn-xs btn-success pull-right">Edit</a></span></td>
									</tr>
<?php
	}
	$stmt->free_result();
	$stmt->close();
?>
								</tbody>
							</table>
								<!-- List existing Forums end-->
							</form>
						</div>
					</div>
					<!-- Forums End -->
			</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>

	</body>
</html>