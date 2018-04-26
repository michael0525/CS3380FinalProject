<?php

	/*Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
	Model-View-Controller implementation of Movie Browser */

	require('User.php');

	class MovieModel {
		private $error = '';
		private $mysqli;
		private $orderBy = 'title';
		private $orderDirection = 'asc';
		private $user;

		public function __construct() {
			session_start();
			$this->initDatabaseConnection();
			$this->restoreOrdering();
			$this->restoreUser();
		}

		public function __destruct() {
			if ($this->mysqli) {
				$this->mysqli->close();
			}
		}

		public function getError() {
			return $this->error;
		}

		private function initDatabaseConnection() {
			require('db_credentials.php');
			$this->mysqli = new mysqli($servername, $username, $password, $dbname);
			if ($this->mysqli->connect_error) {
				$this->error = $mysqli->connect_error;
			}
		}

		private function restoreOrdering() {
			$this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
			$this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		private function restoreUser() {
			if ($loginID = $_SESSION['loginid']) {
				$this->user = new User();
				if (!$this->user->load($loginID, $this->mysqli)) {
					$this->user = null;
				}
			}
		}

		public function getUser() {
			return $this->user;
		}

		public function login($loginID, $password) {
			// check if loginID and password are valid by comparing
			// encrypted version of password to encrypted password stored
			// in database for user with loginID

			$user = new User();
			if ($user->load($loginID, $this->mysqli) && password_verify($password, $user->hashedPassword)) {
				$this->user = $user;
				$_SESSION['loginid'] = $loginID;
				return array(true, "");
			} else {
				$this->user = null;
				$_SESSION['loginid'] = '';
				return array(false, "Invalid login information.  Please try again.");
			}
		}

		public function logout() {
			$this->user = null;
			$_SESSION['loginid'] = '';
		}

		public function toggleOrder($orderBy) {
			if ($this->orderBy == $orderBy)	{
				if ($this->orderDirection == 'asc') {
					$this->orderDirection = 'desc';
				} else {
					$this->orderDirection = 'asc';
				}
			} else {
				$this->orderDirection = 'asc';
			}
			$this->orderBy = $orderBy;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		public function getOrdering() {
			return array($this->orderBy, $this->orderDirection);
		}




		public function getAllMovies() {
			$this->error = '';
			$movies = array();


			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($movies, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);


			$stmt = $this->mysqli->prepare("SELECT * FROM movies ORDER BY $orderByEscaped $orderDirectionEscaped");

			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return array($movies, $this->error);
			}
			if (! ($result = $stmt->get_result()) ) {
				$this->error = "Getting result failed: " . $stmt->error;
				return array($movies, $this->error);
			}
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					array_push($movies, $row);
				}
			}

			$stmt->close();

			return array($movies, $this->error);
		}



		public function getMovies() {
			$this->error = '';
			$movies = array();

			if (!$this->user) {
				$this->error = "User not specified. Unable to get movie.";
				return array($movies, $this->error);
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($movies, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);


if($this->user->loginID == admin){

			$stmt = $this->mysqli->prepare("SELECT * FROM movies ORDER BY $orderByEscaped $orderDirectionEscaped");
		}else{
			$stmt = $this->mysqli->prepare("SELECT * FROM movies WHERE userID = ? ORDER BY $orderByEscaped $orderDirectionEscaped");

			if (! ($stmt->bind_param("i", $this->user->userID)) ) {
				$this->error = "Prepare failed: " . $this->mysqli->error;
				return array($movies, $this->error);
			}}
			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return array($movies, $this->error);
			}
			if (! ($result = $stmt->get_result()) ) {
				$this->error = "Getting result failed: " . $stmt->error;
				return array($movies, $this->error);
			}
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					array_push($movies, $row);
				}
			}

			$stmt->close();

			return array($movies, $this->error);
		}

		public function getMovie($id) {
			$this->error = '';
			$movie = null;

			if (!$this->user) {
				$this->error = "User not specified. Unable to get movie.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($movie, $this->error);
			}

			if (! $id) {
				$this->error = "No id specified for movie to retrieve.";
				return array($movie, $this->error);
			}
      if($this->user->loginID == admin){


				$stmt = $this->mysqli->prepare("SELECT * FROM movies WHERE id = $id" );
			}else{
			$stmt = $this->mysqli->prepare("SELECT * FROM movies WHERE userID = ? AND id = ?");

			if (! ($stmt->bind_param("ii", $this->user->userID, $id)) ) {
				$this->error = "Prepare failed: " . $this->mysqli->error;
				return array($movie, $this->error);
			}}
			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return array($movie, $this->error);
			}
			if (! ($result = $stmt->get_result()) ) {
				$this->error = "Getting result failed: " . $stmt->error;
				return array($movie, $this->error);
			}

			if ($result->num_rows > 0) {
				$movie = $result->fetch_assoc();
			}

			$stmt->close();

			return array($movie, $this->error);
		}

		public function addMovie($data) {
			$this->error = '';

			if (!$this->user) {
				$this->error = "User not specified. Unable to add movie.";
				return $this->error;
			}

			$title = $data['title'];
			$MPAA = $data['MPAA'];
			$genre = $data['genre'];
			$releaseYear = $data['releaseYear'];
			$director = $data['releaseYear'];
			$actors = $data['actors'];
			$summary = $data['summary'];

			if (! $title) {
				$this->error = "No title found for movie to add. A title is required.";
				return $this->error;
			}

			if (! $MPAA) {
				$MPAA = 'not rated';
			}

			if (! $genre) {
				$genre = 'uncategorized';
			}

			if (! $releaseYear) {
				$this->error = "No title found for movie to add. A title is required.";
				return $this->error;
			}

			if (! $director) {
				$this->error = "No director found for movie to add. A director is required.";
				return $this->error;
			}

			if (! $actors) {
				$this->error = "No actors found for movie to add. An actor is required.";
				return $this->error;
			}

			$stmt = $this->mysqli->prepare("INSERT INTO movies (title, summary, MPAA, actors, director, releaseYear, genre, userID) VALUES (?, ?, ?, ?,?, ?, ?, ?)");

			if (! ($stmt->bind_param("sssssisi", $title, $summary, $MPAA, $actors, $director, $releaseYear, $genre, $this->user->userID)) ) {
				$this->error = "Prepare failed: " . $this->mysqli->error;
				return $this->error;

			}
			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return $this->error;
			}

			$stmt->close();

			return $this->error;
		}

		public function updateMovie($data) {
			$this->error = '';

			if (!$this->user) {
				$this->error = "User not specified. Unable to update movie.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to update movie.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for movie to update.";
				return $this->error;
			}

			$title = $data['title'];
			if (! $title) {
				$this->error = "No title found for movie to update. A title is required.";
				return $this->error;
			}

			$MPAA = $data['MPAA'] ? $data['MPAA'] : "not rated";
			$genre = $data['genre'] ? $data['genre'] : "uncategorized";
			$releaseYear = $data['releaseYear'];
			$director = $data['director'];
			$actors = $data['actors'];
			$summary = $data['summary'];

			  if($this->user->loginID == admin){
					$stmt = $this->mysqli->prepare("UPDATE movies SET title=?, MPAA=?, actors=?, director=?, releaseYear=?, summary=?, genre=?  WHERE  id = ?");

				if (! ($stmt->bind_param("sssssisi", $title, $summary, $MPAA, $actors, $director, $releaseYear, $genre, $id)) ) {
							$this->error = "Prepare failed: " . $this->mysqli->error;
							return $this->error;}
				}else{
					$stmt = $this->mysqli->prepare("UPDATE movies SET title=?, summary=?, MPAA=?, actors=?, director=?, releaseYear=?, genre=? WHERE userID = ? AND id = ?");

				if (! ($stmt->bind_param("sssssisii", $title, $summary, $MPAA, $actors, $director, $releaseYear, $genre, $this->user->userID, $id)) ) {
							$this->error = "Prepare failed: " . $this->mysqli->error;
							return $this->error;
			}}

			//Maybe remove this
			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return $this->error;
			}

			$stmt->close();

			return $this->error;
		}

		public function deleteMovie($id) {
			$this->error = '';

			if (!$this->user) {
				$this->error = "User not specified. Unable to delete movie.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to delete movie.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for movie to delete.";
				return $this->error;
			}
        if($this->user->loginID == admin){
					$stmt = $this->mysqli->prepare("DELETE FROM movies WHERE id = $id");
				}else{
			$stmt = $this->mysqli->prepare("DELETE FROM movies WHERE userID = ? AND id = ?");

			if (! ($stmt->bind_param("ii", $this->user->userID, $id)) ) {
				$this->error = "Prepare failed: " . $this->mysqli->error;
				return $this->error;}
			}
			if (! $stmt->execute() ) {
				$this->error = "Execute of statement failed: " . $stmt->error;
				return $this->error;
			}

			$stmt->close();

			return $this->error;
		}

	}

?>
