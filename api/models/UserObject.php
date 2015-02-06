<?php
/******************************************************************************
 * UserObject.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a user.
 * !!! NOT YET ADAPTED !!!! -- Inprogress
 ******************************************************************************/

require_once "/var/www/api/include/PasswordHash.php"; 
	
class UserObject
{
	public $uid;
	public $email;
	public $password;
	public $name;
	public $birthday;
	public $join_date;
	public $verified;
	private $_mysqli;

	// Constructor
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/*
	 * register()
	 * Submits the user data to the database and creates the user.
	 * Checks that all of the appropriate variables have been set.
	 * Returns true on completion or error on fail.
	 */
	public function register() {

		try {
			// Checks that all required post variables are set
			if (!isset($this->email, $this->password, $this->name, $this->birthday, $this->join_date)) {
				throw new UserException("unset vars.", "REGISTER");
			}

			// Create a random salt, hash passwd
			$password = create_hash($this->password);

			// Join data
			$date = date('Y-m-d H:i:s');

			$uid = 0;

			// Submit login information
			if ($stmt = $this->_mysqli->prepare("INSERT INTO `user_accounts` (`email`, `password`) VALUES (?, ?)")) {
				$stmt->bind_param('ss', $this->email, $password);
				$stmt->execute();
			} else
				throw new UserException($this->_mysqli->error, "REGISTER");

			$uid = $this->_mysqli->insert_id;

			// reuse same stmt var
			$stmt->close();

			// Submit user data
			if ($stmt = $this->_mysqli->prepare("INSERT INTO `user_data` (`id`, `name`, `birthday`, `join_date`) VALUES (?, ?, ?, ?)")) {
				$stmt->bind_param('isss', $uid, $this->name, $this->birthday, $this->join_date);
				$stmt->execute();
			} else
				throw new UserException($this->_mysqli->error, "REGISTER");
			
			// Submit user data
			if ($stmt = $this->_mysqli->prepare("INSERT INTO `user_meta` (`id`) VALUES (?)")) {
				$stmt->bind_param('i', $uid);
				$stmt->execute();
			} else
				throw new UserException($this->_mysqli->error, "REGISTER");

			// Return true on success
			return true;

		// Report any failure
		} catch (Exception $e) {
			return $e;
		}
	}

	/* login() 
	 * If the user passes the correct credentials, stores a 
	 * session id and token on the client.
	 * @returns true/false/unverified
	 */
	public function login() {

		$result = array();

		try {
			// Check that requires vars are set
			if (!isset($this->email, $this->password)) {
				throw new Exception("Unset vars.");
			}

			// prepare SQL statement 
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `password` FROM `login` WHERE `email` = ? LIMIT 1")) {
				// SQL Error catch
				throw new UserException("Prepare failed." . $this->_mysqli->error, "LOGIN");
			}
			$stmt->bind_param('s', $this->email); // puts the email in place of the '?'
			$stmt->execute();
			$stmt->store_result();
			
			// stores results from query in variables corresponding to statement
			$stmt->bind_result($uid, $db_password);
			$stmt->fetch();

			// TODO Defense against brute-force attacks

			if ($stmt->num_rows != 1) {
				return false; // if there is 0 results
			}
			// Compare the submitted password to the stored password
			if (!validate_password($this->password, $db_password)) {
				return false; // Password is incorrect
			}
			// Check if user has been verified
			$verified  = checkVerified();
			if (!$verified){
				return false; // TODO how to specify the need to verify? should we use this guy or the one below
			}
			// TODO migrate to database session storage

			// Calculates login length - 2 weeks (unix timestamp)
			$expire = time() + (60*60*24*7*2);
			$sid = hash('sha256', $uid . $this->email . time()); 
			
			if(!$stmt = $this->_mysql->prepared("INSERT INTO `user_sessions`(`id`, `uid`, `timestamp`, `expire`, `ip`) VALUES (?, ?, ?, ?, ?)")){
				throw new UserException($this->_mysqli->error, "LOGIN"));
			}

			$stmt->bind_param('siiis', $sid, $uid, time(), $expire, $_SERVER['REMOTE_ADDR']);
			$stmt->execute();

