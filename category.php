<?php	require_once("includes/init.php");

	// Makes sure that the GET variable id is set.
	if (!isset($_GET['id']))
	{
		header("Location: index.php");
		die();
	}

	try
	{
		$category = new category($_GET['id']);
	}
	catch(Exception $e)
	{
		header("Location: index.php");
		die();
	}

?>
<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title><?php echo $category->name; ?></title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>		
	<!-- Content start -->
		<div class="container">
<?php displayAlerts(); ?>
			<div class="row category">
				<h3 class="category-title"><?php echo $category->name; ?></h3>
<?php

	// Checks if there's at least one forum in the category.
	$forums = $category->getForums();

	if(!empty($forums))
	{
		foreach($forums as $forum)
		{
			if(!$currentUser->isLoggedIn() && !$forum->guestAccess)
				continue;

			echo '<a href="forum.php?id=' . $forum->id . '">' . "\r\n";
			echo '<div class="col-lg-12 forum">' . "\r\n";
			echo '<div class="col-lg-10">' . "\r\n";
			echo '<h4 class="forum-title">' . $forum->name . '</h4>' . "\r\n";
			echo '<p class="forum-desc">' . $forum->description .'</p>' . "\r\n";
			echo '</div>' . "\r\n";
			echo '<div class="col-lg-1">' . "\r\n";
			echo '<p>Posts:<br> ' . $forum->getNumberOfPosts() . '</p>' . "\r\n";
			echo '</div>' . "\r\n";
			echo '<div class="col-lg-1">';
			echo '<p>Views:<br>'. $forum->getNumberOfviews() .'</p>';
			echo '</div>';
			echo '</div>' . "\r\n";
			echo '</a>' . "\r\n";
		}
	}
	else
	{
		// If there's not one or more forums we print a sorry message.
		echo '<div class="alert alert-info">' . "\r\n";
		echo '<h3><strong>Sorry!</strong> There\'s no forum in this category just yet!</h3>' . "\r\n";
		echo '</div>' . "\r\n";
	}
?>
			</div>
		</div>
		<!-- Content end -->
		<?php include("includes/standard_footer.php"); ?>
	</body>
</html>