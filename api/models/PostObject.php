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
		if (!isset($this->uid, $this->body)) {
			error_log(json_encode($this));
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
	 * load the information of this post based on its id
	 */
	public function load() {
		if (!isset($this->id)) {
			throw new PostException("Cannot load post, no id provided.", __FUNCTION__);
		}

		// prepare sql
		if (!$stmt = $this->_mysqli->prepare("SELECT * FROM `posts` WHERE `id` = ?")) {
			throw new PostException("Cannot load post: " . $this->_mysqli->error);
		}

		// bind
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		// check for a single post returned, not 0 or multiple
		if ($stmt->num_rows != 1) {
			throw new PostException("Cannot load post: " . $stmt->num_rows . "returned", __FUNCTION__);
		}

		// store results
		$stmt->bind_result($uid, $thread_id, $body, $created, $status);
		$stmt->fetch();
		$this->uid = $uid;
		$this->thread_id = $thread_id;
		$this->body = $body;
		$this->created = $created;
		$this->status = $status;

		return true;

	}

	public function loadFromThreadId() {
		if (!isset($this->thread_id)) {
			throw new PostException("Could not first post: thread id not set");
		}

		// prepare statement to find id to load from
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `uid`, `body`, `created`, `status` FROM `posts` WHERE `thread_id` = ? ORDER BY `created` ASC LIMIT 1")) {
			throw new PostException($this->_mysqli->error, __FUNCTION__);
		}

		// bind
		$stmt->bind_param("i", $this->thread_id);
		$stmt->execute();
		$stmt->store_result();

		// check for a single post returned, not 0 or multiple
		if ($stmt->num_rows != 1) {
			throw new PostException("Cannot load post: " . $stmt->num_rows . "returned", __FUNCTION__);
		}

		// store results
		$stmt->bind_result($id, $uid, $body, $created, $status);
		$stmt->fetch();
		$this->id = $id;
		$this->uid = $uid;
		$this->body = $body;
		$this->created = $created;
		$this->status = $status;

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