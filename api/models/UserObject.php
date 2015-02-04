<?php
/******************************************************************************
 * UserObject.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a user.
 * !!! NOT YET ADAPTED !!!!
 ******************************************************************************/

require_once "/var/www/api/include/PasswordHash.php"; 
	
class UserObject
{
	public $email;
	public $gender;
	public $name;
	public $password;
	public $uid;
	public $zip;
	public $coords;
	public $memberships;
	public $current_list;
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
			if (!isset($this->email, $this->password, $this->gender, $this->coords, $this->year)) {
				throw new Exception("unset vars."); ;
			}

			// Create a random salt, hash passwd
			$random_salt = create_salt();
			$password = create_hash($this->password, $random_salt);

			// Join data
			$date = date('Y-m-d H:i:s');

			// Submit login information
			if ($stmt = $this->_mysqli->prepare("INSERT INTO login (email, password, salt, init) VALUES (?, ?, ?, 1)")) {
				$stmt->bind_param('sss', $this->email, $password, $random_salt);
				$stmt->execute();
			}
			else {
				throw new Exception($this->_mysqli->error);
			}

			// reuse same stmt var
			$stmt->close();

			// Submit user data
			if ($stmt = $this->_mysqli->prepare("INSERT INTO user_data (gender, join_date, location) VALUES (?, ?, ?)")) {
				$stmt->bind_param('sss', $this->gender, $date, $this->coords);
				$stmt->execute();
			}
			else
				throw new Exception($this->_mysqli->error);
			
			// Return true on success
			return true;

			// Report any failure
		} catch (Exception $e) {
			$msg = $e->getMessage() . " Registration failed.";
			error_log($msg);
			return $msg;
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
			// Check that vars are set
			if (!isset($this->email, $this->password)) {
				throw new Exception("Unset vars.");
			}

			// prepare SQL statement 
			if ($stmt = $this->_mysqli->prepare("SELECT uid, password, salt, init FROM login WHERE email = ? LIMIT 1")) {
				$stmt->bind_param('s', $this->email); // puts the email in place of the '?'
				$stmt->execute();
				$stmt->store_result();
				
				// stores results from query in variables corresponding to statement
				$stmt->bind_result($uid, $db_password, $salt, $init);
				$stmt->fetch();

				if ($stmt->num_rows == 1) { // if there is not 0 results
					
					// Defense against brute-force attacks
					/*if (checkbrute($email, $this->_mysqli) == true) {
						// Account locked; act accordingly
						return false;
					}
					else {*/
						// Compare the submitted password to the stored password
						if (validate_password($this->password, $salt, $db_password)) {
							
							// Password correct; retrieve, store, and sanitize info
							$user_browser = $_SERVER['HTTP_USER_AGENT'];

							// Store user id, init status, email, and a session-specific salt in session array
							$_SESSION['uid'] = $uid;
							$_SESSION['email'] = $this->email;
							$_SESSION['salt'] = create_salt(); // TODO remove use of salt from everywhere but password
							$_SESSION['init'] = $init;
						
							// create session identification
							if (!setcookie('ctoken', create_hash($this->email . $user_browser, $_SESSION['salt']), 
								0, "/", $_SERVER['SERVER_NAME'], true, true)) {
								throw new Exception ("Failed to set ctoken cookie.");
							}
							$_SESSION['login_string'] = create_hash($db_password . $user_browser, $_SESSION['salt']); // TODO remove use of password hashing function for session ids. Rename to session id

							// Obtain the id of the current list and store it as a session variable
							$stmt->close();
							if (!$init) { // only if the user actually has a list at this point
								if ($stmt = $this->_mysqli->prepare("SELECT current_list FROM user_data WHERE uid=?")) {
									$stmt->bind_param("s", $uid);
									$stmt->execute();
									$stmt->store_result();
									if ($stmt->num_rows == 1) {
										$stmt->bind_result($listid);
										$stmt->fetch();
										$_SESSION['list'] = $listid;
										//error_log("list set: " . $_SESSION['list']);

										// Everthing executed correctly
										return true;
									}
									else {
										throw new Exception("Multiple or no lists found.");
									}
								}
								else {
									// Error encountered during prepare
									throw new Exception("Failed to set current list in session; prepare failed: " . $mysqli->error);
								}
							}
								else {
								// Password is correct, but this is the user's first log in
								return "init";
							}
						} else {
							// Password is incorrect
							return false;
						}
					}
					else {
						throw new Exception("Multiple emails found.");
					}
				}
				else {
					throw new Exception("Prepare failed." . $this->_mysqli->error);
				}
		} catch (Exception $e) {
			error_log("Login failed. " . $e->getMessage());
			return false;
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
