<?php

	// Returns the name of a category given the category ID
	function getCategoryName ($categoryID)
	{
		return 'Category 1';
	}

	// Returns the name of a forum given the forum ID
	function getForumName ($forumID)
	{
		return 'Forum 1';
	}

	// Returns number of replies a post has gotten given the post ID
	function numberOfReplies ($postID)
	{
		return 5;
	}

	// Returns number of posts in a forum given the forum ID
	function numberOfPosts ($fourmID)
	{
		return 6;
	}

	// Returns the number of unred messages given the user ID
	function numberOfUnreadMessages ($userID)
	{
		return 1;
	}

	// Returns the id of the category that a forum belongs to
	function forumBelongsTo ($forumID)
	{
		return 1;
	}
?>