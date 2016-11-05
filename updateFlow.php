<?php
	require_once("includes/init.php");

	$time = $_GET['t'];

	$stmt = $conn->prepare('
(SELECT "comment" as type, (SELECT username from users where id = userID) as username, (SELECT title from posts where id = postID) as post, postID, (SELECT name FROM forums WHERE id = (SELECT forum FROM posts WHERE id = postID)) as forum, text, created_at, created_at as date FROM comments WHERE created_at > ?)
UNION
(SELECT "post" as type, (SELECT username from users where id = creator) as username, title as post, id as postID, (SELECT name FROM forums WHERE id = forum) as forum, text, created_at, created_at as date FROM posts WHERE created_at > ?)
ORDER BY created_at ASC
			');

	$stmt->bind_param('ii', $time, $time);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->store_result();
	$stmt->close();
	$dataArray = array();

	if ($result->num_rows == 0)
		return false;

	while ($row = $result->fetch_array())
	{
		$row['date'] = date('H:i d/m/y', $row['date']);
		$row['text'] = htmlspecialchars($row['text']);
		$dataArray[] = $row;		
	}

	echo json_encode($dataArray);
?>