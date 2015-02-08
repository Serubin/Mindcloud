<?php
/******************************************************************************
 * User.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Controller for User-related actions.
 * !!! NOT YET ADAPTED !!!
 ******************************************************************************/

// relative to index.php
require_once "models/UserObject.php";

class User
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	/* createUser()
	 * Creates a UserObject, sets the vars, and creates the user in the database.
	 * Returns true on success or error on fail.
	 */
	public function createUser() {

		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['email'], $this->_params['password'], $this->_params['first_name'], 
				$this->_params['last_name'], $this->_params['year'], $this->_params['gender'])) {
				error_log(json_encode($this->_params));
				throw new UserException("Unset vars", __FUNCTION__);
			}

			// Register new user
			$new_user = new UserObject($this->_mysqli, __FUNCTION__);

			// TODO impletment this
			// Gender check
			$gender = $this->_params['gender'];
			if ($gender !== "M" && $gender !== "F") {
				throw new UserException("Invalid gender specification.", __FUNCTION__);
			}
			$new_user->gender = $gender;

			// validate birthday
			$year = filter_var($this->_params['year'], FILTER_SANITIZE_STRING);
			$new_user->year = $year;

			// ensure a valid email
			$email = filter_var($this->_params['email'], FILTER_VALIDATE_EMAIL);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				throw new UserException("Invalid email.", __FUNCTION__);
			}	
			$new_user->email = $email;

			if(!$new_user->checkEmail()){
				throw new UserException("Duplicate email.", __FUNCTION__);
			}

			// ensure valid password
			$password = filter_var($this->_params['password'], FILTER_SANITIZE_STRING);
			if (strlen($password) != 128) {
				throw new UserException("Invalid password - Client Hash Error", __FUNCTION__);
			}
			$new_user->password = $password;

			$first_name = filter_var($this->_params['first_name'], FILTER_SANITIZE_STRING);
			$new_user->first_name = $first_name;

			$last_name = filter_var($this->_params['last_name'], FILTER_SANITIZE_STRING);
			$new_user->last_name = $last_name;
			

			// Submits new users
			return $new_user->register();
	
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/* loginUser()
	 * Logs users in and sets sessions and cookies
	 */
	public function loginUser() {
		if (isset($this->_params['email'], $this->_params['password'])) {
			throw new UserException("Unset variables", __FUNCTION__);
		}

		$user = new UserObject($this->_mysqli);
		$user->email = $this->_params['email'];
		$user->password = $this->_params['password'];
		return $user->login();
	}

	/* checkUser()
	 * Checks whether the user is logged in.
	 */
	public function checkUser() {
		$user = new UserObject($this->_mysqli);
		return $user->login_check();
	}


	/*
	 * initUser()
	 * Only called once immediately following a user's registration.
	 * Takes care of the following tasks:
	 * + TODO
	 */
	public function initUser() {
		// Verify the user is logged in
		$user = new UserObject($this->_mysqli);
		if ($user->login_check() == 'init') {

			

			// Do user init
			$user->uid = $_SESSION['uid'];
			if (!$user->init())
				return false;

			$_SESSION['init'] = false;
			return true;
		}
	}

	/*
	 * loadUser()
	 * Sends to the client a json array of the current list name and contents,
	 * as well as the user's other lists.
	 */
	public function loadUser() {
		// Veryify that the user is logged in
		$user = new UserObject($this->_mysqli);
		if ($user->login_check() == true) {
			//error_log("Login check in load_user was sucessful");
			
			// TODO
		}
	}


	public function logoutUser() {
		$user = new UserObject($this->_mysqli);
		if ($user->login_check() == true) {
			return $user->logout();
		}
	}
}