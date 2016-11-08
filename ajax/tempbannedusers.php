<?php

require_once("../includes/init.php");
if(isset($_POST['frmid']))
{
$frmid = $_POST['frmid'];
$result = $conn->prepare("SELECT users.id, username, banneduntill FROM users 
                    JOIN tempban ON users.id = tempban.id
                    WHERE onforum = ?");
$result->bind_param("i", $frmid);
$result->execute();
$result->store_result();
$result->bind_result($id,$name,$timestamp);
    ?>
    <div class="col-sm-12 messagecont">
<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         ?>
                <div class="col-sm-12 list-group-item">
                
                <h4 class="list-group-item-heading"><?php echo $id; ?></h4>
                <p class="list-group-item-text"><?php echo $name; ?></p><br>
                <div class="col-sm-3 messagecont"><p class="timestamp">Sent: <?php echo date('H:i d/m/y', $timestamp);?></p></div>
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