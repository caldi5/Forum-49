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
			$stmt->bind_result($id, $name, $sortOrder);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->name = $name;
			$this->sortOrder = $sortOrder;
		}

		public function getForums()
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
		public $categoryID;
		public $sortOrder;

		function __construct($id)
		{
			global $conn;
			$this->views = 0;

			$stmt = $conn->prepare('SELECT id, name, description, category, ordering FROM forums WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();		
			$stmt->bind_result($id, $name, $description, $categoryID, $sortOrder);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->name = $name;
			$this->description = $description;
			$this->categoryID = $categoryID;
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
		public $numberOfReplies;

		function __construct($id)
		{
			global $conn;
				
			$stmt = $conn->prepare('SELECT id, creator, title, text, forum, views, created_at FROM posts WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->store_result();		
			$stmt->bind_result($id, $creator, $title, $text, $forum, $views, $CreatedAt);
			$stmt->fetch();
			$stmt->free_result();
			$stmt->close();

			$this->id = $id;
			$this->creator = $creator;
			$this->title = $title;
			$this->text = $text;
			$this->forum = $forum;
			$this->views = $views;
			$this->CreatedAt = $CreatedAt;

			$this->numberOfReplies = $this->getNumberOfReplies($id);
		}
		
		public static function getNumberOfReplies($postID)
		{
			global $conn;

			$stmt = $conn->prepare('SELECT COUNT(*) FROM comments WHERE postID = ?');
			$stmt->bind_param('i', $postID);
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