<!-- Get the name of all of the users friends -->
<?php

    require_once("../includes/init.php");

if(isset($_POST['search']))
{
$searchtext = $_POST['search'];
$likeString = $searchtext . '%';

$result = $conn->prepare("select username from ((SELECT * FROM users
                        JOIN friends ON users.id = friends.userid2
                        WHERE userid = ? AND username LIKE ?)
                        UNION
                        (SELECT * FROM users
                        JOIN friends ON users.id = friends.userid
                        WHERE userid2 = ? AND username LIKE ?)) as a");
$result->bind_param("isis", $currentUser->id, $likeString, $currentUser->id, $likeString);
$result->execute();
$result->store_result();
$result->bind_result($name);
if($result->num_rows > 0)
{
    while($result->fetch())
    {
        ?>
        <a href="#" class="searchoption" onClick="selectFriend('<?php echo $name; ?>')">
        <h4 class="mates"><?php echo $name; ?></h4>
        </a><br>
        <?php
    }
}
    $result->close();
}
?>