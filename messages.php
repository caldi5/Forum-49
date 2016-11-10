<?php require_once("includes/init.php"); ?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once "includes/standard_head.php"; ?>    
		<title>Messages</title>
	</head>
	<body>
<?php require_once "includes/navbar.php"; ?>
		
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<!-- I am the searchbox and To Form -->
    	<div class="col-sm-12">
      	<div class="col-sm-2 to">
        	<form>
          	<p7>To:</p7><input type="text" placeholder="Username" class="form-control reciever searchtext" id="searchtext" onfocus="showSearchBox()">  
          </form>
          <div class="col-sm-1" id="searchboxdiv"> </div>
        </div>
      </div>
      <!-- Searchbox end -->
			
			<!-- Conversations are loaded here with AJAX -->
			<div class="col-sm-3">
      	<h4 class="pad">Conversations</h4>
					
        	<div class="container col-sm-12 conversationlist"> </div>
      </div>
      <!-- Conversations load end -->
			
			<!-- Messages are loaded here with AJAX-->
			<div class="col-sm-9">
      	<h4 class="pad">Messages</h4>
        <div class="content col-sm-12">
					
				  <p id="messagecontent"> </p>
        </div>
        
				<!-- The messageform -->
				<div class="messageform">
        	<form action="javascript:sendmsg();" name="pmForm" id="pmForm" method="post">
   					<div class="form-group messageformtextbox col-sm-12">
            	Message: <input type="text" class="form-control" id="message" autocomplete="off">
            </div>
            	<div class="col-sm-12">
              	<button name="pmSubmit" type="submit" value="Submit" class="btn btn-default" id="sendbtn">Send</button>
              </div>
           </form>
        </div>
       </div>
			<!-- Messages load end -->
			
		</div>
		<!-- Content end -->

<?php require_once("includes/standard_footer.php"); ?>	
        <script>
					
					// getConversations.php is called to load all conversations
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
						
             function updateConversation(withUser)
            {
                $(".content").empty();
                 $.ajax({
                        method: "post",
                        url: "ajax/updateConversationmessage.php",
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
						// send message to the user whose name is in the To: box
            function sendmsg()
            {
                var reciever = $('.searchtext').val();
                var msg = $('#message').val();
                if(msg == '' || reciever == '')
                    {
                        document.getElementById("message").placeholder = "No message Written...";
                        if(reciever == '')
                        {
                           document.getElementById("searchtext").placeholder = "Choose recipient...";
                        }
                    }
                else 
                    {
                        $.ajax({
                            method: "post",
                            url: "/ajax/messageparse.php",
                            async: true,
                            data: { reciever: reciever, message: msg }
                        })
                        .done(function(data){
                            $('#message').val("");
                        })
                        updateConversation(reciever);
                    }
            }
            // Searchbox functionality, when you press a key in the searchbox this is run
            $(function() 
            {
                $(".searchtext").on("keyup", function(e) {
                    var searchtext = $(".searchtext").val();
                    
                    
                    $.ajax({
                        method: "post",
                        url: "/ajax/friendsearch.php",
                        async: true,
                        data: {search: searchtext}
                        
                    })
                    .done(function(data){  
                     $("#searchboxdiv").html(data);
                    })
                    
                })
                
            })
            // hides the searchbox if you are not clicking in the searchbox
            $(".reciever").on("blur", function(e){
				if($("#searchboxdiv").data("mouseDown") != true){
					hideSearchBox();
				}
			});
            //hides the box if you have completed a click mousedown + mouseup to make sure the anchor tag onclick is triggered
			$("#searchboxdiv").on("mousedown", function(e){
				$("#searchboxdiv").data("mouseDown", true);
			});
			$("#searchboxdiv").on("mouseup", function(e){
				$("#searchboxdiv").data("mouseDown", false);
				
			});
            
            //showconversations between the user and selected user, pos for filling the searchbox with the appropriate recipient name. WithUser is id of recipient
            function showConversation(withUser,pos)
            {
                $(".content").empty();
                $('.reciever').show();
                var to = document.getElementById(pos).innerHTML;
                $('.searchtext').val(to); 
                 $.ajax({
                        method: "post",
                        url: "ajax/showConversation.php",
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
            //Triggered by clicking on the name of a friend when you search for friends
            function selectFriend(val)
            {
                $('.searchtext').val(val);
                hideSearchBox();
            }
            //show/hide searchbox 
            function showSearchBox()
            {
                document.getElementById("searchboxdiv").style.visibility = 'visible';
            }
            function hideSearchBox()
            {
                document.getElementById("searchboxdiv").style.visibility = 'hidden';
            }
            </script>
            <?php if(isset($_GET['user']))
            {
                ?>
                    <script>
                    $('.searchtext').val('<?php echo $_GET['user'] ?>');
                    updateConversation('<?php echo $_GET['user'] ?>');
                    </script>
                <?php
            } 
            ?>
        </script>
	</body>
</html>