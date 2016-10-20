<?php
session_start();
require_once "includes/dbconn.php";
require_once "functions/user.php";

$id = getUserID(getUsername());
if(isset($_POST['search']))
{
$searchtext = $_POST['search'];
    
$result = $conn->query("SELECT username FROM users
                        JOIN friends ON users.id = friends.userid2
                        WHERE userid = '$id' AND username LIKE '%$searchtext%'");
if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        ?>
        <a href="#" class="searchoption" onClick="selectFriend('<?php echo $row["username"]; ?>')">
        <?php
        echo $row['username'];
        echo '</a>';
        echo '<br>';
    }
}
}
?>