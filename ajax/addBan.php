<!-- add a temp ban -->
<?php
	require_once("../includes/init.php");

	if(isset($_POST['username']))
	{
		$name = $_POST['username'];
        $userID = user::getUserID($name);
        $t = $_POST['time'];
        $t = strtotime($t);
        $forumid = $_POST['forumid'];

		$result = $conn->prepare("INSERT INTO tempban
                                VALUES (?,?,?)");
		$result->bind_param("iii",$userID,$t,$forumid);
		$result->execute();
	}  	
?>