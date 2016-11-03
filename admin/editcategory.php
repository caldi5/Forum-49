<?php	

	require_once("../includes/init.php");

	//Kill if users is not admin
	if(!$currentUser->isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//Rediret to categories if no ID is set or if no category by that ID exists
	if(!isset($_GET['id']) OR !getCategoryName($_GET['id']))
	{
		header("Location: categories.php");
		die();
	}


	if(isset($_POST["editCategoryForm"]))
	{
		$stmt = $conn->prepare('UPDATE categories SET name=?, ordering=? WHERE id=?');
		$stmt->bind_param('ssi', $_POST["name"], $_POST["sortOrder"], $_GET['id']);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$error[] = "SQL error: " . $stmt->error;
		}
		else
		{
			$success[] = "Successfully Updated Category";
		}
		$stmt->close();
	}

	$category = new category($_GET['id']);

?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Admin - Edit Category</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container-fluid"">
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
				<div class="col-sm-10">
<?php if(isset($error)){ displayAlerts($error); } ?>
<?php if(isset($success)){ displayAlerts($success); } ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							Edit Category
						</div>
						<div class="panel-body">
							<form id="editCategoryForm" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label>Name:</label>
									<input type="text" maxlength="50" class="form-control" name="name" value="<?php echo $category->name; ?>" required>
								</div>
								<div class="form-group">				
									<label>Sort Order:</label>
									<input type="text" maxlength="50" class="form-control" name="sortOrder" value="<?php echo $category->sortOrder; ?>" required>
								</div>
								<button class="btn btn-lg btn-primary btn-block" type="">Cancel</button>
								<button class="btn btn-lg btn-success btn-block" type="submit" name="editCategoryForm">Save</button>
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