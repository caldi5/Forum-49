<div class="pull-right conversations">
</div>
<footer class="chatbar">
	<div class="container-fluid">
		<div id="conversation1">
			<a href="#">
				<div class="conversation col-md-2 col-sm-4 col-xs-6 pull-right">
					<div class="conversation-content">
						Anton<button class="close" data-target="#conversation1" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
					</div>
				</div>
			</a>
		</div>	
		<div id="conversation2" class="conversation col-md-2 col-sm-4 col-xs-6 pull-right">
			<div class="conversation-content">
				Andre<button class="close" data-target="#conversation2" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
			</div>
		</div>
		<div id="conversation3" class="conversation col-md-2 col-sm-4 col-xs-6 pull-right">
			<div class="conversation-content">
				Martin<button class="close" data-target="#conversation3" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
			</div>
		</div>
		<div id="conversation4" class="conversation col-md-2 col-sm-4 col-xs-6 pull-right">
			<div class="conversation-content">
				William<button class="close" data-target="#conversation4" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
			</div>
		</div>
	</div>
    <script>
        $(function() 
            {
                    $.ajax({
                        method: "post",
                        url: "/ajax/Conversations.php",
                        async: true    
                    })
                    .done(function(data){  
                     $(".conversations").html(data);
                    })
            })
    </script>
</footer>
