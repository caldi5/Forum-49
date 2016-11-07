<?php
	require_once("includes/init.php");

	$userID = $currentUser->id;
	if($userID == false)
		return false;

	// SQL för redan startade konversationer.
/*
	$stmt = $conn->prepare("select partner as partnerID, (select username from users where id = partner) as partnerUsername from
(SELECT (IF (to_user = ?, from_user, to_user)) as partner FROM
(select to_user, from_user from messages where to_user = ? OR from_user = ? order by timestamp desc) as mess
GROUP BY  least(to_user,  from_user), greatest(to_user ,  from_user)) as partners");

$stmt->bind_param('iii', $userID, $userID, $userID);
*/
	if (!isset($_GET['n']))
	{
		// SQL för alla vänner.
		$stmt = $conn->prepare("SELECT (IF (userid = ?, userid2, userid)) partnerID, (select username from users where id = partnerID) as partnerUsername FROM friends WHERE userid = ? or userid2 = ?");
		$stmt->bind_param('iii', $userID, $userID, $userID);
		
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
	else
	{
		$name =$_GET['n'];
		$name = '%'.$name.'%';

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