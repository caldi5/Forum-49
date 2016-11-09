<?php

	require_once("includes/init.php");

	/*

		Flow displays all of the current events on the site.

	*/
	
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include("includes/standard_head.php"); ?>
		<title>Flow</title>

		<script type="text/javascript">
			// last keeps track of the last time something happened.
			var last = parseInt(<?php echo time(); ?>, 10);
			var data = new Array();

			// Function that checks, and gets, new updates by using AJAX.
			function update() 
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
						window.data = JSON.parse(this.responseText);
						window.last = data[data.length-1].created_at;
						//$("#liveContainer").prepend(this.responseText);
						$.each(data, function(index, value){
							// If a new thing has happpend we set last to the timestamp of that thing.
							if (index == window.data.length-1)
							{
								window.last = value.created_at;
							}

							var title;

							if (value.type == "post")
							{
								title = value.username+" posted "+value.post+" in "+value.forum;
							}
							else if (value.type == "comment")
							{
								title = value.username+" commented on "+value.post+" in "+value.forum;
							}

							$("<div class='row'>").prependTo("#liveContainer")
							.html("<a href='post.php?id="+value.postID+"'>								<div class='col-lg-12 liveContent'><div class='col-lg-10'><h4 class='liveTitle'>"+title+"<h4><p class='liveText'>"+value.text+"</p></div><div class='col-lg-2'><span class='post-time'>"+value.date+"</span></div></div></a></div>");
						});
					}
				};
				xmlhttp.open("GET", "/ajax/updateFlow.php?t=" + window.last, true);
				xmlhttp.send();
			};

			// Runs the function every 5 seconds.
			setInterval(update, 5000);
		</script>
	</head>
	<body>
		<?php include("includes/navbar.php"); ?>

		<div class="container" id="container">
			<h1>Flow</h1>

			<div id="liveContainer">
			</div>
		</div>

		<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>