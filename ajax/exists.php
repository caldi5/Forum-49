<?php 
	session_start();

	#requires
	require_once '../includes/dbconn.php';

	/*
	 * WARNING!!!!!
	 * returns true is username or email does NOT exist in database.
	 */
	if(isset($_GET['username']))
	{
		$stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
		$stmt->bind_param('s', $_GET['username']);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			echo "true";
		else
			echo "false";
		$stmt->free_result();
		$stmt->close();
	}

	if(isset($_GET['email']))
	{
		$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
		$stmt->bind_param('s', $_GET['email']);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows == 0)
			echo "true";
		else
			echo "false";
		$stmt->free_result();
		$stmt->close();
	}

 ?>