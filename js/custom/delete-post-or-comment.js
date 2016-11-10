function deleteComment(commentID)
{
	$.ajax(
		{
			url: "/ajax/delete-comment.php?id=" + commentID,
			success : function(response)
			{
				if(response)
				{
					//remove the comment from the list without reloading the page
					$("#commentid" + commentID).remove();
				}
			}
		})
}
function deletePost(postID, forumID)
{
	$.ajax(
		{
			url: "/ajax/delete-post.php?id=" + postID,
			success : function(response)
			{
				if(response)
				{
					//Redirect til forum since post does not exist anymore
					window.location.href = "/forum.php?id=" + forumID;
				}
			}
		})
}