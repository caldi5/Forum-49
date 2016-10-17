<?php	
	session_start();
	require_once "../functions/errors.php";

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="img/favicon.ico">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/custom.css" rel="stylesheet">
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