function acceptFriendRequest(userID)
{
	$.ajax({url: "/ajax/freindrequest.php?accept=" + userID})
}

function denyFriendRequest(userID)
{
	$.ajax({url: "/ajax/freindrequest.php?deny=" + userID})
}