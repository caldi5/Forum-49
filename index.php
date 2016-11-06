<?php	require_once("includes/init.php"); ?>
<!DOCTYPE html>
<html>
	<head>
<?php include("includes/standard_head.php"); ?>
		<title>Forum</title>
	</head>
	<body>
<?php include("includes/navbar.php"); ?>
		<!-- Content start -->
		<div class="container">
<?php	displayAlerts(); ?>
<?php

			$categories = getCategories();
			foreach ($categories as $category) 
			{
				$forums = $category->getForums();

				echo '<div class="row category">';
				echo '<h3 class="category-title"><a href="category.php?id='.$category->id.'">'.$category->name.'</a></h3>';
				
				$i = 0; //To limit number of forums displayed to 3
				foreach($forums as $forum)
				{
					echo '<a href="forum.php?id='.$forum->id.'">';
					echo '<div class="col-lg-12 forum">';
					echo '<div class="col-lg-10">';
					echo '<h4 class="forum-title">'.htmlspecialchars($forum->name).'</h4>';
					echo '<p class="forum-desc">'.htmlspecialchars($forum->description).'</p>';
					echo '</div>';
					echo '<div class="col-lg-1">';
					echo '<p>Posts:<br> '.$forum->getNumberOfPosts().'</p>';
					echo '</div>';
					echo '<div class="col-lg-1">';
					echo '<p>Views:<br>'. $forum->getNumberOfviews() .'</p>';
					echo '</div>';
					echo '</div>';
					echo '</a>';
					if (++$i == 3) break;
				}

				if(empty($forums))
				{
					// If there's not one or more forums we print a sorry message.
					echo '<div class="alert alert-info">' . "\r\n";
					echo '<h3><strong>Sorry!</strong> There\'s no forum in this category just yet!</h3>' . "\r\n";
					echo '</div>' . "\r\n";
				}				
				echo '</div>';
			}
			if(empty($categories))
			{
				echo 'No categories? Something is wrong here.';
			}
?>
		</div>
		<?php require_once("includes/standard_footer.php"); ?>
	</body>
</html>