<?php

require_once("../includes/init.php");
if(isset($_POST['frmid']))
{
$frmid = $_POST['frmid'];
$result = $conn->prepare("SELECT users.id, username, banneduntill FROM users 
                    JOIN tempban ON users.id = tempban.id
                    WHERE onforum = ?");
$result->bind_param("i",$frmid);
$result->execute();
$result->store_result();
$result->bind_result($id,$name,$timestamp);
    ?>
        <table class="table">
<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         ?>
         <tr>
                                        <td><?php echo $name; ?></td>
                                        <td>Banned untill: <?php echo date('H:i d/m/y', $timestamp); ?></td>
                                        <td><a href="#" class="btn btn-xs btn-danger pull-right" onclick="removeBan(<?php echo $id ?>)">Unban</a></td>
                                    </tr>
                                    
        <?php
    }
}
}
?>
</table>