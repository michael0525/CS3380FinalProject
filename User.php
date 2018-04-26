<?php

/*Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
Model-View-Controller implementation of Movie Browser */

class User {
	public $firstName = '';
	public $lastName = '';
	public $loginID = '';
	public $userID = 0;
	public $hashedPassword = '';
  public $userAccess = '';

	public function load($loginID, $mysqli) {
		$this->clear();

		if (! $mysqli) {
			return false;
		}

		$loginIDEscaped = $mysqli->real_escape_string($loginID);

		$sql = "SELECT * FROM users WHERE loginID = '$loginIDEscaped'";

		if ($result = $mysqli->query($sql)) {
			if ($result->num_rows > 0) {
				$user = $result->fetch_assoc();
				$this->firstName = $user['firstName'];
				$this->lastName = $user['lastName'];
				$this->loginID = $user['loginID'];
				$this->userID = $user['id'];
				$this->hashedPassword = $user['password'];
				$this->userAccess = $user['userAccess'];
			}
			$result->close();
			return true;
		} else {
			return false;
		}
	}

	private function clear() {
		$firstName = '';
		$lastName = '';
		$loginID = '';
		$userID = 0;
		$hashedPassword = '';
		$userAccess = '';
	}
}

?>
