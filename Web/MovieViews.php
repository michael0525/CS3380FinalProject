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
			if ($user->firstName != "" && $user->firstName != NULL) {
				$body = "<h1>Tiger Movie Browser for {$user->firstName} {$user->lastName}</h1>\n";
			} else {
				$body = "<h1>Tigers Movie Browser</h1>\n";
			}

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='movieAddButton' href='index.php?view=movieform'>+ Add Movie</a> <a class='logoutButton' href='index.php?logout=1'>Logout</a></p>\n";

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
				$MPAA = $movie['MPAA'];
				$director = $movie['director'];
				$actors = $movie['actors'];
				$releaseYear = $movie['releaseYear'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$title</td><td>$MPAA</td><td>$genre</td><td>$releaseYear</td><td>$director</td><td>$actors</td><td>$summary</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function defaultMovieListView($movies, $orderBy = 'title', $orderDirection = 'asc', $message = '') {
			if ($user->firstName != "" && $user->firstName != NULL) {
				$body = "<h1>Tiger Movie Browser for {$user->firstName} {$user->lastName}</h1>\n";
			} else {
				$body = "<h1>Tigers Movie Browser</h1>\n";
			}

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
				$MPAA = $movie['MPAA'];
				$director = $movie['director'];
				$actors = $movie['actors'];
				$releaseYear = $movie['releaseYear'];

				$body .= "<tr>";
				// $body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				// $body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$title</td><td>$MPAA</td><td>$genre</td><td>$releaseYear</td><td>$director</td><td>$actors</td><td>$summary</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}



		public function movieFormView($user, $data = null, $message = '') {
			$title = '';
			$MPAA = '';
			$genre = '';
			$releaseYear = '';
			$director = '';
			$actor = '';
			$summary = '';
			$selectedRating = array('not rated' => '','G' => '', 'PG' => '', 'PG-13' => '', 'R' => '', 'NC-17' => '');
			$selectedGenre = array('uncategorized' => '', 'Action' => '', 'Comedy' => '', 'Drama' => '', 'Horror' => '', 'SciFi' => '', 'Western' => '');
			if ($data) {
				$title = $data['title'];
				$MPAA = $data['MPAA'] ? $data['MPAA'] : 'not rated';
				$genre = $data['genre'] ? $data['genre'] : 'uncategorized';
				$releaseYear = $data['releaseYear'] ? $data['releaseYear'] : '';
				$director = $data['director'] ? $data['director'] : '';
				$actors = $data['actors'] ? $data['actors'] : '';
				$summary = $data['summary'];
				$selectedRating[$MPAA] = 'selected';
				$selectedGenre[$genre] = 'selected';
			} else {
				$selectedRating['not rated'] = 'selected';
				$selectedGenre['uncategorized'] = 'selected';
			}

			if ($user->firstName != "" && $user->firstName != NULL) {
				$body = "<h1>Tiger Movie Browser for {$user->firstName} {$user->lastName}</h1>\n";
			} else {
				$body = "<h1>Tigers Movie Browser</h1>\n";
			}

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

	<p>Title<br />
	<input type="text" name="title" value="$title" placeholder="title" maxlength="255" size="80"></p>

	<p>Rating<br />
	<select name="MPAA">
	<option value="not rated" {$selectedRating['not rated']}>not rated</option>
		<option value="G" {$selectedRating['G']}>G</option>
		<option value="PG" {$selectedRating['PG']}>PG</option>
		<option value="PG-13" {$selectedRating['PG-13']}>PG-13</option>
		<option value="R" {$selectedRating['R']}>R</option>
		<option value="NC-17" {$selectedRating['NC-17']}>NC-17</option>
	</select>
	</p>

  <p>Genre<br />
  <select name="genre">
		<option value="uncategorized" {$selectedGenre['uncategorized']}>uncategorized</option>
	  <option value="Action" {$selectedGenre['Action']}>Action</option>
	  <option value="Comedy" {$selectedGenre['Comedy']}>Comedy</option>
	  <option value="Drama" {$selectedGenre['Drama']}>Drama</option>
		<option value="Horror" {$selectedGenre['Horror']}>Horror</option>
	  <option value="SciFi" {$selectedGenre['SciFi']}>SciFi</option>
	  <option value="Western" {$selectedGenre['Western']}>Western</option>
  </select>
  </p>

	<p>Release Year<br />
	<input type="text" name="releaseYear" value="$releaseYear" placeholder ="release year" maxlength="255" size="20"></p>

	<p>Director<br />
	<input type="text" name="director" value="$director" placeholder ="director" maxlength="255" size="80"></p>

	<p>Actor(s)<br />
	<input type="text" name="actors" value="$actors" placeholder ="actor(s)" maxlength="255" size="80"></p>

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
<img src="https://i.pinimg.com/736x/a6/15/50/a6155006f78e6943cd2ae7b418d1c157--tigers.jpg" style="float: left;width: 100px;position: absolute;height: 100px;top: -2px;left: 10px;">
</head>
<body>
$body
</body>
</html>
EOT;
			return $html;
		}

}
