<!--

	This page contains the live messaging system for logged in users.
	The users can send and receive messages from their friends.

-->

<script type="text/javascript">

	// This function catches the submit event when a user sends a new message.
	$(document).on('submit','form.chattForm',function(){
		var form = this.id;
		var to = $("#"+form+" #to").val();
		var text = $("#"+form+" #newMessage").val();
		
		// AJAX that sends the message and calls a function that checks if there are any new messages.
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				getNewMessages();
			}
		};
		xmlhttp.open("GET", "/ajax/sendMessage.php?to="+to+"&message="+text, true);
		xmlhttp.send();

		// Empties the input field from which the message was sent.
		$("#"+form+" #newMessage").val("");

		// Return false keeps the page from refreshing.
		return false;
	});

	// This funcitons keeps track of what chat windows were open on the page before.
	$(document).ready(function(){
		// Checks if the list of friends were open.
		if ($.cookie('cn') != undefined)
		{
			var value = $.cookie('cn');
			if (value == 'open')
			{
				openConversationPartners();
			}
		}

		// Really ugly code x4 that keeps track of every chat window.
		if (($.cookie('c0') != undefined))
		{
			// Splits the cookie into an array.
			var cookie = $.cookie('c0').split('-');

			// cid is the conversation partners user id.
			var cid = cookie[0];

			// Conversation partners username.
			var cname = cookie[1];

			// ctype can be maxi or mini depending on if the chat window i supposed to be maximized or minimized.
			var ctype = cookie[2];

			var id = openConversation(cid, cname);
			if (ctype != 'maxi')
			{
				minimizeConversation(id, cid);
			}
		}

		if (($.cookie('c1') != undefined))
		{
			var cookie = $.cookie('c1').split('-');
			var cid = cookie[0];
			var cname = cookie[1];
			var ctype = cookie[2];
			var id = openConversation(cid, cname);
			if (ctype != 'maxi')
			{
				minimizeConversation(id, cid);
			}
		}

		if (($.cookie('c2') != undefined))
		{
			var cookie = $.cookie('c2').split('-');
			var cid = cookie[0];
			var cname = cookie[1];
			var ctype = cookie[2];
			var id = openConversation(cid, cname);
			if (ctype != 'maxi')
			{
				minimizeConversation(id, cid);
			}
		}

		if (($.cookie('c3') != undefined))
		{
			var cookie = $.cookie('c3').split('-');
			var cid = cookie[0];
			var cname = cookie[1];
			var ctype = cookie[2];
			var id = openConversation(cid, cname);
			if (ctype != 'maxi')
			{
				minimizeConversation(id, cid);
			}
		}
	});

	// Sets all of the messages in a conversation to read.
	function setRead(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				return true;
			}
		};
		xmlhttp.open("GET", "/ajax/setRead.php?id="+id, true);
		xmlhttp.send();
	}

	// Checks to see if there are any new messages in any of the open conversations.
	function getNewMessages()
	{
		// Takes an array of all the elements with the class conversation, reverses it, and the does a foreach.
		$($(".conversation").get().reverse()).each(function (index, value){
			// Partner user id and when the last message was received.
			var userid = $(this).data("userid");
			var lastMessage = $(this).attr("data-last");

			if (lastMessage != '')
			{
				var messages = new Array();
				var xmlhttp = new XMLHttpRequest();

				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
					messages = JSON.parse(this.responseText);

					// Updates the data-last attribute.
					$("#conversation"+index).attr("data-last", messages[0].created_at);

					// Loops thru and outputs all of the new messages.
					$.each(messages, function(index2, value2){
						if (value2.type == "sent")
						{
							$("#conversation"+index+" .conversationText .conversationMessages").append("<div class='message'><div title='"+value2.showTime+"' class='messageSent'>"+value2.message+"</div></div>");
						}
						else
						{
							$("#conversation"+index+" .conversationText .conversationMessages").append("<div class='message'><div title='"+value2.showTime+"' class='messageReceived'>"+value2.message+"</div></div>");
						}
						$("#conversation"+index+" .conversationFooterMini").css("background-color", "#ff3B3F");
						$("#conversation"+index+" .conversationFooterMini").css("color", "white");
					})

					// Scrolls the chat window to the bottom.
					var convo = $("#conversation"+index+" .conversationText .conversationMessages");
					convo.scrollTop(convo.prop("scrollHeight"))
				}
				};
				xmlhttp.open("GET", "/ajax/getMessages.php?id="+userid+"&t="+lastMessage, true);
				xmlhttp.send();
				
			}
		});
	}

	// Opens the list of friends.
	function openConversationPartners()
	{
		var people = new Array();
		//minimizeAllConversations();
		//$(".conversation").last().css("margin-right", "10px");

		$("#startNewConversation").empty();
		
		$("#startNewConversation").append("<div class='conversationPartners'>");
		$("#startNewConversation .conversationPartners").append("<div class='newConversationHeader'>");
		$("#startNewConversation .newConversationHeader").append("<div class='startNewConversationToggle' onclick='closeConversationPartners()'>-</div>");
		$("#startNewConversation .startNewConversationToggle").css("width", "200px");
		$("#startNewConversation .startNewConversationToggle").css("text-align", "left");
		$("#startNewConversation .startNewConversationToggle").css("height", "35px");
		$("#startNewConversation .conversationPartners").append("<ul>");
		$("#startNewConversation").css("width", "200px");
		$("#startNewConversation").css("margin-right", "0px");
		$("#startNewConversation").append("<input onkeyup='updateConversationPartners(this.value)' class='covnoPartnerForm' type='text'>");

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				people = JSON.parse(this.responseText);

				$.each(people, function(index, value){
					$("#startNewConversation .conversationPartners ul").append("<a href='#' onclick='openConversation("+value.partnerID+", \""+value.partnerUsername+"\")'><li>"+value.partnerUsername+"</li></a>");
				});
			}
		};
		xmlhttp.open("GET", "/ajax/getConversationsLive.php", true);
		xmlhttp.send();

		$.cookie('cn', 'open');
	}

	// Runs every time you type something in the serch box.
	function updateConversationPartners(term)
	{
		// Updates the list of friends to a list that mathes your search term.
		$(".conversationPartners ul").empty();
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				people = JSON.parse(this.responseText);

				// Outputs all of the matching friends.
				$.each(people, function(index, value){
					$("#startNewConversation .conversationPartners ul").append("<a href='#' onclick='openConversation("+value.partnerID+", \""+value.partnerUsername+"\")'><li>"+value.partnerUsername+"</li></a>");
				});
			}
		};
		xmlhttp.open("GET", "/ajax/getConversationsLive.php?n="+term, true);
		xmlhttp.send();
	}

	// Closes the list of friends.
	function closeConversationPartners()
	{
		//$(".conversation").last().css("margin-right", "10px");

		$("#startNewConversation").empty();
		$("#startNewConversation").css("width", "35px");
		$("#startNewConversation").css("margin-right", "10px");
		$("#startNewConversation").append("<div class='startNewConversationToggle' onclick='openConversationPartners()''>+</div>");
		$.cookie('cn', 'closed')
	}

	// Depricated. Minimizes all of the open conversations.
	function minimizeAllConversations()
	{
		$(".conversation .conversationFooterMaxi").each(function (index, value) {
			minimizeConversation(index+1);
		});
	}

	// Minimize a specific conversation.
	function minimizeConversation(id, partner)
	{
		// Id is the id of the conversation. Partner is the user id of the conversation partner.

		var name = $("#conversation"+id+" .conversationHeaderName h4").text();

		// Sets a cookie.
		$.cookie("c"+id, partner+'-'+name+'-mini');

		// Minimize
		$("#conversation"+id).empty();
		$("#conversation"+id).append("<div class='conversationFooterMini'>");
		$("#conversation"+id+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+id+", "+partner+")'><h4 class='conversationPartnerName'>"+name+"</h4></div><div class='conversationFooterClose' onclick='closeConversation("+id+")'><span>X</span></div>");
	}

	// Maximize a conversatoin.
	function maximizeConversation(id, partner)
	{
		// Id is the id of the conversation. Partner is the user id of the conversation partner.

		var name = $("#conversation"+id+" .conversationFooterName h4").text();
		var messages = new Array();

		// Sets a cookie to keep track of this conversation windows current state.
		$.cookie("c"+id, partner+'-'+name+'-maxi');

		// Maximize.
		$("#conversation"+id).empty();
		$("#conversation"+id).append("<div class='conversationFooterMaxi'><form class='chattForm' id='form"+id+"'><input autocomplete='off' type='text' id='newMessage' class='conversationWriteMessage'><input type='hidden' id='to' value='"+partner+"'></form></div>");
		$("#conversation"+id).append("<div class='conversationText'>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationHeader'><div class='conversationHeaderName' onclick='minimizeConversation("+id+", "+partner+")''><h4>"+name+"</h4 class='conversationPartnerName'></div><div class='conversationHeaderClose' onclick='closeConversation("+id+")'><span>X</span></div></div>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationMessages'>");

		// Get all of the messages that belongs to this conversation.
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				messages = JSON.parse(this.responseText);

				$("#conversation"+id).attr("data-last", messages[0].created_at);

				// Output every message.
				$.each(messages, function(index, value){
					if (value.type == "sent")
					{
						$("#conversation"+id+" .conversationText .conversationMessages").prepend("<div class='message'><div title='"+value.showTime+"' class='messageSent'>"+value.message+"</div></div>");
					}
					else
					{
						$("#conversation"+id+" .conversationText .conversationMessages").prepend("<div class='message'><div title='"+value.showTime+"' class='messageReceived'>"+value.message+"</div></div>");
					}
					var convo = $("#conversation"+id+" .conversationText .conversationMessages");
					convo.scrollTop(convo.prop("scrollHeight"))
				})
			};
		};
		xmlhttp.open("GET", "/ajax/getMessages.php?id="+partner, true);
		xmlhttp.send();
		setRead(partner);
	}

	// Close a conversation.
	function closeConversation(id)
	{
		// Here we need to reset all of the cookies.
		$.removeCookie('c0');
		$.removeCookie('c1');
		$.removeCookie('c2');
		$.removeCookie('c3');

		// Remove the conversation.
		$("#conversation"+id).remove();

		// Update all of the other conversations.
		$($(".conversation").get().reverse()).each(function(index, value){
			var userid = $(this).data("userid");
			$(this).attr("id", "conversation"+index);
			$("#conversation"+index+" .conversationFooterName").attr("onclick", "maximizeConversation("+index+", "+userid+")");
			$("#conversation"+index+" .conversationHeaderName").attr("onclick", "minimizeConversation("+index+", "+userid+")");
			$("#conversation"+index+" .conversationHeaderClose").attr("onclick", "closeConversation("+index+")");
			$("#conversation"+index+" .conversationFooterClose").attr("onclick", "closeConversation("+index+")");
			
			// Fix the new cookies.
			if ($("#conversation"+index+" .conversationFooterName h4").text() != "")
			{
				var name = $("#conversation"+index+" .conversationFooterName .conversationPartnerName").text();
				$.cookie('c'+index, userid+'-'+name+'-mini');
			}
			
			if ($("#conversation"+index+" .conversationHeaderName h4").text() != "")
			{
				var name = $("#conversation"+index+" .conversationHeaderName h4").text();
				$.cookie('c'+index, userid+'-'+name+'-maxi');
			}
		});
	}
	
	// Opens a new conversation.
	function openConversation(id, partnerName)
	{
		// Id is the users user id.

		var conversationsOpen = $(".conversation").length;

		// You can only have 4 conversations open att the same time.
		if(conversationsOpen >= 4)
		{
			alert("Please close a conversation before opening a new one.");
		}
		else
		{
			// Creates the new converasation.
			$("#convContainer").prepend("<div class='conversation' id='conversation"+(conversationsOpen)+"' data-userid='"+id+"' data-last='' >");
			$("#conversation"+(conversationsOpen)).prepend("<div class='conversationFooterMini'>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+(conversationsOpen)+", "+id+")''><h4>"+partnerName+"</h4></div>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterClose' onclick='closeConversation("+id+")'><span onclick='closeConversation("+(conversationsOpen)+")'>X</span></div>");

			maximizeConversation(conversationsOpen,id);
			$(".conversation").last().css("margin-right", "10px");
		}

		return conversationsOpen;
	}

	setInterval(getNewMessages, 1500);
</script>

<div class="convWrapper hidden-xs hidden-sm">
	<div class="convContainer" id="convContainer">

		<div class="startNewConversation" id="startNewConversation">
			<div class="startNewConversationToggle" onclick="openConversationPartners()">+</div>
		</div>
	</div>
</div>