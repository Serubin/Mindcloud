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
require_once "include/mail/mail.php";

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
			$success = $new_user->register();
			// returns if not true, wont send email

			if($success !== true) {
				return $success;
			}
			
			// submit email
			$emailBody = "<h2>Welcome to mindcloud!</h2>
					  <p>Hi $first_name $last_name, <br />
					  We've noticed that you created an account! We're very excited to have you! All you have left todo is verify your account by clicking the link below! <br/>
					  See you on the other side!<br />

					  <a href='http://mindcloud.io/validate/" . hash("sha512", $new_user->uid . $new_user->first_name . $new_user->last_name . $new_user->email) . "'>Validate your account!</a> <br/>

					  -- The Mindcloud team! </p>";

			Mail::send($email, "Welcome to Mindcloud, this is it!", $emailBody);

			return $success;
	
		} catch (Exception $e) {
			return $e;
		}
	}

	/* loginUser()
	 * Logs users in and sets sessions and cookies
	 */
	public function loginUser() {
		try{
			if (!isset($this->_params['email'], $this->_params['password'])) {
				throw new UserException("Unset variables.\n" .
					"Email: " . $this->_params['email'] . "\n" .
					"Password: " . $this->_params['password'],
					__FUNCTION__);
			}

			$user = new UserObject($this->_mysqli);
			$user->email = $this->_params['email'];
			$user->password = $this->_params['password'];
			return $user->login();
		} catch (Exception $e){
			return $e;
		}
	}

	/* checkUser()
	 * Checks whether the user is logged in.
	 */
	public function checkUser() {
		$user = new UserObject($this->_mysqli);
		return $user->loginCheck();
	}

	public function verifyUser(){
		try{
			if(!isset($this->_params['uid'], $this->_params['hash'])){
				$user = new UserObject();
				$user->id = filter_var($this->_params['uid'], FILTER_SANITIZE_STRING);
				$user->load();

				$local_hash = hash(‘sha512’, $user->uid . $user->first_name . $user->last_name . $user->email);

				if($local_hash == $this->_params) {
					$user->verified = true;
					return $user->updateVerified();
				}
			}
		} catch (Exception $e){
			return $e;
		}
	}

	/*
	 * loadUser()
	 * Sends to the client a json array of the current list name and contents,
	 * as well as the user's other lists.
	 */
	public function loadUser() {
		try {

			if(!isset($this->_params['uid'])) {
				throw new UserException("Unset vars", __FUNCTION__);
			}

			$user = new UserObject($this->_mysqli);
			$user->uid = $this->_params['uid'];
			$user->load();

			return Array ( 
				"email" => $this->email,
				"first_name" => $this->first_name,
				"last_name" => $this->last_name,
				"year" => $this->year,
				"join_date" => $this->join_date,
				"permission" => $this->permission,
				"verified" => $this->verified
			);
		} catch (Exception $e) {
			return $e;
		}
	}

	public function updateUser(){
		try {
			if(isset($_SESSION['uid'], $this->_params['first_name'], $this->_params['last_name'], $this->_params['gender'])) {
				return $this->updateInfo();
			}

			if(isset($_SESSION['uid'], $this->_params['password'])){
				return $this->updatePassword();
			}

			throw new UserException("Unset vars", __FUNCTION__);

		} catch(Exception $e) {
			return $e;
		}
	}

	private function updateInfo(){
		$user = new UserObject();

		$user->uid = $_SESSION['uid'];
		$user->first_name = filter_var($this->_params['first_name'], FILTER_SANITIZE_STRING);
		$user->last_name = filter_var($this->_params['last_name'], FILTER_SANITIZE_STRING);
		$user->gender = filter_var($this->_params['gender'], FILTER_SANITIZE_STRING);

		return $user->updateInfo();
	}

	private function updatePassword(){
		$user = new UserObject();

		$user->uid = $_SESSION['uid'];
		$user->password = $this->_params['password'];

		return $user->updatePassword();
	}

	public function logoutUser() {
		$user = new UserObject($this->_mysqli);
		if ($user->loginCheck() == true) {
			return $user->logout();
		}
	}
}