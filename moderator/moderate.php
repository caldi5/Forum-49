<?php	

	require_once("../includes/init.php");

	//Rediret to forums if no ID is set or if no forum by that ID exists
	if(!isset($_GET['id']) OR !$currentUser->isModeratorID($currentUser->id,$_GET['id']))
	{
		header("Location: ../forums.php");
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
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
<?php include("../includes/standard_head.php"); ?>
		<title>Moderator - Edit Forum</title>
	</head>
	<body>
<?php include("../includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row"><!-- Moderator Menu Start-->
				<nav class="navbar navbar-default adminMenuHeader" role="navigation">
					<div class="container-fluid">
						<div class="navbar-header">
							<label class="navbar-brand col-sm-0">Moderator <span class="red-text">Menu</span></label>
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#adminMenu">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
					</div>
				</nav>
				<div id="adminMenu" class="collapse navbar-collapse" style="overflow: hidden;">
					<ul class="nav nav-tabs nav-justified">
						<li><a onclick="showTempBans(<?php echo $_GET['id']; ?>)" class="list-group-item">Temporary bans</a></li>
						<li><a onclick="showForumReports(<?php echo $_GET['id']; ?>)" class="list-group-item">Reports</a></li>
					</ul>
				</div>
				<!-- Admin Menu End-->
				<div class="tempbans">
				</div>
		<!-- Content end -->
		<script src="/js/custom/admin-menu.js"></script> 
<?php include("../includes/standard_footer.php"); ?>
	</body>
		<script>
		function showTempBans(forumid)
		{
			$.ajax({
				method:"post",
				url: "ajax/tempbannedusers.php",
				async: true,
				data: {frmid: forumid}
			})
			.done(function(data){
				$(".tempbans").html(data);
			})
		} 
		</script>
</html>