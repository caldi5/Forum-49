<?php	

	require_once("../includes/init.php");

	//Kill if users is not admin
	if(!$currentUser->isAdmin())
	{
		header("Location: /index.php");
		die();
	}

	//Rediret to forums if no ID is set or if no forum by that ID exists
	if(!isset($_GET['id']) OR !getCategoryName($_GET['id']))
	{
		header("Location: forums.php");
		die();
	}


	if(isset($_POST["editForumForm"]))
	{
		$stmt = $conn->prepare('UPDATE forums SET name=?, description=?, category=?, ordering=? WHERE id=?');
		$stmt->bind_param('ssiii', $_POST["name"], $_POST["description"], $_POST["category"], $_POST["sortOrder"], $_GET['id']);
		$stmt->execute();
		if(!empty($stmt->error))
		{
			$alerts[] = new alert("danger", "Error:", "SQL error: " . $stmt->error);
		}
		else
		{
			$alerts[] = new alert("success", "Success:", "Successfully Updated Forum");
		}
		$stmt->close();
	}

	$forum = new forum($_GET['id']);

?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Admin - Edit Forum</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container-fluid"">
<?php displayAlerts(); ?>
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
				<div class="col-sm-10">
					<div class="panel panel-default">
						<div class="panel-heading">
							Edit Forum
						</div>
						<div class="panel-body">
							<form id="editForumForm" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label>Name:</label>
									<input type="text" maxlength="50" class="form-control" name="name" value="<?php echo htmlspecialchars($forum->name); ?>" required>
								</div>
								<div class="form-group">
									<label>Description:</label>
									<textarea name="description" cols="30" rows="2" class="form-control"><?php echo htmlspecialchars($forum->description); ?></textarea>
								</div>
								<div class="form-group">				
									<label>Category:</label>
										<select class="form-control" name="category">
<?php
	$CategoryNames =getAllCategoryNames();
	foreach ($CategoryNames as $CategoryName)
	{
		$categoryID = getCategoryID($CategoryName);
		if($forum->CategoryID === getCategoryID($CategoryNames))
		{
			echo "<option selected value=\"". $categoryID ."\">". $CategoryName ."</option>";
		}
		else
		{
			echo "<option value=\"". $categoryID ."\">". $CategoryName ."</option>";
		}
	} 
?>
									</select>
								</div>
								<div class="form-group">				
									<label>Sort Order:</label>
									<input type="text" maxlength="50" class="form-control" name="sortOrder" value="<?php echo $forum->sortOrder; ?>" required>
								</div>
								<button class="btn btn-lg btn-primary btn-block" type="">Cancel</button>
								<button class="btn btn-lg btn-success btn-block" type="submit" name="editForumForm">Save</button>
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