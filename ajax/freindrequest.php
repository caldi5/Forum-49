<?php

	require_once("../includes/init.php");

	//the get variable is the ID of the person sending the request
	if(isset($_GET['accept']))
		$currentUser->acceptFriendRequest($_GET['accept']);

		if(isset($_GET['deny']))
		$currentUser->denyFriendRequest($_GET['deny']);
?>