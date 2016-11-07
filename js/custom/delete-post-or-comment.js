function deleteComment(commentID)
{
	$.ajax(
		{
			url: "/ajax/delete-comment.php?id=" + commentID,
			success : function(response)
			{
				if(response)
				{
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
					window.location.href = "/forum.php?id=" + forumID;
				}
			}
		})
}