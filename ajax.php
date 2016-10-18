<?php 
	//======================================================================
	// Returns json object som be used on various pages
	//======================================================================
	session_start();

	#requires
	require_once 'includes/dbconn.php';
	require_once 'functions/user.php';

	//======================================================================
	// admin functions ish?
	//======================================================================
	if (isset($_GET['admin']))
	{
		//Must be admin to use
		if(!isAdmin())
			die();

		//-----------------------------------------------------
		//Return userlist 
		//-----------------------------------------------------
		if($_GET['admin'] === 'getusers')
		{
			//Ofset limnit stuff
			/*
			 * To retrieve all rows from a certain offset up to the end of the result set, you can use some large number for the second parameter. --MySQL
			 */
			$limit =  PHP_INT_MAX;
			$offset = 0;
			if(isset($_GET['limit']))
				$limit = $_GET['limit'];
			if(isset($_GET['offset']))
					$offset = $_GET['offset'];

			$stmt = $conn->prepare('SELECT id, username, role, banned FROM users ORDER BY id LIMIT ? OFFSET ?');
			$stmt->bind_param('ii', $limit, $offset);
			$stmt->execute();
			$result  = $stmt->get_result();
			while($row = $result->fetch_assoc())
				$rows[] = $row;
			if(isset($rows))
				echo json_encode($rows);
		}

	}

 ?>