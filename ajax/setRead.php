<?php

	// This file sets all of the messages to read in a conversation

	require_once("../includes/init.php");

	if (!isset($_GET['id']))
		return false;

	$id = $currentUser->id;
	$partner = $_GET['id'];

	// The SQL to update the isread column
	$stmt = $conn->prepare("UPDATE messages SET isread = 1 WHERE to_user = ? and from_user = ?");
	$stmt->bind_param('ii', $id, $partner);
	$stmt->execute();
	$stmt->close();

?>