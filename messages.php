<?php
	session_start();
	require_once("includes/dbconn.php"); 
?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once("includes/standard_head.php"); ?>
		<title>Forum- Messages</title>
	</head>
	<body>
<?php require_once("includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
			<div class="row">
				<nav class="col-sm-3">
					<ul class="list-group">
						<li class="list-group-item">Friend 1</a></li>
						<li class="list-group-item"><a href="#section2">Friend 2</a></li>
						<li class="list-group-item"><a href="#section3">Friend 3</a></li>
						<li class="list-group-item"><a href="#section41">Friend 4</a></li>
						<li class="list-group-item"><a href="#section42">Friend 5</a></li>
					</ul>
				</nav>
				<div class="col-sm-9">
					<div id="section1">
						<h3>Sender!</h3>
						<p>Message</p>
					</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>