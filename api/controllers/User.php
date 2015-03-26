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
				$this->_params['last_name'], $this->_params['year'], $this->_params['gender'], $this->_params['captcha'])) {
				error_log(json_encode($this->_params));
				throw new UserException("Unset vars", __FUNCTION__);
			}

			if($this->_params['captcha'] != $_SESSION['captcha']) {
				return "captcha-mismatch";
				//throw new UserException("Captcha mismatch. Are you a Robot?", __FUNCTION__);
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
				return "duplicate-email";
				//throw new UserException("Duplicate email.", __FUNCTION__);
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

					  <a href='https://mindcloud.io/login/validate/" . hash("sha512", $new_user->uid . $new_user->first_name . $new_user->last_name . $new_user->email) . "/" . str_replace(".", "-", $new_user->email) . "'>Validate your account!</a> <br/>

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

			// require captcha match if more than 3 attempts
			if(isset($_SESSION['login-attempts']) && $_SESSION['login-attempts'] > 3){
				if(!isset($this->_params['login_captcha']))
					throw new UserException("Unset variables: captcha");

				error_log("doing captcha stuff");
				// Checks for captcha consistency
				if($this->_params['login_captcha'] != $_SESSION['captcha'])
					return false;
			}

			$user = new UserObject($this->_mysqli);
			$user->email = $this->_params['email'];
			$user->password = $this->_params['password'];
			
			$result = $user->login();

			// track login attempts 
			if(!$result) {
				if(!isset($_SESSION['login-attempts'])) 
					$_SESSION['login-attempts'] = 1;
				else
					$_SESSION['login-attempts'] += 1;

				// if login attempts is greater than 3, tell client to require captcha
				if($_SESSION['login-attempts'] > 3)
					$result = "captcha";
			} else {
				unset($_SESSION['login-attempts']);
			}

			$_SESSION['uid'] = $user->uid;

			return $result;
		} catch (Exception $e){
			return $e;
		}
	}

	/* checkUser()
	 * Checks whether the user is logged in.
	 */
	public function checkUser() {
		try {
			$user = new UserObject($this->_mysqli);
			return $user->loginCheck();
		} catch (Exception $e){
			return $e;
		}
	}

	public function verifyUser(){
		try{
			if(!isset($this->_params['email'], $this->_params['hash'])){
				throw new UserException("Unset vars", __FUNCTION__);
			}
				$user = new UserObject($this->_mysqli);
				$user->email = filter_var($this->_params['email'], FILTER_SANITIZE_STRING);
				$user->getIdFromEmail();
				$user->load();

				$local_hash = hash('sha512', $user->uid . $user->first_name . $user->last_name . $user->email);

				if($local_hash == $this->_params['hash']) {
					$user->verified = true;
					return $user->updateVerify();
				}

				return false;
		} catch (Exception $e){
			return $e;
		}
	}

	/*
	 * loadUser()
	 * Sends to the client a json array of the current list name and join_date,
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
				"first_name" => $user->first_name,
				"last_name" => $user->last_name,
				"join_date" => $user->join_date,
			);
		} catch (Exception $e) {
			return $e;
		}
	}

	/*
	 * getCurrentUser()
	 * retrieves the id of the current user from session var
	 */
	public function getCurrentUser(){
		try {
			if(!isset($_SESSION['uid'])) {
				throw new UserException("User not logged in", __FUNCTION__);
			}

			return $_SESSION['uid'];
		} catch (Exception $e) {
			return $e;
		}
	}

	/*
	 * updateUser()
	 * updates either password or basic info of current user 
	 */
	public function updateUser(){
		try {
			if(isset($_SESSION['uid'], $this->_params['first_name'], $this->_params['last_name'], $this->_params['gender'],$this->_params['password'])) {
				return $this->updateInfo();
			}

			if(isset($_SESSION['uid'], $this->_params['password'], $this->_params['new_password'])){
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
		$user->password = $this->_params['password'];

		if(!$this->verifyPassword())
			return false;

		$user->first_name = filter_var($this->_params['first_name'], FILTER_SANITIZE_STRING);
		$user->last_name = filter_var($this->_params['last_name'], FILTER_SANITIZE_STRING);
		$user->gender = filter_var($this->_params['gender'], FILTER_SANITIZE_STRING);

		return $user->updateInfo();
	}

	private function updatePassword(){
		$user = new UserObject();

		$user->uid = $_SESSION['uid'];
		$user->password = $this->_params['password'];

		if(!$this->verifyPassword())
			return false;

		$user->password = $this->_params['new_password'];
		return $user->updatePassword();
	}

	public function logoutUser() {
		$user = new UserObject($this->_mysqli);
		if ($user->loginCheck() == true) {
			return $user->logout();
		}
	}
}