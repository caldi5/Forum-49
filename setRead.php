<?php
	require_once("includes/init.php");

	if (!isset($_GET['id']))
		return false;

	$id = $currentUser->id;
	$partner = $_GET['id'];

	$stmt = $conn->prepare("UPDATE messages SET isread = 1 WHERE (to_user = ? and from_user = ?) or (to_user = ? and from_user = ?)");
	$stmt->bind_param('iiii', $id, $partner, $partner, $id);
	$stmt->execute();
	$stmt->close();

?>