<?php
	require_once __DIR__.'/../includes/dbconn.php';

	//returns an array with all categories names
	function getCategories()
	{
		global $conn;
		$stmt = $conn->prepare('SELECT id FROM categories');
		$stmt->execute();
		$stmt->bind_result($id);
		while ($stmt->fetch()) 
		{
			$ids[] = $id;
		}
		$stmt->close();

		
		foreach($ids as $id) 
		{
			$categories[] = new category($id);
		}

		return $categories;
	}

	class category
	{
		public $id;
		public $name;
		public $sortOrder;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, name, ordering FROM categories WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');		
			$stmt->bind_result($id, $name, $sortOrder);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->name = $name;
			$this->sortOrder = $sortOrder;
		}

		//TODO: implement limit and offset...
		public function getForums($limit=0, $offset=0)
		{
			global $conn;
			$stmt = $conn->prepare('SELECT id FROM forums WHERE category = ?  ORDER BY ordering');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->bind_result($id);
			while ($stmt->fetch()) 
			{
				$ids[] = $id;
			}
			$stmt->close();

			foreach($ids as $id) 
			{
				$forums[] = new forum($id);
			}

			return $forums;
		}

		public function getNumberOfForums()
		{
			global $conn;
			$stmt = $conn->prepare('SELECT COUNT(*) FROM forums WHERE category = ?');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();

			return $count;
		}
	}

	class forum
	{
		public $id;
		public $name;
		public $description;
		public $category;
		public $sortOrder;

		function __construct($id)
		{
			global $conn;
			$this->views = 0;

			$stmt = $conn->prepare('SELECT id, name, description, category, ordering FROM forums WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');		
			$stmt->bind_result($id, $name, $description, $category, $sortOrder);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->name = $name;
			$this->description = $description;
			$this->category = $category;
			$this->sortOrder = $sortOrder;
		}
		public function getNumberOfviews()
		{
			global $conn;

			$stmt = $conn->prepare('SELECT SUM(views) FROM posts Where forum = ?');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->bind_result($views);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			if(!empty($views))				
				return $views;
			return 0;
		}

		public function getNumberOfPosts()
		{
			global $conn;
			$stmt = $conn->prepare('SELECT COUNT(*) FROM posts WHERE forum = ?');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();

			return $count;
		}
	}

	class post
	{
		public $id;
		public $creator;
		public $title;
		public $text;
		public $forum;
		public $views;
		public $createdAt;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, creator, title, text, forum, views, created_at FROM posts WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');
			$stmt->bind_result($id, $creator, $title, $text, $forum, $views, $createdAt);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->creator = $creator;
			$this->title = $title;
			$this->text = $text;
			$this->forum = $forum;
			$this->views = $views;
			$this->createdAt = $createdAt;
		}
		
		public function getComments($limit=PHP_INT_MAX, $offset=0)
		{
			global $conn;
			$stmt = $conn->prepare('SELECT id FROM comments WHERE postID = ? ORDER BY created_at LIMIT ? OFFSET ?');
			$stmt->bind_param('iii', $this->id, $limit, $offset);
			$stmt->execute();
			$stmt->bind_result($id);
			while ($stmt->fetch()) 
			{
				$ids[] = $id;
			}
			$stmt->close();

			if(!isset($ids))
				return;

			foreach($ids as $id) 
				$comments[] = new comment($id);

			return $comments;
		}

		public function getNumberOfComments()
		{
			global $conn;

			$stmt = $conn->prepare('SELECT COUNT(*) FROM comments WHERE postID = ?');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();
			return $count;
		}

		public function getNumberOfviews()
		{
			return $this->views;
		}

		public function view()
		{
			global $conn;

			$stmt = $conn->prepare('UPDATE posts SET views = views + 1 WHERE id=?');
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
		}

	}

	class comment
	{
		public $id;
		public $creator;
		public $post;
		public $text;
		public $createdAt;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, userID, postID, text, created_at FROM comments WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0)
				throw new Exception('Does not exist');	
			$stmt->bind_result($id, $creator, $post, $text, $createdAt);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->creator = $creator;
			$this->post = $post;
			$this->text = $text;
			$this->createdAt = $createdAt;

		}
	}