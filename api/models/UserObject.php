<?php
/******************************************************************************
 * UserObject.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a user.
 ******************************************************************************/

require_once "include/PasswordHash.php"; 
	
class UserObject
{
	public $uid;
	public $email;
	public $password;
	public $first_name;
	public $last_name;
	public $year;
	public $join_date;
	public $permission;
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

		// Checks that all required post variables are set
		if (!isset($this->email, $this->password, $this->first_name, $this->last_name, $this->year)) {
			throw new UserException("unset vars.", __FUNCTION__);
		}

		// Lowercase email
		$this->email = strtolower($this->email);

		// Create a random salt, hash passwd
		$password = create_hash($this->password);

		// Join data
		$date = date('Y-m-d H:i:s');

		$this->uid = 0;

		// Submit login information
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `user_accounts` (`email`, `password`) VALUES (?, ?)")) {
			throw new UserException($this->_mysqli->error, "REGISTER");
		}

		$stmt->bind_param('ss', $this->email, $password);
		$stmt->execute();

		$this->uid = $this->_mysqli->insert_id;

		// reuse same stmt var
		$stmt->close();

		// Submit user data
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `user_data` (`id`, `first_name`, `last_name`, `year`, `join_date`) VALUES (?, ?, ?, ?, ?)")) {
			throw new UserException($this->_mysqli->error, "REGISTER");
		}

		$stmt->bind_param('issss', $this->uid, $this->first_name, $this->last_name, $this->year, $date);
		$stmt->execute();
		
		// Submit user data
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `user_meta` (`id`) VALUES (?)")) {
			throw new UserException($this->_mysqli->error, "REGISTER");
		}

		$stmt->bind_param('i', $this->uid);
		$stmt->execute();

		// Return true on success
		return true;

	}

	/* login() 
	 * If the user passes the correct credentials, stores a 
	 * session id and token on the client.
	 * @returns true/false/unverified
	 */
	public function login() {

		$result = array();

		// Check that requires vars are set
		if (!isset($this->email, $this->password)) {
			throw new Exception("Unset vars.");
		}

		// Lowercase email
		$this->email = strtolower($this->email);

		// prepare SQL statement 
		if (!$stmt = $this->_mysqli->prepare("SELECT `user_accounts`.`id`, `user_accounts`.`password`, `user_accounts`.`email`, `user_meta`.`id`, `user_meta`.`verified` FROM `user_accounts` INNER JOIN `user_meta` ON `user_accounts`.`id`=`user_meta`.`id` WHERE `email` = ? LIMIT 1")) {
			throw new UserException("Prepare failed." . $this->_mysqli->error, "LOGIN");
		}

		$stmt->bind_param('s', $this->email); // puts the email in place of the '?'
		$stmt->execute();
		$stmt->store_result();
		
		// stores results from query in variables corresponding to statement
		$stmt->bind_result($uid, $db_password, $db_email, $uid, $db_verified);
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
		$verified  = ($db_verified === 1 ? true : false);
		if (!$verified){
			return "unverified"; // TODO how to specify the need to verify? should we use this guy or the one below
		}

		$stmt->close();

		// Deletes previous sessions
		if(!$stmt = $this->_mysqli->prepare("DELETE FROM `user_sessions` WHERE `uid`=? AND `ip` = ?")){
			throw new UserException($this->_mysqli->error, "LOGIN");
		}

		$stmt->bind_param('is', $uid, $_SERVER['REMOTE_ADDR']);
		$stmt->execute();

		// Calculates login length - 2 weeks (unix timestamp)
		$expire = time() + (60*60*24*7*2);
		$sid = hash('sha256', $uid . $this->email . time()); 
		$time = time();
		
		if(!$stmt = $this->_mysqli->prepare("INSERT INTO `user_sessions`(`id`, `uid`, `timestamp`, `expire`, `ip`) VALUES (?, ?, ?, ?, ?)")){
			throw new UserException($this->_mysqli->error, "LOGIN");
		}

		$stmt->bind_param('siiis', $sid, $uid, $time, $expire, $_SERVER['REMOTE_ADDR']);
		$stmt->execute();

		// Store user id, verified status
		$this->uid = $uid;
		$this->verified = $verified;

		// create session identification
		if (!setcookie('stoken', $sid, $expire, "/", DOMAIN, SECURE, true)) {
			throw new UserException ("Failed to set ctoken cookie.", "LOGIN");
		}

		$stmt->close();
		/*
		if (!$verified) { // only if the user actually has a list at this point
			//TODO do an unverified action?? or move above
		} else {
			// Password is correct, but this is the user's first log in
			return "unverified";
		}*/

		// Return true on success
		return true;
	}
	
	/* loginCheck()
	 * Verify whether this given user is logged in
	 * Returns true or false depending on whether the user is presently logged in,
	 * or init if the user is logged in and needs initialized.
	 */
	public function loginCheck() {

		// Retrieve stoken
		if (!isset($_COOKIE['stoken']))
			return false;

		$sid = $_COOKIE['stoken'];
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `uid`, `ip` FROM user_sessions WHERE `id` = ? AND `ip` = ? LIMIT 1")) {
			 throw new UserException("Prepare failed.", __FUNCTION__);
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

		// Save in session
		$_SESSION['uid'] = $this->uid;

		// If the user hasn't been initalized, do that now
		if ($this->checkVerified()) 
			return "unverified";
		// All checks out
		return true;
	}

	/**
	 * updateVerify()
	 * updates verify in meta db
	 */
	public function updateVerify(){

		if(!isset($this->uid, $this->verified)) {
			throw new UserException("Unset vars", __FUNCTION__);
		}

		// "UPDATE M SET M.`verified`=? FROM user_meta as M INNER JOIN user_accounts AS A ON A.`id`=M.`id` WHERE `email` = ?"
		
		if(!$stmt = $this->_mysqli->prepare("UPDATE user_meta SET `verified`=? WHERE `id` = ?")) {
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}
		$verifiedBool = $this->verified ? 1 : 0;
		$stmt->bind_param( "ii", $verifiedBool, $this->uid );
		$stmt->execute();

		return true;
	}

	/* checkVerified()
	 * Checks database to verify that the user has verified their email
	 *
	 * @returns true or false based on databse entry
	 */
	public function checkVerified(){
		// TODO unnestify ifs
		$result = array();
	
		// Checks that required vars
		if (!isset($this->uid)) {
			throw new UserException("Unsert vars", "VERIFY");
		}

		// prepared SQL statement
		if (!$stmt = $this->_mysqli->prepare("SELECT id, verified FROM user_meta WHERE `id` = ? LIMIT 1")) {
			throw new UserException($this->_mysqli->error, "VERIFY");
		}

		$stmt->bind_param('i', $this->uid);
		$stmt->execute();
		$stmt->store_result();
		
		// stores results from query in variables
		$stmt->bind_result($db_uid, $verified);
		$stmt->fetch();

		if($stmt->num_rows != 1){
			throw new UserException("More than one row returned", "VERIFY");
		}
		// Returns true false based on database
		return ($verified === 1) ? true : false;
	}

	/* checkEmail
	 * This functions is to verify that the email specificed is not already in the database
	 *
	 * @returns true: when no email other email is found or false: when another email is found
	 */
	public function checkEmail(){
		// Checks that required vars
		if (!isset($this->email)) {
			throw new UserException("Unsert vars", "CHECK");
		}

		// Lowercase email
		$this->email = strtolower($this->email);

		// Check for an existing email
		if (!$stmt = $this->_mysqli->prepare("SELECT `email` FROM user_accounts WHERE `email` = ? LIMIT 1")) {
			throw new Exception($this->_mysqli->error);
		}

		$stmt->bind_param('s', $this->email);
		$stmt->execute();
		$stmt->store_result(); // DO THIS FUCKER.

		// if a user already exists with this email
		if ($stmt->num_rows >= 1) {
			return false;
		}
		$stmt->close();
		// No other matching email found
		return true;
	}

	/**
	* updateInfo()
	* Updates specific values within user: first and last name, and gender
	*/
	public function updateInfo(){
		if(!isset($this->uid, $this->first_name, $this->last_name, $this->gender)){
			throw new UserException("Unset vars", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("UPDATE user_data SET `first_name`=?, `last_name`=?, `gender`=? WHERE `id` = ?")){
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("sssi", $this->first_name, $this->last_name, $this->gender, $this->uid);
		$stmt->execute();

		return true;
	}

	/**
	 * updatePassword()
	 * Updates password
	 */
	public function updatePassword(){
		if(!isset($this->uid, $this->password)){
			throw new UserException("Unset vars", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("UPDATE user_accounts SET `password` = ? WHERE `id` = ?")){
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$this->password = create_hash($this->password);

		$stmt->bind_param("si", $this->password, $this->uid);
		$stmt->execute();

		return true;
	}

	/* loadUser()
	 * This function loads user data into memory.
	 */
	public function load() {
		if (!isset($this->uid)) {
			throw new UserException("Unset vars: UID", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("SELECT * FROM user_accounts INNER JOIN user_data ON user_accounts.id = user_data.id INNER JOIN `user_meta` ON user_data.id=user_meta.id WHERE user_accounts.id = ?")){
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->uid);
		$stmt->execute();
		$stmt->store_result();
		
		error_log($stmt->num_rows);

		if($stmt->num_rows < 1)
			throw new UserException("User not found", __FUNCTION__);

		$stmt->bind_result($db_id, $db_email, $db_password, $db_id, $db_first_name, $db_last_name, $db_gender, $db_year, $db_join_date, $db_permission, $db_id, $db_verified);
		$stmt->fetch();

		// Will not store email for user privacy
		//$this->email = $db_email;
		$this->first_name = $db_first_name;
		$this->last_name = $db_last_name;
		$this->year = $db_year;
		$this->join_date = $db_join_date;
		$this->permission = $db_permission;
		$this->verified = $db_verified;

		return true;
	}

	/*
	 * getIdFromEmail()
	 * retreives id from
	 */
	public function getIdFromEmail(){
		if (!isset($this->email)) {
			throw new UserException("Unset vars: email", __FUNCTION__);
		}

		// Fetches ID. Work around for inner join
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `email` FROM user_accounts WHERE `email` = ? LIMIT 1")) {
			throw new UserException($this->_mysqli->error);
		}

		$stmt->bind_param('s', $this->email);
		$stmt->execute();
		$stmt->store_result(); // DO THIS FUCKER.

		// if a user already exists with this email
		if ($stmt->num_rows > 1) {
			return false;
		}

		// Bind/Fetch results
		$stmt->bind_result($db_uid, $db_email);
		$stmt->fetch();

		$this->uid = $db_uid;

		$stmt->close();

		return true;
	}
	/* logout()
	 * Deletes the session and cookie arrays, the cookies, and 
	 * destroys the session.
	 */
	public function logout() {
		if(!isset($_COOKIE['stoken'])){
			throw new UserException("Unset vars", __FUNCTION__);
		}
		// Remove from database
		if(!$stmt = $this->_mysqli->prepare("DELETE FROM `user_sessions` WHERE id = ?")) {
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}
		
		$stmt->bind_param('i', $_COOKIE['stoken']);
		$stmt->execute();

		// Nullify cookie
		setcookie('stoken', "", time()-9999999, "/", DOMAIN, SECURE, true);

		return true;
	}
}
