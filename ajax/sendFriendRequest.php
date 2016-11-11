<!-- Send friend request and return true false -->
<?php 
	
	require_once("../includes/init.php");

	if(isset($_POST["username"])){
		
		if($currentUser->sendFriendRequest($currentUser::getUserID($_POST["username"]))){
			echo '<div class="alert alert-info" role="alert">Friend Request Sent to: ';
			echo $_POST["username"];
			echo '!</div>';
		}
		else {
			echo '<div class="alert alert-info" role="alert">Friend Request Failed: ';
			echo $_POST["username"];
			echo '!</div>';
		}
	}

?>