<?php
session_start();
require_once "includes/dbconn.php";
require_once "functions/user.php";
$username = getUsername();
if(isset($_POST['search']))
{
$searchtext = $_POST['search'];
    
$result = $conn->query("SELECT username, username2 FROM users
                        JOIN friends ON users.id = friends.userid
                        WHERE username = '$username' AND username2 LIKE '%$searchtext%'");
if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        ?>
        <a href="#" class="searchoption" onClick="selectFriend('<?php echo $row["username2"]; ?>')">
        <?php
        echo $row['username2'];
        echo '</a>';
        echo '<br>';
    }
}
}
?>