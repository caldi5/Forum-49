<?php

	/*

		This file either gets all of the messages between the current user and another user, or only gets some of the messages based on a timestamp.

	*/

	require_once("../includes/init.php");

	$userID = $currentUser->id;
	$partnerID = $_GET['id'];

	if(empty($userID) || !isset($userID))
		return false;

	// If no timestamp was specefied.
	if (!isset($_GET['t']))
	{
		// SQL gets all of the messages and if the message was sent or received.
		$stmt = $conn->prepare("select (IF (to_user = ?, 'received', 'sent')) as type, to_user as toID, from_user as fromID, message, isread, timestamp as created_at, timestamp as showTime from messages where (to_user = ? or from_user = ?) and (to_user = ? or from_user = ?) order by timestamp desc limit 20");

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
			$row['showTime'] = date('H:i d/m/y', $row['showTime']);
			$dataArray[] = $row;
		}

		echo json_encode($dataArray);
	}
	else
	{
		$time = $_GET['t'];

		// Same as before but now also checks the timestamp.
		$stmt = $conn->prepare("select (IF (to_user = ?, 'received', 'sent')) as type, to_user as toID, from_user as fromID, message, isread, timestamp as created_at, timestamp as showTime from messages where (to_user = ? or from_user = ?) and (to_user = ? or from_user = ?) and (timestamp > ?) order by timestamp desc limit 20");

		$stmt->bind_param('iiiiii', $userID, $userID, $userID, $partnerID, $partnerID, $time);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->store_result();
		$stmt->close();
		$dataArray = array();
		if ($result->num_rows == 0)
			return false;

		while ($row = $result->fetch_array())
		{
			$row['showTime'] = date('H:i d/m/y', $row['showTime']);
			$dataArray[] = $row;		
		}

		echo json_encode($dataArray);
	}
?>