<?php

	require_once("includes/init.php");

	$userID = $currentUser->id;
	$partnerID = $_GET['id'];

	if($userID == false)
		return false;

	$stmt = $conn->prepare("select (IF (to_user = ?, 'received', 'sent')) as type, to_user as toID, from_user as fromID, message, isread, timestamp as created_at from messages where (to_user = ? or from_user = ?) and (to_user = ? or from_user = ?) order by timestamp desc limit 20");

	$stmt->bind_param('iiiii', $userID, $userID, $userID, $partnerID, $partnerID);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->store_result();
	$stmt->close();
	$dataArray = array();
	if ($result->num_rows == 0)
		return false;

	while ($row = $result->fetch_array())
	{
		$dataArray[] = $row;		
	}

	echo json_encode($dataArray);

?>