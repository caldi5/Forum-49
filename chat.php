<script type="text/javascript">




	function openConversationPartners()
	{
		var people = new Array();
		minimizeAllConversations();
		$("#startNewConversation").empty();
		$("#startNewConversation").append("<div class='startNewConversationToggle' onclick='closeConversationPartners()'>-</div>");
		$("#startNewConversation").append("<div class='conversationPartners'>");
		$("#startNewConversation .conversationPartners").append("<ul>");

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				people = JSON.parse(this.responseText);

				$.each(people, function(index, value){
					$("#startNewConversation .conversationPartners ul").append("<li><a href='#' onclick='openConversation("+value.partnerID+", \""+value.partnerUsername+"\")'>"+value.partnerUsername+"</a></li>");
				});
			}
		};
		xmlhttp.open("GET", "getConversations.php", true);
		xmlhttp.send();
	}

	function closeConversationPartners()
	{
		$("#startNewConversation").empty();
		$("#startNewConversation").append("<div class='startNewConversationToggle' onclick='openConversationPartners()''>+</div>");

	}

	function minimizeAllConversations()
	{
		$(".conversation .conversationFooterMaxi").each(function (index, value) {
			minimizeConversation(index+1);
		});
	}

	function minimizeConversation(id, partner)
	{
		var name = $("#conversation"+id+" .conversationHeaderName h4").text();
		
		$("#conversation"+id).empty();
		$("#conversation"+id).append("<div class='conversationFooterMini'>");
		$("#conversation"+id+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+id+", "+partner+")'><h4>"+name+"</h4></div><div class='conversationFooterClose'><span onclick='closeConversation("+id+")''>X</span></div>");
	}

	function maximizeConversation(id, partner)
	{
		closeConversationPartners();

		var name = $("#conversation"+id+" .conversationFooterName h4").text();
		var messages = new Array();

		$("#conversation"+id).empty();
		$("#conversation"+id).append("<div class='conversationFooterMaxi'><form action='#' onsubmit='sendNewMessage()' id='userID'><input type='text' name='newMessage' class='conversationWriteMessage'></form></div>");
		$("#conversation"+id).append("<div class='conversationText'>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationHeader'><div class='conversationHeaderName' onclick='minimizeConversation("+id+", "+partner+")''><h4>"+name+"</h4></div><div class='conversationHeaderClose'><span onclick='closeConversation("+id+")''>X</span></div></div>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationMessages'>");

		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				messages = JSON.parse(this.responseText);

				$.each(messages, function(index, value){
					if (value.type == "sent")
					{
						$("#conversation"+id+" .conversationText .conversationMessages").prepend("<div class='message'><div class='messageSent'>"+value.message+"</div></div>");
					}
					else
					{
						$("#conversation"+id+" .conversationText .conversationMessages").prepend("<div class='message'><div class='messageReceived'>"+value.message+"</div></div>");
					}
					var convo = $("#conversation"+id+" .conversationText .conversationMessages");
					convo.scrollTop(convo.prop("scrollHeight"))
				})
			};
		};
		xmlhttp.open("GET", "getMessages.php?id="+partner, true);
		xmlhttp.send();

	}

	function closeConversation(id)
	{
		$("#conversation"+id+"").remove();
	}
	
	function openConversation(id, partnerName)
	{
		var partner = id;
		var conversationsOpen = $(".conversation").length;

		if(conversationsOpen >= 4)
		{
			alert("Please close a conversation before opening a new one.");
		}
		else
		{
			$("#convContainer").prepend("<div class='conversation' id='conversation"+(conversationsOpen)+"'>");
			$("#conversation"+(conversationsOpen)).prepend("<div class='conversationFooterMini'>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+(conversationsOpen)+", "+id+")''><h4>"+partnerName+"</h4></div>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterClose'><span onclick='closeConversation("+(conversationsOpen)+")'>X</span></div>");
		}

	}

</script>

<div class="convWrapper">

	<div class="convContainer" id="convContainer">
		<!--<div class="conversation" id="conversation1">
				<div class="conversationFooterMaxi">
					<form action="#" onsubmit="sendNewMessage()" id="userID">
						<input type="text" name="newMessage" class="conversationWriteMessage">
					</form>
				</div>


			<div class="conversationText">
				<div class="conversationHeader">
					<div class="conversationHeaderName" onclick="minimizeConversation(1)">
						<h4>André</h4>
					</div>
					<div class="conversationHeaderClose">
						<span onclick="closeConversation(1)">X</span>
					</div>
				</div>

				<div class="conversationMessages">
					<div class="message">
						<div class="messageSent">
							hejsan
						</div>
					</div>
					<div class="message">
						<div class="messageSent">
							hejsan
						</div>
					</div>
					<div class="message">
						<div class="messageReceived">
							hejsan
						</div>
					</div>
					<div class="message">
						<div class="messageReceived">
							hejsan
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="conversation" id="conversation2">
			<div class="conversationFooterMini">
				<div class="conversationFooterName" onclick="maximizeConversation(2, 1)">
					<h4>Anton</h4>
				</div>
				<div class="conversationFooterClose">
					<span onclick="closeConversation(2)">X</span>
				</div>
			</div>
		</div>-->

		<div class="startNewConversation" id="startNewConversation">
			<div class="startNewConversationToggle" onclick="openConversationPartners()">+</div>
		</div>
	</div>
</div>
