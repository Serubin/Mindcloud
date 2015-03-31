<?php
/******************************************************************************
 * NotificationObject.php
 * @author Michael Shullick, Solomon Rubin
 * 28 February 2015
 * Model representation of a discussion thread.
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

	}

	/**
	 * create()
	 * Creates a new notification
	 */
	public function create(){

	}

	/**
	 * fetchNotifications()
	 * returns a list of ids for a notification that is attached to a user
	 */
	public function fetchNotifications(){

	}
}