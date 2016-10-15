<?php	
	session_start();

	require_once "includes/dbconn.php";

	//-----------------------------------------------------
	//New Category
	//-----------------------------------------------------
	if(isset($_POST["newCategory"])) 
	{
		//-----------------------------------------------------
		//Insert the data in the database
		//-----------------------------------------------------
		if(!isset($error))
		{
			$stmt = $conn->prepare('INSERT INTO categories(name, ordering) VALUES (?,?)');
			$stmt->bind_param('si', $_POST["categoryName"], $_POST["ordering"]);
			$stmt->execute();
			if($stmt->error !== "")
			{
				$error[] = "SQL error: " . $stmt->error;
			}
			$stmt->close();
		}
	}

	//-----------------------------------------------------
	//Get existing categories
	//-----------------------------------------------------
	$stmt = $conn->prepare('SELECT id, name, ordering FROM categories');
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $name, $ordering);



?>
<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title>Admin</title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>
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
							<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur hic veniam ipsum aperiam maiores, consequuntur blanditiis corporis. Dolorem non mollitia quia deleniti necessitatibus autem, dolore reprehenderit, atque dolor similique praesentium.</span>
							<span>Placeat similique voluptate quam ut iste itaque quidem omnis assumenda labore esse distinctio sunt ipsum numquam quas, fugiat porro veritatis consequatur molestias laudantium! Soluta pariatur, non possimus velit voluptates magni.</span>
						</div>
					</div>
					<!-- Users End -->
					<!-- Categories Start -->
					<div id="pannelAdminCategories" class="panel panel-default collapse">
						<div class="panel-heading">
							Categories
							<button  type="button" class="btn btn-xs btn-success pull-right" data-toggle="collapse" data-target="#newCategoryWell">New Category</button>
						</div>
						<div class="panel-body">
							<div id="newCategoryWell" class="well well-sm collapse">
								<form id="newCategory" method="post" enctype="multipart/form-data">
									<div class="row">
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">Name: </span>
												<input type="text" name="categoryName" class="form-control" placeholder="Category name">
											</div>
										</div>
										<div class="col-sm-2">
											<div class="input-group">
												<span class="input-group-addon">Order:</span>
												<input type="text" name="ordering" class="form-control" size="1" placeholder="1">
											</div>
										</div>
										<div class="col-sm-3">
											<span class="input-group-btn">
												<button type="submit" name="newCategory" class="btn btn-sm btn-success">Create Category</button>
									    	<button type="button" class="btn btn-sm btn-default" data-toggle="collapse" data-target="#newCategoryWell">Cancel</button>							
											</span>
										</div>
									</div>
								</form>
							</div>
							<!-- List existing categories start-->
							<table id="sort" class="table">
						    <thead>
						        <tr>
						        	<th>ID</th>
						        	<th>Category</th>
						        	<th>Sort Order</th>
						        	<th>#of Forums</th>
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
?>
								</tbody>
							</table>
								<!-- List existing categories end-->
							</form>
						</div>
					</div>
					<!-- Categories End -->
					<div id="pannelAdminForums" class="panel panel-default collapse" in>
						<div class="panel-heading">
							Forums
							<button  type="button" class="btn btn-xs btn-success pull-right" data-toggle="collapse" data-target="#">New Forum</button>
						</div>
				</div>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
		<!-- unessesary
		<script src="js/jquery-ui.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script>
			// Return a helper with preserved width of cells
			var fixHelper = function(e, ui) {
					ui.children().each(function() {
							$(this).width($(this).width());
					});
					return ui;
			};

			$("#sort tbody").sortable({
					helper: fixHelper
			}).disableSelection();
		</script>
		-->
	</body>
</html>