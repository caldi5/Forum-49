<?php

	session_start();

	if(session_destroy())
	{
		setcookie("cn", "", time()-3600);
		setcookie("c0", "", time()-3600);
		setcookie("c1", "", time()-3600);
		setcookie("c2", "", time()-3600);
		setcookie("c3", "", time()-3600);
		
		header("location: index.php");
	}
?>