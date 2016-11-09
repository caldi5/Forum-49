<!-- Load all conversations for the user--> 
<!DOCTYPE html>
<html>
    <head>
<?php require_once "../includes/standard_head.php"; ?>
    </head>
    <body>
<?php
require_once("../includes/init.php");
            $result = $conn->prepare("select distinct username from((SELECT DISTINCT username, to_user, from_user FROM users 
                        JOIN messages ON users.id = messages.to_user
                        WHERE from_user = ?)
                        UNION
                        (SELECT DISTINCT username, to_user, from_user FROM users 
                        JOIN messages ON users.id = messages.from_user
                        WHERE to_user = ?)) as a");
            $result->bind_param("ii", $currentUser->id, $currentUser->id);
            $result->execute();
            $result->store_result();
            $result->bind_result($name);
            $i = 0;
?>
    <div class="list-group col-sm-12 friends">

<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         $conversWithID = user::getUserID($name)
         ?>
                    
                    <a href="#" class="list-group-item" onclick="showConversation('<?php echo $conversWithID; ?>','<?php echo $i; ?>')">
                    <h4 class="list-group-item-heading" id="<?php echo $i; ?>"><?php echo $name; $i++; ?></h4>
        <?php
        $result2 = $conn->prepare("SELECT SUBSTRING(message,1,26), timestamp, id FROM messages
                        WHERE to_user = ? AND from_user = ?
						order by timestamp desc
						limit 1");
        $result2->bind_param("ii", $currentUser->id,$conversWithID);
        $result2->execute();
        $result2->store_result();
        $result2->bind_result($lastMessage,$timestamp, $messageid);
       if($result2->num_rows > 0)
       {
           while($result2->fetch())
            {
               ?>
                    <p class="list-group-item-text"><?php echo $lastMessage; ?></p>    
                <?php
            }
       } 
        ?>
                </a>
        <?php
    }
}
     ?>
        </div>
    </body>
</html>