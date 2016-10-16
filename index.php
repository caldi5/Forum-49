<?php
	session_start();
	require_once "includes/dbconn.php";

	$stmt = $conn->prepare('SELECT id, name 
		FROM categories 
		WHERE (SELECT COUNT(id) FROM forums WHERE category = categories.id) > 0
		ORDER BY ordering');
	$stmt->execute();
	$result = $stmt->get_result();
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
		<?php
			if ($result->num_rows > 0)
			{
				while ($row = $result->fetch_assoc())
				{
					echo '<div class="row category">';
					echo '<h2 class="category-title"><a href="category.php?id='.$row['id'].'">'.$row['name'].'</a></h2>';

					$stmt = $conn->prepare('SELECT * FROM forums WHERE category = ? ORDER BY ordering LIMIT 3');
					$stmt->bind_param('i', $row['id']);
					$stmt->execute();
					$result_2 = $stmt->get_result();
					$stmt->close();

					if($result_2->num_rows > 0)
					{
						while ($forum = $result_2->fetch_assoc())
						{
							echo '<a href="forum.php?id='.$forum['id'].'">';
							echo '<div class="col-lg-12 forum">';
							echo '<h4 class="forum-title">'.$forum['name'].'</h4>';
							echo '<p class="forum-desc">'.$forum['description'].'</p>';
							echo '</div>';
							echo '</a>';
						}
					}
					else
					{
						echo 'There is no forums in this category yet.';
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
		
		<?php include("includes/standard_footer.php"); ?>
	</body>
</html>