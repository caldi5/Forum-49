<script type="text/javascript">

	$(document).on('submit','form.chattForm',function(){
		var form = this.id;

		var to = $("#"+form+" #to").val();
		var text = $("#"+form+" #newMessage").val();



		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				$("#"+form+" #newMessage").val("");
				getNewMessages();
			}
		};
		xmlhttp.open("GET", "ajax/sendMessage.php?to="+to+"&message="+text, true);
		xmlhttp.send();
		
		return false;
	});

	$(document).ready(function(){
		if ($.cookie('convos'))
		{
			var conv = $.cookie('convos');
			var arr = conv.split(",");
			$.cookie('convos', "")
			$(arr).each(function(index, value){
				var cookie = value.split('-');
				var name = cookie[1];
				var id = cookie[0]
				openConversation(id, name);
			});
		}
	});

	function getNewMessages()
	{
		$($(".conversation").get().reverse()).each(function (index, value){
			var userid = $(this).data("userid");
			var lastMessage = $(this).attr("data-last");

			if (lastMessage != '')
			{
				var messages = new Array();
				var xmlhttp = new XMLHttpRequest();
				var number = index;

				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
					messages = JSON.parse(this.responseText);

					$("#conversation"+number).attr("data-last", messages[0].created_at);
					$.each(messages, function(index2, value2){
						if (value2.type == "sent")
						{
							$("#conversation"+index+" .conversationText .conversationMessages").append("<div class='message'><div class='messageSent'>"+value2.message+"</div></div>");
						}
						else
						{
							$("#conversation"+index+" .conversationText .conversationMessages").append("<div class='message'><div class='messageReceived'>"+value2.message+"</div></div>");
							$("#conversation"+index+" .conversationFooterName span").remove();
							$("#conversation"+index+" .conversationFooterName").append("<span class='red-text'> - NEW</span>");
						}
					})

					var convo = $("#conversation"+index+" .conversationText .conversationMessages");
					convo.scrollTop(convo.prop("scrollHeight"))
				}
				};
				xmlhttp.open("GET", "getMessages.php?id="+userid+"&t="+lastMessage, true);
				xmlhttp.send();
				
			}
		});
	}

	function openConversationPartners()
	{
		var people = new Array();
		//minimizeAllConversations();
		$(".conversation").last().css("margin-right", "10px");

		$("#startNewConversation").empty();
		
		$("#startNewConversation").append("<div class='conversationPartners'>");
		$("#startNewConversation .conversationPartners").append("<div class='newConversationHeader'>");
		$("#startNewConversation .newConversationHeader").append("<div class='startNewConversationToggle' onclick='closeConversationPartners()'>-</div>");
		$("#startNewConversation .startNewConversationToggle").css("width", "100%");
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
		xmlhttp.open("GET", "getConversations.php", true);
		xmlhttp.send();
	}

	function updateConversationPartners(term)
	{
		$(".conversationPartners ul").empty();
		

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				people = JSON.parse(this.responseText);

				$.each(people, function(index, value){
					$("#startNewConversation .conversationPartners ul").append("<a href='#' onclick='openConversation("+value.partnerID+", \""+value.partnerUsername+"\")'><li>"+value.partnerUsername+"</li></a>");
				});
			}
		};
		xmlhttp.open("GET", "getConversations.php?n="+term, true);
		xmlhttp.send();
	}

	function closeConversationPartners()
	{
		$(".conversation").last().css("margin-right", "10px");

		$("#startNewConversation").empty();
		$("#startNewConversation").css("width", "35px");
		$("#startNewConversation").css("margin-right", "10px");
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
		$("#conversation"+id+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+id+", "+partner+")'><h4 class='conversationPartnerName'>"+name+"</h4></div><div class='conversationFooterClose' onclick='closeConversation("+id+")'><span>X</span></div>");

		if ($.cookie('convos'))
		{
			var conv = $.cookie('convos');
			var userid = $("#conversation"+id).data("userid");
			var newArr = new Array();

			var arr = conv.split(",");
			$(arr).each(function(index, value){
				var cookie = value.split('-');
				var cid = cookie[0];
				var cname = cookie[1];
				var ctype = cookie[2];


				if (userid != cid)
				{
					newArr.push(value);
				}
				else
				{
					newArr.push(cid+'-'+cname+'-mini');
				}
			});
			conv = newArr.join(',');
			$.cookie('convos', conv);
		}
	}

	function maximizeConversation(id, partner)
	{
		//closeConversationPartners();

		var name = $("#conversation"+id+" .conversationFooterName h4").text();
		var messages = new Array();

		$("#conversation"+id).empty();
		$("#conversation"+id).append("<div class='conversationFooterMaxi'><form class='chattForm' id='form"+id+"'><input autocomplete='off' type='text' id='newMessage' class='conversationWriteMessage'><input type='hidden' id='to' value='"+partner+"'></form></div>");
		$("#conversation"+id).append("<div class='conversationText'>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationHeader'><div class='conversationHeaderName' onclick='minimizeConversation("+id+", "+partner+")''><h4>"+name+"</h4 class='conversationPartnerName'></div><div class='conversationHeaderClose' onclick='closeConversation("+id+")'><span>X</span></div></div>");
		$("#conversation"+id+" .conversationText").append("<div class='conversationMessages'>");

		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.responseText != false) {
				messages = JSON.parse(this.responseText);

				$("#conversation"+id).attr("data-last", messages[0].created_at);

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

		if ($.cookie('convos'))
		{
			var conv = $.cookie('convos');
			var userid = $("#conversation"+id).data("userid");
			var newArr = new Array();

			var arr = conv.split(",");
			$(arr).each(function(index, value){
				var cookie = value.split('-');
				var cid = cookie[0];
				var cname = cookie[1];
				var ctype = cookie[2];


				if (userid != cid)
				{
					newArr.push(value);
				}
				else
				{
					newArr.push(cid+'-'+cname+'-maxi');
				}
			});
			conv = newArr.join(',');
			$.cookie('convos', conv);
		}
	}

	function closeConversation(id)
	{
		if ($.cookie('convos'))
		{
			var conv = $.cookie('convos');
			var userid = $("#conversation"+id).data("userid");
			var newArr = new Array();

			var arr = conv.split(",");
			$(arr).each(function(index, value){
				var cookie = value.split('-');
				var cid = cookie[0]

				if (userid != cid)
				{
					newArr.push(value);
				}
			});
			conv = newArr.join(',');
			$.cookie('convos', conv);
		}

		var margin = $("#conversation"+id).css("margin-right");
		$("#conversation"+id).remove();
		$($(".conversation").get().reverse()).each(function(index, value){
			var userid = $(this).data("userid");
			$(this).attr("id", "conversation"+index);
			$("#conversation"+index+" .conversationFooterName").attr("onclick", "maximizeConversation("+index+", "+userid+")");
			$("#conversation"+index+" .conversationHeaderName").attr("onclick", "minimizeConversation("+index+", "+userid+")");
			$("#conversation"+index+" .conversationHeaderClose").attr("onclick", "closeConversation("+index+")");
			$("#conversation"+index+" .conversationFooterClose").attr("onclick", "closeConversation("+index+")");
		});
		$("#conversation"+id).last().css("margin-right", margin);
	}
	
	function openConversation(id, partnerName)
	{
		var partner = id;
		var conversationsOpen = $(".conversation").length;

		if ($.cookie('convos'))
		{
			var conv = $.cookie('convos')+','+id+'-'+partnerName+'-maxi';
			$.cookie('convos', conv);
			var arr = conv.split(",");
		}
		else
		{
			var conv = id+'-'+partnerName+'-maxi';
			$.cookie('convos', conv);
		}
		


		if(conversationsOpen >= 4)
		{
			alert("Please close a conversation before opening a new one.");
		}
		else
		{
			$("#convContainer").prepend("<div class='conversation' id='conversation"+(conversationsOpen)+"' data-userid='"+partner+"' data-last='' >");
			$("#conversation"+(conversationsOpen)).prepend("<div class='conversationFooterMini'>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterName' onclick='maximizeConversation("+(conversationsOpen)+", "+id+")''><h4>"+partnerName+"</h4></div>");
			$("#conversation"+(conversationsOpen)+" .conversationFooterMini").append("<div class='conversationFooterClose' onclick='closeConversation("+id+")'><span onclick='closeConversation("+(conversationsOpen)+")'>X</span></div>");

			maximizeConversation(conversationsOpen,id);
			$(".conversation").last().css("margin-right", "10px");
		}


	}

	setInterval(getNewMessages, 5000);
</script>

<div class="convWrapper hidden-xs hidden-sm">
	<div class="convContainer" id="convContainer">

		<div class="startNewConversation" id="startNewConversation">
			<div class="startNewConversationToggle" onclick="openConversationPartners()">+</div>
		</div>
	</div>
</div>