<!DOCTYPE html>
<html>
    <head>
<?php require_once "includes/standard_head.php"; ?>
    </head>
    <body>
<?php
require_once("includes/init.php");
            $result = $conn->prepare("SELECT DISTINCT username, to_user, from_user FROM users 
                        JOIN messages ON users.id = messages.to_user
                        WHERE from_user = ?");
            $result->bind_param("i", $currentUser->id);
            $result->execute();
            $result->store_result();
            $result->bind_result($name,$toUser,$fromUser);
            $i = 0;
?>
    <div class="list-group col-sm-12 friends">

<?php
if($result->num_rows > 0)
{
    while($result->fetch())
    {
         ?>
                    
                    <a href="#" class="list-group-item" onclick="showConversation('<?php echo $toUser; ?>','<?php echo $i; ?>')">
                    <h4 class="list-group-item-heading" id="<?php echo $i; ?>"><?php echo $name; ?></h4>
        <?php
        $result2 = $conn->prepare("SELECT message, max(timestamp) FROM messages
                        WHERE to_user = ?");
        $result2->bind_param("i", $currentUser->id);
        $result2->execute();
        $result2->store_result();
        $result2->bind_result($lastMessage,$timestamp);
       if($resul2->num_rows > 0)
       {
           while($result2->fetch())
            {
               ?>
                    <p class="list-group-item-text"><?php if(ISSET($lastMessage)) {echo $lastMessage;} ?></p>    
                    </a>
                <?php
            }
                
       
       }   
        $result2->close();
    }
}
            $result3 = $conn->prepare("SELECT DISTINCT username, to_user, from_user FROM users 
                        JOIN messages ON users.id = messages.from_user
                        WHERE to_user = ?");
            $result3->bind_param("i", $currentUser->id);
            $result3->execute();
            $result3->store_result();
            $result3->bind_result($name,$toUser,$fromUser);

if($result3->num_rows > 0)
{
    while($result3->fetch())
    {
         ?>
                    
                    <a href="#" class="list-group-item" onclick="showConversation('<?php echo $toUser; ?>','<?php echo $i; ?>')">
                    <h4 class="list-group-item-heading" id="<?php echo $i; ?>"><?php echo $name; $i++; ?></h4>
        <?php
        $result4 = $conn->prepare("SELECT message, max(timestamp) FROM messages
                        WHERE to_user = ?");
        $result4->bind_param("i", $currentUser->id);
        $result4->execute();
        $result4->store_result();
        $result4->bind_result($lastMessage,$timestamp);
       if($result4->num_rows > 0)
       {
           while($result4->fetch())
            {
               ?>
                    <p class="list-group-item-text"><?php if(ISSET($lastMessage)) {echo $lastMessage;} ?></p>    
                    </a>
                <?php
            }
                
       
       }   
        $result4->close();
    }
}
         
    $result3->close();        
?>
        </div>
    </body>
</html>