<?php

    require_once("includes/init.php");
?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once "/includes/standard_head.php"; ?>    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<title>Messages</title>
	</head>
	<body>
<?php require_once "includes/navbar.php"; ?>
		<!-- Content start -->
		<div class="container">
                <form>
                    <button type="button" class="btn btn-success newconversation">New</button>
                    <p7 class="reciever">To:</p7> <input type="text" class="form-control reciever searchtext" id="searchtext" onfocus="showSearchBox()">
                </form>
                <div id="searchboxdiv">
                </div>
                <div class="container col-sm-3 conversationlist">
                </div>
                <div class="col-sm-9">
                <div class="content col-sm-12">
				    <h3 id="Sender"></h3>
				    <p id="messagecontent"> </p>
                </div>
            <div class="messageform">
                <form action="javascript:sendmsg();" name="pmForm" id="pmForm" method="post">
                    <div class="form-group messageformtextbox col-sm-12">
                    Message: <input type="text" class="form-control" id="message" autocomplete="off">
                    </div>
                    <input name="senderid" id="senderid" type="hidden" value="<?php echo $_SESSION['id'] ?>" />
                    <div class="col-sm-12">
                    <button name="pmSubmit" type="submit" value="Submit" class="btn btn-default" id="sendbtn">Send</button>
                    </div>
                    <p id="confirm"></p>
                </form>
            </div>
            </div>
		</div>
        <?php require_once "/includes/chatbar.php"; ?>
		<!-- Content end -->
        <script>
            $(function() 
            {
                    $.ajax({
                        method: "post",
                        url: "/ajax/getConversations.php",
                        async: true
                        
                    })
                    .done(function(data){  
                     $(".conversationlist").html(data);
                    })
            })
            function sendmsg()
            {
                var sendid = $('#senderid').val();
                var reciever = $('.searchtext').val();
                var msg = $('#message').val();
                if(msg == '' || reciever == '')
                    {
                        document.getElementById("message").placeholder = "No message Written...";
                        if(reciever == '')
                        {
                            $('.reciever').show();
                           document.getElementById("searchtext").placeholder = "Choose recipient...";
                        }
                    }
                else 
                    {
                        $.ajax({
                            method: "post",
                            url: "messageparse.php",
                            async: true,
                            data: { senderid: sendid,reciever: reciever, message: msg }
                        })
                        .done(function(data){
                            $('.reciever').val("");
                            $('#message').val("");
                            $('#confirm').html(data);
                        })
                    }
            }
            $(function() 
            {
                $(".searchtext").on("keydown", function(e) {
                    var searchtext = $(".searchtext").val();
                    
                    
                    $.ajax({
                        method: "post",
                        url: "friendsearch.php",
                        async: true,
                        data: {search: searchtext}
                        
                    })
                    .done(function(data){  
                     $("#searchboxdiv").html(data);
                    })
                    
                })
                
            })
            $(".reciever").on("blur", function(e){
				if($("#searchboxdiv").data("mouseDown") != true){
					hideSearchBox();
				}
			});
			$("#searchboxdiv").on("mousedown", function(e){
				$("#searchboxdiv").data("mouseDown", true);
			});
			$("#searchboxdiv").on("mouseup", function(e){
				$("#searchboxdiv").data("mouseDown", false);
				
			});
            
            $('.newconversation').on('click', function() {
                $('.reciever').show();
            });
            
            function showConversation(withUser,pos)
            {
                $(".content").empty();
                $('.reciever').show();
                var to = document.getElementById(pos).innerHTML;
                $('.searchtext').val(to); 
                 $.ajax({
                        method: "post",
                        url: "showConversation.php",
                        async: true,
                        data: {withUser: withUser}
                        
                    })
                    .done(function(data){  
                     $(".content").html(data);
                     var scroll = $('.content');
                     var height = scroll[0].scrollHeight;
                     scroll.scrollTop(height);
                    })

            }
            function selectFriend(val)
            {
                $('.searchtext').val(val);
                hideSearchBox();
            }
            function showSearchBox()
            {
                document.getElementById("searchboxdiv").style.visibility = 'visible';
            }
            function hideSearchBox()
            {
                document.getElementById("searchboxdiv").style.visibility = 'hidden';
            }
        </script>
<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>