<?php

	require_once("includes/init.php");

	try 
	{
		$forum = new forum($_GET['id']);
	}
	catch (Exception $e) 
	{
		header('Location: index.php');
		die;
	}

	if(!$currentUser->isLoggedIn() && !$forum->guestAccess)
	{
		header('Location: index.php');
		die;
	}

	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	// Number of posts we want to display per page.
	$posts_per_page = 10;
	$postsOffset = ($posts_per_page*$page)-$posts_per_page;
	$count = $forum->getNumberOfPosts();
	$posts = $forum->getPosts($posts_per_page, $postsOffset);

	$category = new category($forum->category);

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include("includes/standard_head.php"); ?>
		<title><?php echo $forum->name; ?></title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>		
		<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<h3>
<?php echo '<a href="category.php?id='.$category->id.'" class="category-title">'.$category->name.'</a> / '.$forum->name; ?>
			</h3>
			<div class="posts">
<?php
	// Buttons for for posting, administrating and moderating.
	if ($currentUser->isAdmin())
	{
		echo '<div class="actions">';
		echo '<a href="new-post.php?forum='.$forum->id.'" class="btn btn-default" role="button">New Post</a>';
		echo '<a href="#" class="btn btn-default" role="button">Moderate</a>';
		echo '<a href="admin/editforum.php?id='.$forum->id. '" class="btn btn-default" role="button">Administrate</a>';
		echo '</div>';
	}
	elseif ($currentUser->isModerator($_GET['id']))
	{
		echo '<div class="actions">';
		echo '<a href="new-post.php?forum='.$forum->id.'" class="btn btn-default" role="button">New Post</a>';
		echo '<a href="moderate.php" class="btn btn-default" role="button">Moderate</a>';
		echo '</div>';
	}
	elseif ($currentUser->isLoggedIn())
	{
		echo '<div class="actions">';
		echo '<a href="new-post.php?forum='.$forum->id.'" class="btn btn-default" role="button">New Post</a>';
		echo '</div>';
	}
		
	// Checks to see if there's actually any posts.
	if (!empty($posts))
	{
		foreach ($posts as $post)
		{
			$user = new user($post->creator);
			echo '<div class="row">';
			echo '<a href="post.php?id=' . $post->id . '">';
			echo '<div class="col-lg-12 post">';
			echo '<div class="col-lg-10">';
			echo '<h4 class="post-title">' . $post->title . '</h4>';

			// Nice admin and moderator colors for the "creator" text. Blue is for moderator and red is for admin.
			if ($user->isAdmin())
			{
				echo '<p class="post-poster"><span class="admin">'.$user->username. ' [A]</span></p>';
			}
			/*elseif ($user->isModerator())
			{
				echo '<p class="post-poster"><span class="mod">'.$user->username.' [M]</span></p>';
			}*/
			else
			{
				echo '<p class="post-poster">'.$user->username.'</p>';
			}
			echo '<span class="post-time"> - '.date('H:i d/m/y', $post->createdAt).'</span>';
			echo '</div>';
			echo '<div class="col-lg-1">';
			echo '<p>Replies:<br>'.$post->getNumberOfComments().'</p>';
			echo '</div>';
			echo '<div class="col-lg-1">';
			echo '<p>Views:<br>'.$post->getNumberOfviews().'</p>';
			echo '</div>';
			echo '</div>';
			echo '</a>';
			echo '</div>';
		}
	}
	else
	{
		// Here we check if there's post in the forum, or if the user has tried goind to a page that doesn't have any results.
		if ($count == 0)
		{
			// We check if it's an empty forum.
			echo '<div class="alert alert-info">';
			echo '<h3><strong>Sorry!</strong> There\'s no posts in this forum just yet!</h3>';
			echo '</div>';

		}
		elseif ($page > ceil($count / $posts_per_page))
		{
			// Here we check if the user has tried going to a page without any posts.
			echo '<div class="alert alert-info">';
			echo '<h3><strong>Sorry!</strong> This page does not exist!</h3>';
			echo '</div>';
		}
	}
?>
			</div>
<?php

	// Checks to see if the number of posts exceeds the number of posts we allow per page. In that case we will need pagination.
	if ($count > $posts_per_page)
	{
		echo '<nav aria-label="Page navigation">';
		echo '<div class="row">';
		echo '<ul class="pagination">';
		if ($page == 1)
		{
			echo '<li class="page-item disabled">';
			echo '<a class="page-link" href="#" aria-label="Previous">';
		}
		else 
		{
			echo '<li class="page-item">';
			echo '<a class="page-link" href="forum.php?id='.$_GET['id'].'&page='.($page-1).'" aria-label="Previous">';
		}
		echo '<span aria-hidden="true">&laquo;</span>';
		echo '</a></li>';

		// Number of pages we need, rounded up.
		$pages = ceil($count / $posts_per_page);

		for ($i = 1; $i <= $pages; $i++)
		{
			// Makes the current page active.
			if ($i == $page)
				echo '<li class="page-item active"><a class="page-link" href="forum.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
			else
				echo '<li class="page-item"><a class="page-link" href="forum.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
		}
		if ($page == $pages)
		{
			echo '<li class="page-item disabled">';
			echo '<a class="page-link" href="#" aria-label="Next">';
		}
		else 
		{
			echo '<li class="page-item">';
			echo '<a class="page-link" href="forum.php?id='.$_GET['id'].'&page='.($page+1).'" aria-label="Next">';
		}		
		echo '<span aria-hidden="true">&raquo;</span>';
		echo '</a></li>';
		echo '</ul>';
		echo '</div>';
		echo '</nav>';
	}
?>
		</div>
		<!-- Content end -->
		<?php include("includes/standard_footer.php"); ?>
	</body>
</html>