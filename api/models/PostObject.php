<?php
/******************************************************************************
 * PostObject.php
 * @author Michael Shullick, Solomon Rubin
 * 28 February 2015
 * Model representation of a discussion thread.
 *****************************************************************************/

class PostObject {

	private $_mysqli;

	// member vars
	public $id;
	public $uid;
	public $thread_id;
	public $body;
	public $status;
	public $created;

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * create this post in the database
	 */
	public function create() {

		// check that we have everything we need 
		if (!isset($this->uid, $this->thread_id, $this->body)) {
			throw new PostException("Unset vars in post creation", __FUNCTION__);
		}

		// prepare sql statement
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `posts` (`uid`, `thread_id`, `body`) VALUES (?, ?, ?)")) {
			throw new PostException("Prepare failed", __FUNCTION__);
		}
		$stmt->bind_param("iis", $this->uid, $this->thread_id, $this->body);

		// submit query
		$stmt->execute();

		// set this post's id
		$this->id = $this->_mysqli->insert_id;

		// finish
		return true;

	}

	/**
	 * flag this post
	 */
	public function flag() {
		try {

		} catch (PostException $e) {

		}
	}

	/**
	 * delete/hide this post
	 */
	public function hide() {
		try {

		} catch (PostException $e) {

		}
	}

	/**
	 * edit post
	 */
	public function update() {
		try {

		} catch (PostException $e) {

		}
	}
}