<?php	
	session_start();
	require_once "../functions/errors.php";

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
			</div>
		</div>
		<!-- Content end -->
<?php include("../includes/standard_footer.php"); ?>
	</body>
</html>