<?php

    require_once("includes/init.php");

if(isset($_POST['search']))
{
$searchtext = $_POST['search'];
$likeString = '%' . $searchtext . '%';

$result = $conn->prepare("SELECT username FROM users
                        JOIN friends ON users.id = friends.userid2
                        WHERE userid = ? AND username LIKE ?");
$result->bind_param("is", $currentUser->id, $likeString);
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