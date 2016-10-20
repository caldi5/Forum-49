<?php
session_start();
require_once "includes/dbconn.php";
require_once "functions/user.php";
if(!isset($_SESSION['id']))
{
    echo '<p>Session Expired</p>';
    exit();
}
if(isset($_POST['message']))
{
    $to = $_POST['reciever'];
    $from = $_POST['senderid'];
    $msg = htmlspecialchars($_POST['message']);
    $msg = $conn->real_escape_string($msg);
    $t = time();
    
    if(!usernameExists($to))
    {
        echo '<p>User does not exist</p>';
        exit();
    }
    $toID = getUserID($to);
    if(empty($to) || empty($from) || empty($msg))
    {
        echo '<p> Missing Data To Continue </p>';
        exit();
    }
    $sql = "INSERT INTO messages (to_user, from_user, message, timestamp) VALUES('$toID','$from','$msg','$t')";
    if($conn->query($sql) === true)
    {
        echo '<p>Message sent successfully</p>';
    }
}
?>