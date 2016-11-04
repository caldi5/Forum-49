<?php
	require_once("includes/init.php");

	$time = $_GET['t'];

	$stmt = $conn->prepare('
			(SELECT "comment" as type, userID, postID, "0" as title, text, created_at FROM comments WHERE created_at > ?)
 			UNION
			(SELECT "post" as type, creator as userID, id as postID, title, text, created_at FROM posts WHERE created_at > ?)');
	$stmt->bind_param('ii', $time, $time);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->store_result();
	$stmt->close();

	if ($result->num_rows == 0)
		return false;

	while ($row = $result->fetch_assoc())
	{
			echo '<div class="row">';
			echo '<a href="post.php?id='.$row['postID'].'">';
			echo '<div class="col-lg-12 liveContent">';
			echo '<div class="col-lg-10">';
			if ($row['type'] == "post")
			{
				echo '<h4 class="liveTitle">'.getUsernameID($row['userID']).' posted '.$row['title'].' in '.getForumName(postBelongsTo($row['postID'])).'</h4>';
				echo '<p class="liveText">'.$row['text'].'</p>';
			}
			elseif ($row['type'] == "comment")
			{
				echo '<h4 class="liveTitle">'.getUsernameID($row['userID']).' commented on '.getPostTitle($row['postID']).' in '.getForumName(postBelongsTo($row['postID'])).'</h4>';
				echo '<p class="liveText">'.$row['text'].'</p>';
			}
			
			//echo '<p class="liveText">'.$row['text'].'</p>';
			echo '</div>';
			echo '<div class="col-lg-2">';
			echo '<span class="post-time">'.date('H:i d/m/y', $row['created_at']).'</span>';
			echo '</div>';
			echo '</div>';
			echo '</a>';
			echo '</div>';
	}
?>