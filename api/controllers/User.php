<?php
/******************************************************************************
 * User.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Controller for User-related actions.
 * !!! NOT YET ADAPTED !!!
 ******************************************************************************/

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
			if (!isset($this->_params['email'], $this->_params['password'], $this->_params['gender'], 
				$this->_params['year'], $this->_params['coords'])) {
				throw new Exception("Unset variables.");
			}

			// Gender check
			$gender = $this->_params['gender'];
			if ($gender !== "M" && $gender !== "F") {
				throw new Exception("Invalid gender specification.");
			}

			// Sanitize coordinates
			$this->_params['coords'] = json_decode($this->_params['coords']);
			$coords = json_encode(array(
										"latitude" => filter_var($this->_params['coords']->latitude, FILTER_SANITIZE_NUMBER_FLOAT),
										"longitude" => filter_var($this->_params['coords']->longitude, FILTER_SANITIZE_NUMBER_FLOAT),
										));
			//error_log($this->_params['coords']->latitude);
			//error_log(filter_var($this->_params['coords']->latitude, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

			$year = filter_var($this->_params['year'], FILTER_SANITIZE_NUMBER_INT);
			
			// ensure a valid email
			$email = filter_var($this->_params['email'], FILTER_VALIDATE_EMAIL);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				throw new Exception("Invalid email.");
			}	
			
			// ensure valid password
			$password = filter_var($this->_params['password'], FILTER_SANITIZE_STRING);
			if (strlen($password) != 128) {
				throw new Exception("Invalid password.");
			}

			// Check for an existing email
			if ($stmt = $this->_mysqli->prepare("SELECT uid FROM login WHERE email = ? LIMIT 1")) {
				$stmt->bind_param('s', $email);
				$stmt->execute();
				$stmt->store_result();
				
				// if a user already exists with this email
				if ($stmt->num_rows >= 1) {
					//error_log("Email taken");
					throw new Exception("Email taken. Already have an account?");
				}
			} else {
				throw new Exception($this->_mysqli->error);
			}

			$stmt->close();

			/********** NEED MORE SECURITY CHECKS HERE ****************/
			
			// Register new user
			$new_user = new UserObject($this->_mysqli);
			$new_user->email = $email;
			$new_user->gender = $gender;
			$new_user->password = $password;
			$new_user->coords = $coords;
			$new_user->year = $year;
			return $new_user->register();
	
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function loginUser() {

		if (isset($this->_params['email'], $this->_params['password'])) {

			$user = new UserObject($this->_mysqli);
			$user->email = $this->_params['email'];
			$user->password = $this->_params['password'];
			return $user->login();
		}
		else
			return "Login failed.";
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