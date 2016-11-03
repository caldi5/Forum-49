<?php

require_once("includes/init.php");
if(isset($_POST['withUser']))
{
$withUser = $_POST['withUser'];
$result = $conn->prepare("SELECT username, message, messages.timestamp FROM users 
                   JOIN messages ON users.id = messages.from_user
				   WHERE to_user = ? AND from_user = ?");
$result->bind_param("ii", $withUser, $currentUser->id);
$result->execute();
$result->store_result();
$result->bind_result($name,$message,$timestamp);
    ?>
    <div class="list-group col-sm-12 messagecont">
<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         ?>
                <a class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $name; ?></h4>
                <p class="list-group-item-text"><?php echo $message; ?></p>
                </a>
        <?php
    }
}
?>
<?php
    $result->close();
    
$result2 = $conn->prepare("SELECT username, message, messages.timestamp FROM users 
                   JOIN messages ON users.id = messages.to_user
				   WHERE to_user = ? AND from_user = ?");
$result2->bind_param("ii", $withUser, $currentUser->id);
$result2->execute();
$result2->store_result();
$result2->bind_result($name,$message,$timestamp);
    ?>
<?php
if($result2->num_rows > 0)
{
    while($result2->fetch())
    {
         ?>
                <a class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $name; ?></h4>
                <p class="list-group-item-text"><?php echo $message; ?></p>
                </a>
        <?php
    }
}
?>
<?php
    $result2->close();
}

?>
     </div>