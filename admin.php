<?php
	session_start();
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
					<!-- Users Start -->
					<div id="pannelAdminUsers" class="panel panel-default collapse in">
						<div class="panel-heading">
							<h3 class="panel-title">Users</h3>
						</div>
						<div class="panel-body">
							<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur hic veniam ipsum aperiam maiores, consequuntur blanditiis corporis. Dolorem non mollitia quia deleniti necessitatibus autem, dolore reprehenderit, atque dolor similique praesentium.</span>
							<span>Placeat similique voluptate quam ut iste itaque quidem omnis assumenda labore esse distinctio sunt ipsum numquam quas, fugiat porro veritatis consequatur molestias laudantium! Soluta pariatur, non possimus velit voluptates magni.</span>
						</div>
					</div>
					<!-- Users End -->
					<!-- Categories Start -->
					<div id="pannelAdminCategories" class="panel panel-default collapse in">
						<div class="panel-heading">
							<h3 class="panel-title">Categories</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<button  type="button" class="btn btn-success pull-right" data-toggle="collapse" data-target="#newCategoryWell">New Category</button>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div id="newCategoryWell" class="well well-sm collapse">
								<form action="">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Category name">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#newCategoryWell">Cancel</button>
										</span>
										<span class="input-group-btn">
											<button type="button" class="btn btn-success">Create Category</button>
										</span>
									</div>
								</form>
							 </div>
							 <div class="panel panel-default">
								<div class="panel-heading">
									9 Categories
								</div>
									<table id="sort" class="table">
								    <thead>
								        <tr><th>Category</th><th>#of Forums</th><th></th></tr>
								    </thead>
								    <tbody>
											<tr>
												<td>Anousments</td>
												<td>6</td>
												<td><button type="button" class="btn btn-xs btn-danger pull-right">Delete</button></td>
											</tr>
											<tr>
												<td>News</td>
												<td>3</td>
												<td><button type="button" class="btn btn-xs btn-danger pull-right">Delete</button></td>
											</tr>
											<tr>
												<td>Drugs</td>
												<td>203</td>
												<td><button type="button" class="btn btn-xs btn-danger pull-right">Delete</button></td>
											</tr>
										</tbody>
									</table>
							</div>
						</div>
					</div>
					<!-- Categories End -->
				</div>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
		<script src="js/jquery-ui.js"></script>
		<script src="js/jquery.ui.touch-punch.min"></script>
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
	</body>
</html>