			// Store user id, verified status
			$this->uid = $uid;
			$this->verified = $verified;
		
			// create session identification
			if (!setcookie('stoken', $sid, $expire, "/", "minecloud.io", ,true, true)) {
				throw new UserException ("Failed to set ctoken cookie.", "LOGIN");
			}

			$stmt->close();
			if (!$verified) { // only if the user actually has a list at this point
				//TODO do an unverified action?? or move above
			} else {
				// Password is correct, but this is the user's first log in
				return "unverified";
			}

			// Return true on success
			return true;
		} catch (Exception $e) {
			return $e;
		}
	}
	
	/* login_check()
	 * Verify whether this given user is logged in
	 * Returns true or false depending on whether the user is presently logged in,
	 * or init if the user is logged in and needs initialized.
	 */
	function login_check() {

		try {
			// Retrieve stoken
			$sid = $_COOKIE['stoken'];
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `uid`, `ip` FROM user_sessions WHERE `id` = ? AND `ip` = ? LIMIT 1")) {
				 throw new UserException("Prepare failed.", "CHECK");
			}
			$stmt->bind_param('ss', $sid, $_SERVER['REMOTE_ADDR']);
			$stmt->execute();
			$stmt->store_result();
			
			// If user exists, retreive credentials
			if ($stmt->num_rows != 1) {
					// Not logged in 
	       		return false;
			}
			// Bind/Fetch results
			$stmt->bind_result($db_sid, $db_uid, $db_ip);
			$stmt->fetch();
			
			// Save retreived info
			$this->uid = $db_uid;

			// If the user hasn't been initalized, do that now
			if (checkVerified()) 
				return "unverified";
			// All checks out
			return true;
		} catch (Exception $e) {
			return $e;
		}
	}

	/*
	 * init()
	 * Only called once immediately following a user's registration.
	 * Takes care of the following tasks:
	 * + TODO
	 */
	function init() {
		try {
			// TODO

			// All was successful
			return true;

		} catch (Exception $e) {
			$msg = "User init failed. " . $e->getMessage();
			error_log($msg);
			return false;
		}
	}

	/* checkVerified()
	 * Checks database to verify that the user has verified their email
	 *
	 * @returns true or false based on databse entry
	 */
	public function checkVerified(){
		// TODO unnestify ifs
		$result = array();
		
		try {
			// Checks that required vars
			if (!isset($this->uid)) {
				throw new UserException("Unsert vars", "VERIFY");
			}

			// prepared SQL statement
			if (!$stmt = $this->_mysqli->prepare("SELECT id, verified FROM user_meta WHERE `id` = ? LIMIT 1"){
				throw new UserException($this->_mysqli->error, "VERIFY");
			}
			$stmt->bind_param('i', $this->uid);
			$stmt->execute();
			$stmt->store_results();
			
			// stores results from query in variables
			$stmt->bind_result($db_uid, $verified);
			$stmt->fetch();
		
			if($stmt->num_rows != 1){
				throw new UserException("More than one row returned", "VERIFY")
			}
			// Returns true false based on database
			return ($verified == 1) ? true : false;
		} catch (Exception $e) {
			return $e;
		}
	}

	/* loadUser()
	 * This function returns to the client the following json array:
	 * {
	 *		current_id: current list id
	 *		current_contents: [
	 *							array of list items
	 *							]
	 *		lists: array of list names and ids
	 * }
	 */
	public function load() {
		// TODO should load get all neccessary user info?
		try {
			if (!isset($this->uid)) {
				throw new UserException("Unset vars: UID ", "LOAD");
			}

			// TODO

			return $result;

		} catch (Exception $e) {
			return false;
		}
	}

	/* logout()
	 * Deletes the session and cookie arrays, the cookies, and 
	 * destroys the session.
	 */
	function logout() {

		// Remove from database
		if(!$stmt = $this->_mysql->prepared("DELETE FROM `user_sessions` WHERE id = ?")){
			throw new UserException($this->_mysqli->error, "LOGIN"));
		}
		$stmt->bind_param('i', $sid);
		$stmt->execute();

		// Nullify cookie
		setcookie('stoken', "", time()-9999999, "/", "minecloud.io", ,true, true)
		return true;
	}
}
