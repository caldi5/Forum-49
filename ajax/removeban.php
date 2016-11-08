<?php
	require_once("../includes/init.php");

	if(isset($_POST['userid']))
	{
		$userid = $_POST['userid'];

		$result = $conn->prepare("DELETE FROM tempban
								WHERE id = ?");
		$result->bind_param("i",$userid);
		$result->execute();
	}  	
?>