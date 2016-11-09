<?php	

	require_once("../includes/init.php");

	//Rediret to forums if no ID is set or if no forum by that ID exists
	if(!isset($_GET['id']) OR !$currentUser->isAdmin())
	{
		header("Location: forums.php");
		die();
	}

	try {
		$forum = new forum($_GET['id']);
	} catch (Exception $e) {
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

		//Deleta all moderators for forum
		$stmt = $conn->prepare('DELETE FROM  moderators WHERE forumID=?');
		$stmt->bind_param('i', $forum->id);
		$stmt->execute();
		$stmt->close();

		//If any moderators selected, add them to the forum
		if(!empty($_POST['moderators']))
		{	
			foreach ($_POST['moderators'] as $moderatorID) 
			{
				$stmt = $conn->prepare('INSERT INTO moderators(userID, forumID) VALUES(?,?)');
				$stmt->bind_param('ii', $moderatorID, $forum->id);
				$stmt->execute();
				$stmt->close();
			}
		}
	}


	//Get Current Forum Moderators
	$stmt = $conn->prepare('SELECT id, username, forumID FROM users LEFT OUTER JOIN (SELECT * FROM moderators WHERE forumid=?) AS moderatorsForForum ON users.id = moderatorsForForum.userid WHERE role = "moderator"');
	$stmt->bind_param('i', $forum->id);
	$stmt->execute();
	$stmt->bind_result($id, $username, $forumID);
	while ($stmt->fetch()) 
	{
		$moderator['id'] = $id;
		$moderator['username'] = $username;
		$moderator['forumID'] = $forumID;
		$moderators[] = $moderator;
	}
	$stmt->close();


?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<link href="/css/bootstrap-select.css" rel="stylesheet">
		<title>Admin - Edit Forum</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row">
<?php include("../includes/admin_menu.php"); ?>
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
	$categories = category::getAllCategories();
	foreach ($categories as $category) 
	{
		if($forum->category === $category->id)
			echo '<option selected value="'. $category->id .'">'. $category->name .'</option>';
		else
			echo '<option value="'. $category->id .'">'. $category->name .'</option>';
	} 
?>
								</select>
							</div>
							<div class="form-group">				
								<label>Sort Order:</label>
								<input type="text" maxlength="50" class="form-control" name="sortOrder" value="<?php echo $forum->sortOrder; ?>" required>
							</div>
							<div class="form-group">				
								<label>Forum Moderators:</label>
								<!-- moderators[]: the backets are important to let php know that it's an array-->
								<select name="moderators[]" class="form-control selectpicker" multiple title="This forum does not have any moderators. Choose some!">
<?php 
	foreach($moderators as $moderator)
	{
		if($moderator['forumID'] === $forum->id)
			echo '<option selected value="'. $moderator['id'] .'"">'. $moderator['username'] .'</option>';
		else
			echo '<option value="'. $moderator['id'] .'"">'. $moderator['username'] .'</option>';
	}
?>
								</select>
							</div>
							<button class="btn btn-lg btn-primary btn-block" type="">Cancel</button>
							<button class="btn btn-lg btn-success btn-block" type="submit" name="editForumForm">Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
		<script src="/js/custom/admin-menu.js"></script>
		<script src="/js/bootstrap-select.js"></script>
	</body>
</html>