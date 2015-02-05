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
				throw new Exception("unset vars."); ;
			}

			// Create a random salt, hash passwd
			$password = create_hash($this->password);

			// Join data
			$date = date('Y-m-d H:i:s');

			$uid = 0;

			// Submit login information
			if ($stmt = $this->_mysqli->prepare("INSERT INTO user_accounts (email, password) VALUES (?, ?)")) {
				$stmt->bind_param('ss', $this->email, $password);
				$stmt->execute();
			} else
				throw new Exception($this->_mysqli->error);

			$uid = $this->_mysqli->insert_id;

			// reuse same stmt var
			$stmt->close();

			// Submit user data
			if ($stmt = $this->_mysqli->prepare("INSERT INTO user_data (id, name, birthday, join_date) VALUES (?, ?, ?, ?)")) {
				$stmt->bind_param('isss', $uid, $this->name, $this->birthday, $this->join_date);
				$stmt->execute();
			} else
				throw new Exception($this->_mysqli->error);
			
			// Submit user data
			if ($stmt = $this->_mysqli->prepare("INSERT INTO user_meta (id) VALUES (?)")) {
				$stmt->bind_param('i', $uid);
				$stmt->execute();
			} else
				throw new Exception($this->_mysqli->error);

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
	 * return an json array of: 
	 * {
	 * 		result: true/false (whether user logged in)
	 *		init: true/false (whether the user has set his/her first list, memberships) 
	 * }
	 */
	public function login() {

		$result = array();

		try {
			// Check that requires vars are set
			if (!isset($this->email, $this->password)) {
				throw new Exception("Unset vars.");
			}

			// prepare SQL statement 
			if ($stmt = $this->_mysqli->prepare("SELECT id, password, init FROM login WHERE email = ? LIMIT 1")) {
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
				
				if($stmt = $this->_mysql->prepared("INSERT INTO `user_sessions`(`id`, `uid`, `timestamp`, `expire`, `ip`) VALUES (?, ?, ?, ?, ?)")){
					$stmt->bind_param('siiis', $sid, $uid, time(), $expire, $_SERVER['REMOTE_ADDR']);
					$stmt->execute();
				} else
					throw new UserException($this->_mysqli->error, "LOGIN"));

				}

				// Store user id, verified status
				$this->uid = $uid;
				$this->verified = $verified;
			
				// create session identification
				if (!setcookie('stoken', $sid, true, true)) {
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

			// Report any failure
			} else {
				throw new UserException("Prepare failed." . $this->_mysqli->error, "LOGIN");
			}
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
			// Check if all session variables have been set
			if (isset($_SESSION['email'], $_SESSION['login_string'], $_SESSION['salt'], $_COOKIE['ctoken'])) {
			
				//$user_id = $_SESSION['user_id'];
				$login_string = $_SESSION['login_string'];
				$email = $_SESSION['email'];
			
				// get current user-agent
				$user_browser = $_SERVER['HTTP_USER_AGENT'];
				
				// get password hash from db
				if ($stmt = $this->_mysqli->prepare("SELECT password FROM login WHERE email = ? LIMIT 1")) {
					$stmt->bind_param('s', $email);
					$stmt->execute();
					$stmt->store_result();
					
					// If user exists, retreive credentials
					if ($stmt->num_rows == 1) {
						$stmt->bind_result($password);
						$stmt->fetch();
						
						// re-create login_check
						$ctoken = create_hash($email . $user_browser, $_SESSION['salt']);
						$login_check = create_hash($password . $user_browser, $_SESSION['salt']);
					
						if (strcmp($ctoken, $_COOKIE['ctoken'] == 0) && strcmp($login_check, $login_string) == 0) {

							// If the user hasn't been initalized, do that now
							if ($_SESSION['init']) 
								return "init";
							else {
								error_log("Login check was successful");
								return true;
							}

		                } else {
		                    // Not logged in 
		           			throw new Exception("Session vars wrong or ctoken incorrect.");
		                }
		            } else {
		                // Not logged in 
		           		throw new Exception("Duplicate or no email found.");
		            }
		        } else {
		            // Not logged in 
		           throw new Exception("Prepare failed.");
		        }
		    } else {
		        // Not logged in 
		        //error("UNSET VARS");
		        error_log("Session: " . json_encode($_SESSION));
		        error_log("cookies: " . json_encode($_COOKIE));
		        throw new Exception("Session vars or cookie not set.");
		    }
		} catch (Exception $e) {
			error_log ("Login check exception: ". $e->getMessage());
			return false;
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
		$result = array();
		
		try {
			// Checks that required vars
			if (!isset($this->uid)) {
				throw new UserException("Unsert vars", "LOGIN");
			}

			// prepared SQL statement
			if ($stmt = $this->_mysqli->prepare("SELECT id, verified FROM user_meta WHERE `id` = ? LIMIT 1"){
				$stmt->bind_param('i', $this->uid);
				$stmt->execute();
				$stmt->store_results();
				
				// stores results from query in variables
				$stmt->bind_result($db_uid, $verified);
				$stmt->fetch();
			
				if($stmt->num_rows == 1){
					// Returns true false based on database
					return ($verified == 1) ? true : false;
				} else {
					throw new UserException("More than one row returned", "LOGIN")
				}
			} else {
				throw new UserException($this->_mysqli->error, "LOGIN");
			}
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
		
		try {
			if (!isset($this->uid, $this->current_list)) {
				throw new Exception("Unset vars. uid: " . $this->uid . "list: " . $this->current_list);
			}

			// TODO

			return $result;

		} catch (Exception $e) {
			$msg = "Getting user's lists failed. " . $e->getMessage();
			error_log($msg);
			return false;
		}
	}

	/* logout()
	 * Deletes the session and cookie arrays, the cookies, and 
	 * destroys the session.
	 */
	function logout() {
	
		// Reset sessions vars
		$_SESSION = array();
		
		// get session params
		$params = session_get_cookie_params();
		
		// Delete the cookie
		setcookie(session_name(), 
			"", time() - 42000, 
			$params["path"], 
			$params["domain"],
			$params["secure"],
			$params["httponly"]);

		setcookie('ctoken', "", time() - 42000);
		
		$_COOKIE = array();

		// Destroy session
		session_destroy();
		return true;
	}
}
