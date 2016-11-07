<?php

require_once("../includes/init.php");
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
    <div class="col-sm-12 messagecont">
<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         ?>
                
                <h4 class="list-group-item-heading"><?php echo $name; ?></h4>
                <p class="list-group-item-text"><?php echo $message; ?></p><br>
                </div>
                
        <?php
    }
}
$result2 = $conn->prepare("UPDATE messages
                           SET isread=1
                           WHERE to_user = ? AND from_user = ?");
$result2->bind_param("ii",$currentUser->id,$withUser);
$result2->execute();
}
?>
     </div>
