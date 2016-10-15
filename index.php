<?php
	session_start();
	require_once "includes/dbconn.php";

	$stmt = $conn->prepare('SELECT id, name 
		FROM categories 
		WHERE (SELECT COUNT(id) FROM forums WHERE category = categories.id) > 0
		ORDER BY ordering');
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->store_result();
	
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

					$stmt_2 = $conn->prepare('SELECT * FROM forums WHERE category = ? ORDER BY ordering LIMIT 3');
					$stmt_2->bind_param('i', $row['id']);
					$stmt_2->execute();
					$result_2 = $stmt_2->get_result();
					$stmt_2->store_result();

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
						echo 'This should not be possible.';
						die();
					}
					echo '</div>';
					$stmt_2->free_result();
					$stmt_2->close();
				}
				$stmt->free_result();
				$stmt->close();
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