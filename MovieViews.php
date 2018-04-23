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
			$body .= "<tr><th>delete</th><th>edit</th><th>completed</th>";

			$columns = array(array('name' => 'title', 'label' => 'title'),
							 array('name' => 'summary', 'label' => 'summary'),
							 array('name' => 'genre', 'label' => 'genre'));

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

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$title</td><td>$summary</td><td>$genre</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function movieFormView($user, $data = null, $message = '') {
			$genre = '';
			$title = '';
			$summary = '';
			$selected = array('Action' => '', 'Comedy' => '', 'Drama' => '', 'Horror' => '', 'SciFi' => '', 'Western' => '', 'uncategorized' => '');
			if ($data) {
				$genre = $data['genre'] ? $data['genre'] : 'uncategorized';
				$title = $data['title'];
				$summary = $data['summary'];
				$selected[$genre] = 'selected';
			} else {
				$selected['uncategorized'] = 'selected';
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
  <p>Genre<br />
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

	<p>Rating<br />
	<select name="genre">
		<option value="G" {$selected['G']}>G</option>
		<option value="PG" {$selected['PG']}>PG</option>
		<option value="PG-13" {$selected['PG-13']}>PG-13</option>
		<option value="R" {$selected['R']}>R</option>
		<option value="Not Rated" {$selected['Not Rated']}>Not Rated</option>
	</select>
	</p>
  <p>Title<br />
  <input type="text" name="title" value="$title" placeholder="title" maxlength="255" size="80"></p>

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
