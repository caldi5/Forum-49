<?php

	/*
	This file either gets all of the current users friends,
	or this users friends that match a serach string.

	*/

	require_once("../includes/init.php");

	$userID = $currentUser->id;
	if($userID == false)
		return false;

	// If no search string is set.
	if (!isset($_GET['n']))
	{
		// SQL gets all of the current users friends.
		$stmt = $conn->prepare("SELECT (IF (userid = ?, userid2, userid)) partnerID, (select username from users where id = partnerID) as partnerUsername FROM friends WHERE userid = ? or userid2 = ?");
		$stmt->bind_param('iii', $userID, $userID, $userID);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->store_result();
		$stmt->close();
		$dataArray = array();
		if ($result->num_rows == 0)
			return false;

		// Put the data into an array.
		while ($row = $result->fetch_array())
		{
			$dataArray[] = $row;		
		}

		echo json_encode($dataArray);
	}
	else
	{
		// If there is a search string.
		$name =$_GET['n'];
		$name = $name.'%';

		// SQL same as before, but the friends has to match the search string.
		$stmt = $conn->prepare("SELECT * FROM (SELECT (IF (userid = ?, userid2, userid)) partnerID, (select username from users where id = partnerID) as partnerUsername FROM friends WHERE (userid = ? or userid2 = ?)) as first WHERE partnerUsername like ?");
		$stmt->bind_param('iiis', $userID, $userID, $userID, $name);
		
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
	}
?>