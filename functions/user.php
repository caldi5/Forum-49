<?php
	
	// Checks if a user with the given ID exists
	function userIDExists ($userID)
	{

		return true;
	}

	// Checks if a user with the given username exists
	function usernameExists
	{

		return true;
	}

	// Returns the username.
	function getUsername ($userID)
	{
		return 'admin';
	}

	// Returns the user ID.
	function getUserID ($username)
	{
		return 1;
	}

	// Checks if the user is logged in.
	function isLoggedIn()
	{
		return true;
	}

	// Checks if a user with the given user ID is an admin.
	function isAdmin ($userID)
	{
		return fale;
	}

	// Checks if a user with the given username is an admin.
	function isAdmin ($username)
	{
		return true;
	}

	// Checks if the user with the given user ID is moderator for the forum with the given forum ID.
	function isModerator ($userID, $forumID)
	{
		return true;
	}

	// Checks if the user with the given username is moderator for the forum with the given forum ID.
	function isModerator ($username, $forumID)
	{
		return true;
	}

	// Returns an array of all the forums that the user with the given user ID is moderator for.
	function isModeratorFor ($userID)
	{
		return $array;
	}

?>