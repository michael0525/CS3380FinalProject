<?php

	/*Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
	Model-View-Controller implementation of Movie Browser */

	class MovieViews {
		private $stylesheet = 'MovieBrowser.css';
		private $pageTitle = 'Movies';

		public function __construct() {

		}

		public function __destruct() {

		}

		public function movieListView($user, $movies, $orderBy = 'title', $orderDirection = 'asc', $message = '') {
			$body = "<h1>Movies for {$user->firstName} {$user->lastName}</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='movieButton' href='index.php?view=movieform'>+ Add Movie</a> <a class='movieButton' href='index.php?logout=1'>Logout</a></p>\n";

			if (count($movies) < 1) {
				$body .= "<p>No movies to display!</p>\n";
				return $this->page($body);
			}

			$body .= "<table>\n";
			$body .= "<tr><th>delete</th><th>edit</th>";

			$columns = array(array('name' => 'title', 'label' => 'Title'),
							 array('name' => 'MPAA', 'label' => 'Rating'),
							 array('name' => 'genre', 'label' => 'Genre'),
							 array('name' => 'releaseYear', 'label' => 'Release Year'),
							 array('name' => 'director', 'label' => 'Director'),
							 array('name' => 'actors', 'label' => 'Actor(s)'),
							 array('name' => 'summary', 'label' => 'Summary'),
								);

			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}

			foreach ($movies as $movie) {
				$id = $movie['id'];
				$title = $movie['title'];
				$summary = ($movie['summary']) ? $movie['summary'] : '';
				$genre = $movie['genre'];
				$rating = $movie['MPAA'];
				$director = $movie['director'];
				$actors = $movie['actors'];
				$releaseYear = $movie['releaseYear'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$title</td><td>$rating</td><td>$genre</td><td>$releaseYear</td><td>$director</td><td>$actors</td><td>$summary</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function defaultMovieListView($movies, $orderBy = 'title', $orderDirection = 'asc', $message = '') {
			$body = "<h1>Movies for </h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p> <a class='movieButton' href='index.php?view=loginform'>Log In</a></p>\n";

			if (count($movies) < 1) {
				$body .= "<p>No movies to display!</p>\n";
				return $this->page($body);
			}

			$body .= "<table>\n<tr>";
			// $body .= "<tr><th>delete</th><th>edit</th>";

			$columns = array(array('name' => 'title', 'label' => 'Title'),
							 array('name' => 'MPAA', 'label' => 'Rating'),
							 array('name' => 'genre', 'label' => 'Genre'),
							 array('name' => 'releaseYear', 'label' => 'Release Year'),
							 array('name' => 'director', 'label' => 'Director'),
							 array('name' => 'actors', 'label' => 'Actor(s)'),
							 array('name' => 'summary', 'label' => 'Summary'),
								);

			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}

			foreach ($movies as $movie) {
				$id = $movie['id'];
				$title = $movie['title'];
				$summary = ($movie['summary']) ? $movie['summary'] : '';
				$genre = $movie['genre'];
				$rating = $movie['MPAA'];
				$director = $movie['director'];
				$actors = $movie['actors'];
				$releaseYear = $movie['releaseYear'];

				$body .= "<tr>";
				// $body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				// $body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$title</td><td>$rating</td><td>$genre</td><td>$releaseYear</td><td>$director</td><td>$actors</td><td>$summary</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}



		public function movieFormView($user, $data = null, $message = '') {
			$genre = '';
			$title = '';
			$summary = '';
			$releaseYear = '';
			$director = '';
			$actors = '';
			$selected = array('Action' => '', 'Comedy' => '', 'Drama' => '', 'Horror' => '', 'SciFi' => '', 'Western' => '', 'uncategorized' => '');

			$selectedRating = array('G' => '', 'PG' => '', 'PG-13' => '', 'R' => '', 'NC-17' => '', 'not rated' => '');
			if ($data) {
				$genre = $data['genre'] ? $data['genre'] : 'uncategorized';
				$MPAA = $data['MPAA'] ? $data['MPAA'] : 'not rated';
				$title = $data['title'];
				$summary = $data['summary'];
				$releaseYear = $data['releaseYear'];
				$director = $data['director'];
				$actors = $data['actors'];
				$selected[$genre] = 'selected';
			  $selectedRating[$MPAA] = 'selected';
								} else {
										$selected['uncategorized'] = 'selected';
										$selectedRating['not rated'] = 'selected';
												}



			$body = "<h1>Movies for {$user->firstName} {$user->lastName}</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<form action='index.php' method='post'>";

			if ($data['id']) {
				$body .= "<input type='hidden' name='action' value='update' />";
				$body .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$body .= "<input type='hidden' name='action' value='add' />";
			}

			$body .= <<<EOT2
  <p>Category<br />
  <select name="genre">
	  <option value="Action" {$selected['Action']}>Action</option>
	  <option value="Comedy" {$selected['Comedy']}>Comedy</option>
	  <option value="Drama" {$selected['Drama']}>Drama</option>
		<option value="Horror" {$selected['Horror']}>Horror</option>
	  <option value="SciFi" {$selected['SciFi']}>SciFi</option>
	  <option value="Western" {$selected['Western']}>Western</option>
	  <option value="uncategorized" {$selected['uncategorized']}>uncategorized</option>
  </select>
  </p>

	<p>MPAA<br />
	<select name="MPAA">
		<option value="G" {$selectedRating['G']}>G</option>
		<option value="PG" {$selectedRating['PG']}>PG</option>
		<option value="PG-13" {$selectedRating['PG-13']}>PG-13</option>
		<option value="R" {$selectedRating['R']}>R</option>
		<option value="NC-17" {$selectedRating['NC-17']}>NC-17</option>
		<option value="not rated" {$selectedRating['not rated']}>not rated</option>
	</select>
	</p>

  <p>Title<br />
  <input type="text" name="title" value="$title" placeholder="title" maxlength="255" size="80"></p>

	<p>Release Year<br />
  <input type="number" name="releaseYear" value="$releaseYear" placeholder="Release Year" maxlength="255" size="80"></p>

	<p>Director<br />
	<input type="text" name="director" value="$director" placeholder="director" maxlength="255" size="80"></p>

	<p>Actors<br />
  <input type="text" name="actors" value="$actors" placeholder="actors" maxlength="255" size="80"></p>

  <p>Summary<br />
  <textarea name="summary" rows="6" cols="80" placeholder="summary">$summary</textarea></p>


  <input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
</form>
EOT2;

			return $this->page($body);
		}


		public function loginFormView($data = null, $message = '') {
			$loginID = '';
			if ($data) {
				$loginID = $data['loginid'];
			}

			$body = "<h1>Movies</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= <<<EOT
<form action='index.php' method='post'>
<input type='hidden' name='action' value='login' />
<p>User ID<br />
  <input type="text" name="loginid" value="$loginID" placeholder="login id" maxlength="255" size="80"></p>
<p>Title<br />
  <input type="password" name="password" value="" placeholder="password" maxlength="255" size="80"></p>
  <input type="submit" name='submit' value="Login">
</form>
EOT;

			return $this->page($body);
		}

		public function errorView($message) {
			$body = "<h1>Movies</h1>\n";
			$body .= "<p>$message</p>\n";

			return $this->page($body);
		}

		private function page($body) {
			$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>{$this->pageTitle}</title>
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
</body>
</html>
EOT;
			return $html;
		}

}
