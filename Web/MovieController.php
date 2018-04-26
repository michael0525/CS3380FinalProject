<?php

	/*Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
	Model-View-Controller implementation of Movie Browser */

	require('MovieModel.php');
	require('MovieViews.php');

	class MovieController {
		private $model;
		private $views;

		private $orderBy = '';
		private $view = '';
		private $action = '';
		private $message = '';
		private $data = array();


		public function __construct() {
			$this->model = new MovieModel();
			$this->views = new MovieViews();

			$this->view = $_GET['view'] ? $_GET['view'] : 'movielist';
			// $this->view = $_GET['log'] ? $_GET['log'] : 'loginForm';
			$this->action = $_POST['action'];
		}

		public function __destruct() {
			$this->model = null;
			$this->views = null;
		}

		public function run() {
			if ($error = $this->model->getError()) {
				print $views->errorView($error);
				exit;
			}

			// Note: given order of handling and given processOrderBy doesn't require user to be logged in
			//...orderBy can be changed without being logged in
			$this->processOrderBy();

			$this->processLogout();

			switch($this->action) {
				case 'login':
					$this->handleLogin();
					break;
				case 'delete':
					$this->handleDelete();
					break;
				case 'add':
					$this->handleAddMovie();
					break;
				case 'edit':
					$this->handleEditMovie();
					break;
				case 'update':
					$this->handleUpdateMovie();
					break;
				default:
			 $this->defaultUserLogIn();
			}

			switch($this->view) {

				case 'loginform':
					print $this->views->loginFormView($this->data, $this->message);
					break;
				case 'movieform':
					print $this->views->movieFormView($this->model->getUser(), $this->data, $this->message);
					break;

					case 'defaultLogIn':

					list($orderBy, $orderDirection) = $this->model->getOrdering();
					list($movies, $error) = $this->model->getAllMovies();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->defaultMovieListView( $movies, $orderBy, $orderDirection, $this->message);
					 break;


				default: // 'movielist'
					list($orderBy, $orderDirection) = $this->model->getOrdering();
					list($movies, $error) = $this->model->getMovies();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->movieListView($this->model->getUser(), $movies, $orderBy, $orderDirection, $this->message);
			}

		}


		private function defaultUserLogIn() {
			if (( $this->view == 'movielist') && (! $this->model->getUser())) {
				$this->view = 'defaultLogIn';
				return false;
			} else {
				return true;
			}
		}


		private function verifyLogin() {
			if (! $this->model->getUser()) {
				$this->view = 'loginform';
				return false;
			} else {
				return true;
			}
		}

		private function processOrderby() {
			if ($_GET['orderby']) {
				$this->model->toggleOrder($_GET['orderby']);
			}
		}

		private function processLogout() {
			if ($_GET['logout']) {
				$this->model->logout();
			}
		}

		private function handleLogin() {
			$loginID = $_POST['loginid'];
			$password = $_POST['password'];

			list($success, $message) = $this->model->login($loginID, $password);
			if ($success) {
				$this->view = 'movielist';
			} else {
				$this->message = $message;
				$this->view = 'loginform';
				$this->data = $_POST;
			}
		}

		private function handleDelete() {
			if (!$this->verifyLogin()) return;

			if ($error = $this->model->deleteMovie($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'movielist';
		}

		private function handleAddMovie() {
			if (!$this->verifyLogin()) return;

			if ($_POST['cancel']) {
				$this->view = 'movielist';
				return;
			}

			$error = $this->model->addMovie($_POST);
			if ($error) {
				$this->message = $error;
				$this->view = 'movieform';
				$this->data = $_POST;
			}
		}

		private function handleEditMovie() {
			if (!$this->verifyLogin()) return;

			list($movie, $error) = $this->model->getMovie($_POST['id']);
			if ($error) {
				$this->message = $error;
				$this->view = 'movielist';
				return;
			}
			$this->data = $movie;
			$this->view = 'movieform';
		}

		private function handleUpdateMovie() {
			if (!$this->verifyLogin()) return;

			if ($_POST['cancel']) {
				$this->view = 'movielist';
				return;
			}

			if ($error = $this->model->updateMovie($_POST)) {
				$this->message = $error;
				$this->view = 'movieform';
				$this->data = $_POST;
				return;
			}

			$this->view = 'movielist';
		}
	}
?>
