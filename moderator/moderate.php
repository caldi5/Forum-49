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
			<div class="col-sm-6" style="padding:0;">
				<a href="#" onclick="showTempBans(<?php echo $_GET['id']; ?>)" class="list-group-item">Temporary bans</a>
			</div>
			<div class="col-sm-6" style="padding:0;">
			<a href="#" onclick="showForumReports(<?php echo $_GET['id']; ?>)" class="list-group-item">Reports</a>
			</div>
			<div class="tempbans">
			</div>
		</div>
		<!-- Content end -->
		<script src="/js/custom/admin-menu.js"></script> 
<?php include("../includes/standard_footer.php"); ?>
	</body>
		<script>
		function showTempBans(forumid)
		{

			$.ajax({
				method: "post",
				url: "../ajax/tempbannedusers.php",
				async: true,
				data: {frmid: forumid}
			})
			.done(function(data){
				$(".tempbans").html(data);
			})
		}
		function removeBan(id)
		{
			$.ajax({
				method: "post",
				url: "../ajax/removeban.php",
				async: true,
				data: {userid: id}
			})
			.success : function(data){
				$("#")
			}
		} 
		</script>
</html>