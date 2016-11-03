<?php

require_once("includes/init.php");
if(isset($_POST['withUser']))
{
$withUser = $_POST['withUser'];
$result = $conn->prepare("SELECT username, message, messages.timestamp FROM users 
                   JOIN messages ON users.id = messages.from_user
				   WHERE (to_user = ? AND from_user = ?) OR (to_user = ? AND from_user = ?)");
$result->bind_param("iiii", $withUser, $currentUser->id, $currentUser->id, $withUser);
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
}
?>
     </div>