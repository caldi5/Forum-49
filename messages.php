<?php
	session_start();
	require_once("includes/dbconn.php"); 
?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once("includes/standard_head.php"); ?>
		<title>Forum- Messages</title>
	</head>
	<body>
<?php require_once("includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
                <div class="list-group col-sm-3 friends">
                    <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">Friend 1 Name</h4>
                    <p class="list-group-item-text">Friend 2 Description</p>    
                    </a>
                    <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">Friend 2 Name</h4>
                    <p class="list-group-item-text">Friend 2 Description</p>    
                    </a>
                </div>
                <div class="col-sm-9 content">
				    <h3>From: Friend 1</h3>
				    <p>Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance.Message, This is a message! Hello i have a cat as a profile picture and i need to write this to you it is of out most importance. </p>
                </div>
            <div class="col-sm-7 messageform">
                <form>
                    <div class="form-group messageformtextbox">
                    To: <input type="text" class="form-control" id="reciever">
                    </div>
                    <div class="form-group messageformtextbox">
                    Message: <input type="text" class="form-control" id="message">
                    </div>
                    <button type="submit" class="btn btn-default">Send</button>
                </form>
            </div>
		</div>
		<!-- Content end -->
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>