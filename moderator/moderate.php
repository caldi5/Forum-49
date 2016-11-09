<?php	

	require_once("../includes/init.php");

	//Rediret to forums if no ID is set or if no forum by that ID exists
	if(!isset($_GET['id']) || (!$currentUser->isModeratorID($currentUser->id,$_GET['id']) && !$currentUser->isAdmin()))
	{
		header("Location: ../forum.php");
		die();
	}

	try {
		$forum = new forum($_GET['id']);
	} catch (Exception $e) {
		header("Location: forum.php");
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
                <table class="table">
                    <tr>
                        <td><form class="form-inline">User: <input type="text" class="form-control" id="user"></form></td>
                        <td><form class="form-inline">Untill: <input type="text" class="form-control" id="untill" placeholder="hh:mm yy-mm-dd"></form></td>
                        <td><button onclick="addBan(<?php echo $_GET['id']; ?>)" type="button" class="btn btn-primary pull-right">Add Ban</button></td>
                    </tr>
                </table>
			<div class="tempbans">
			</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
	</body>
		<script>
        //Show people that are temporarily banned in forum with forumid, returns table with answers to the tempban div
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
        //remove a temp ban with the given user id
		function removeBan(id)
		{
			$.ajax({
				method: "post",
				url: "../ajax/removeban.php",
				async: true,
				data: {userid: id}
			})
		}
        // add a temp ban to the forum with frmid, takes the username from the user form and Untill as a datetime from untill form
        function addBan(frmid)
            {
                var user = $("#user").val();
                var time = $("#untill").val();
                $.ajax({
                    method: "post",
                    url: "../ajax/addBan.php",
                    async: true,
                    data: {username: user, time: time, forumid: frmid}
                })
            }
		</script>
</html>