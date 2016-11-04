<?php

	require_once("../includes/init.php");

	if(isset($_GET['accept']))
		$currentUser->acceptFriendRequest($_GET['accept']);

		if(isset($_GET['deny']))
		$currentUser->denyFriendRequest($_GET['deny']);
?>