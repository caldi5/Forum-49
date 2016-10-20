<?php
	session_start();
	require_once "includes/dbconn.php";
	require_once 'functions/get.php';
	require_once 'functions/user.php';

	if (isset($_GET['new']) && isset($_GET['forum']))
	{
		if ($forumName = getForumName($_GET['forum']))
		{
			$newPost = true;
			$forum = true;
		}
		else
		{
			$newPost = true;
			$forum = false;
		}
	}
	elseif (isset($_GET['id']))
	{
		$stmt = $conn->prepare('SELECT * FROM posts WHERE id = ?');
		$stmt->bind_param('i', $_GET['id']);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->store_result();
		

		if ($result->num_rows == 0)
			header('Location: index.php');


		$post = $result->fetch_array();

		$repliesSTMT = $conn->prepare('SELECT * FROM comments WHERE postID = ?');
		$repliesSTMT->bind_param('i', $post['id']);
		$repliesSTMT->execute();
		$comments = $repliesSTMT->get_result();
		$repliesSTMT->store_result();

		$repliesSTMT->free_result();
		$stmt->free_result();
		$stmt->close();
		$repliesSTMT->close();

	}
	else
	{
		header('Location: index.php');
	}
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
			<h2 class="post-title">
			<?php 
				$categoryID = forumBelongsTo($post['forum']);
				echo '<a href="category.php?id='.$categoryID.'">'.getCategoryName($categoryID).'</a> / ';
				echo '<a href="forum.php?id='.$post['forum'].'">'.getForumName($post['forum']).'</a> / ';
				echo $post['title']; 
				?>
			</h2>
			<div class="row post-post">
				<div class="col-lg-2 post-profile">
					<h4><?php echo getUsernameID($post['creator']); ?></h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p><?php echo $post['text']; ?></p>
				</div>
			</div>

			<?php
			if ($comments->num_rows > 0)
			{
				while ($comment = $comments->fetch_assoc())
				{
					echo '<div class="row post-reply">';
					echo '<div class="col-lg-2 post-profile">';
					echo '<h4>'.getUsernameID($comment['userID']).'</h4>';
					echo '<img src="img/cat.jpg" alt="Profile picture">';
					echo '</div>';
					echo '<div class="col-lg-10 post-text">';
					echo '<p>'.$comment['text'].'</p>';
					echo '</div>';
					echo '</div>';
				}
			}


			
			?>
			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf</p>
				</div>
			</div>



			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdf</p>
				</div>
			</div>
			<div class="row post-reply">
				<div class="col-lg-2 post-profile">
					<h4>Username</h4>
					<img src="img/cat.jpg" alt="Profile picture">
				</div>
				<div class="col-lg-10 post-text">
					<p>asdf</p>
				</div>
			</div>
			<div class="row">
				<ul class="pagination">
				  <li class="active"><a href="#">1</a></li>
				  <li><a href="#">2</a></li>
				  <li><a href="#">3</a></li>
				  <li><a href="#">4</a></li>
				  <li><a href="#">5</a></li>
				</ul>
			</div>
			<div class="row post-reply-form">
				<h3>Reply</h3>
				<form action="post.php" method="post">
					<textarea name="comment" maxlength="1500" required></textarea>
					<br>
					<input type="submit">
				</form>
			</div>
		</div>
		<!-- Content end -->
<?php include("includes/standard_footer.php"); ?>
	</body>
</html>