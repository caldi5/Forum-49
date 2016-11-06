<?php

	require_once("includes/init.php");

	// Here we get all of the categories that has at least one forum in them.
	$stmt = $conn->prepare('SELECT id, name 
		FROM categories 
		WHERE (SELECT COUNT(id) FROM forums WHERE category = categories.id) > 0
		ORDER BY ordering');
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->store_result();
	$stmt->close();
	
?>
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
			if ($result->num_rows > 0)
			{
				// Loops thru all of the selected forums.
				while ($row = $result->fetch_assoc())
				{
					echo '<div class="row category">';
					echo '<h3 class="category-title"><a href="category.php?id='.$row['id'].'">'.$row['name'].'</a></h3>';

					// Here we get the forums that belongs to the current category.
					$stmt = $conn->prepare('SELECT * FROM forums WHERE category = ? ORDER BY ordering LIMIT 3');
					$stmt->bind_param('i', $row['id']);
					$stmt->execute();
					$result_2 = $stmt->get_result();
					$stmt->store_result();
					$stmt->close();

					// Just an extra safety check, this should never be false. Explained below.
					if($result_2->num_rows > 0)
					{
						// Lopps thru the forums for this category.
						while ($forum = $result_2->fetch_assoc())
						{
							//Anton test code for objects start
							$forumObject = new forum($forum['id']);
							//Anton test code for objects END
							echo '<a href="forum.php?id='.$forum['id'].'">';
							echo '<div class="col-lg-12 forum">';
							echo '<div class="col-lg-10">';
							echo '<h4 class="forum-title">'.htmlspecialchars($forum['name']).'</h4>';
							echo '<p class="forum-desc">'.htmlspecialchars($forum['description']).'</p>';
							echo '</div>';
							echo '<div class="col-lg-1">';
							echo '<p>Posts:<br> '.numberOfPosts($forum['id']).'</p>';
							echo '</div>';
							echo '<div class="col-lg-1">';
							echo '<p>Views:<br>'. $forumObject->getNumberOfviews() .'</p>';
							echo '</div>';
							echo '</div>';
							echo '</a>';
						}
					}
					else
					{
						// This should not be able to happen since we only select thoose categories with at least one forum.
						echo 'This should not be possible.';
						die();
					}
					echo '</div>';
				}
				$result_2->free_result();
				$result->free_result();
			}
			else
			{
				echo 'No categories? Something is wrong here.';
			}
		?>
		</div>
		<?php require_once("includes/standard_footer.php"); ?>
		<?php require_once("chat.php"); ?>
	</body>
</html>