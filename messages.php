<?php
	session_start();
	require_once "includes/dbconn.php"; 
?>
<!DOCTYPE html>
<html>
	<head>
<?php require_once "includes/standard_head.php"; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<title>Messages</title>
	</head>
	<body>
<?php require_once "includes/navbar.php"; ?>
        
<?php
        
?>
		<!-- Content start -->
		<div class="container">
                <form>
                    <button type="button" class="btn btn-success newconversation">New</button>
                    <p7 class="reciever">To:</p7> <input type="text" class="form-control reciever" onfocus="showSearchBox()">
                </form>
                <div id="searchboxdiv">
                </div>
                <div class="list-group col-sm-3 friends">
                    <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">André</h4>
                    <p class="list-group-item-text">Last Message?</p>    
                    </a>
                    <a href="#" class="list-group-item ">
                    <h4 class="list-group-item-heading">William</h4>
                    <p class="list-group-item-text">Last Message?</p>    
                    </a>
                </div>
                <div class="col-sm-9">
                <div class="content">
				    <h3 id="Sender"></h3>
				    <p id="messagecontent"> </p>
                </div>
            <div class="messageform">
                <form>
                    <div class="form-group messageformtextbox col-sm-12">
                    Message: <input type="text" class="form-control" id="message">
                    </div>
                    <div class="col-sm-12">
                    <button type="button" class="btn btn-default sendbtn">Send</button>
                    </div>
                </form>
            </div>
            </div>
		</div>
		<!-- Content end -->
        <script>
            $(function() 
            {
                $(".reciever").on("keydown", function(e) {
                    var searchtext = $(".reciever").val();
                    
                    
                    $.ajax({
                        method: "post",
                        url: "getusers.php",
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