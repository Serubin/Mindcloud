<?php
/******************************************************************************
 * NotificationObject.php
 * @author Michael Shullick, Solomon Rubin
 * 28 February 2015
 * Model representation of a notifcation.
 *****************************************************************************/

class NotificationObject {

	private $_mysqli;

	// member vars
	public $id;
	public $uid;
	public $url;
	public $message;
	public $time;

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * load()
	 * loads all fields for NotificationObjects
	 */
	public function load(){
		if(!isset($this->id)) {
			throw new UserException("Unset vars: id", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("SELECT `id`, `uid`, `url`, `message`, `time` FROM `user_notifications` WHERE `id` = ? LIMIT 1")) {
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows < 1) {
			throw new UserException("No rows returned", __FUNCTION__);
		}

		$stmt->bind_result($db_id, $db_uid, $db_url, $db_message, $db_time);
		$stmt->fetch();

		$this->id = $db_id;
		$this->uid = $db_uid;
		$this->url = $db_url;
		$this->message = $db_message;
		$this->time = $db_time;

		$stmt->close();
	}

	/**
	 * create()
	 * Creates a new notification
	 */
	public function create(){
		if(!isset($this->uid, $this->url, $this->message)) {
			throw new UserException("unset vars: uid, url, message, time");
		}

		if($stmt = $this->_mysqli->prepare("INSERT INTO `user_notifications` (`uid`, `url`, `message`) VALUES (?,?,?)")){
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("iss", $this->uid, $this->url, $this->message);
		$this->execute();

		$this->id = $this->_mysqli->insert_id;
		$stmt->close();
	}

	/**
	 * fetchNotifications()
	 * returns a list of ids for a notification that is attached to a user
	 */
	public function fetchNotifications(){
		if(!isset($this->uid)){
			throw new UserException("Unset vars: uid", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("SELECT `id`, `uid` FROM `user_notifications` WHERE `uid` = ?")) {
			throw new UserException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->uid);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($db_id, $db_uid);

		$notifications = Array();

		while($stmt->fetch()){
			array_push($notifications, $db_id);
		}

		$stmt->close();

		return $notifications;
	}
}