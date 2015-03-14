<?php
/******************************************************************************
 * Threadbject.php
 * @author Michael Shullick, Solomon Rubin
 * 28 February 2015
 * Model representation of a discussion thread.
 *****************************************************************************/

class ThreadObject {

	private $_mysqli;

	// member vars
	public $id;
	public $op;
	public $title;
	public $status;
	public $created;
	public $problem_id;

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * create this thread in the database
	 * @return an array of the new thread id and the new post id
	 */
	public function create() {
		
		if (!isset($this->op, $this->title, $this->problem_id)) {
				throw new ThreadException("unset vars", __FUNCTION__);
			}

		// prepate statement
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `threads` (`op_id`, `title`, `problem_id`) VALUES (?, ?, ?)")) {
			throw new ThreadException("Insert failed: " + $this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("isi", $this->op, $this->title, $this->problem_id);

		// submit thread
		$stmt->execute();

		// store id
		$this->id = $this->_mysqli->insert_id;

		// finish
		return true;
	}

	/**
	 * load preview 
	 * Make this instance a preview of the thread
	 * (This means only including a snippit in $this->description)
	 */
	public function loadPreview() {

		try {
			// check that we have an id
			if (!$isset($this->id)) {
				throw new ThreadException("ID unset", __FUNCTION__);
			}

			// Ensure that the idea exists
			

		} catch (ThreadException $e) {
			error_log("Getting thread preview failed: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * lock
	 * Makes this thread view-only
	 */ 
	public function lock() {

	}

	/**
	 * submits flag on this thread
	 */
	public function flag() {

	}

	/**
	 * update
	 * set all database values for the thread of this id to the values of 
	 * this instance.
	 */
	public function update() {

	}

	/**
	 *	Checks that a thread of this id exists in the database already. 
	 */
	public function exists() {

	}

	/**
	 * Returns and stores the total number of replies to this thread
	 */
	public function getReplyTotal() {

	}
}