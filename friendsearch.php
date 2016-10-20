<?php
session_start();
require_once "includes/dbconn.php";
require_once "functions/user.php";

$id = getUserID(getUsername());
if(isset($_POST['search']))
{
$searchtext = $_POST['search'];
$likeString = '%' . $searchtext . '%';

$result = $conn->prepare("SELECT username FROM users
                        JOIN friends ON users.id = friends.userid2
                        WHERE userid = '$id' AND username LIKE ?");
$result->bind_param("s", $likeString);
$result->execute();
$result->store_result();
$result->bind_result($name);
if($result->num_rows > 0)
{
    while($result->fetch())
    {
        ?>
        <a href="#" class="searchoption" onClick="selectFriend('<?php echo $name; ?>')">
        <?php
        echo $name;
        echo '</a>';
        echo '<br>';
    }
}
    $result->close();
}
?>