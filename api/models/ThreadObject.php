<?php
/******************************************************************************
 * Threadbject.php
 * @author Michael Shullick, Solomon Rubin
 * 28 February 2015
 * Model representation of a discussion thread.
 *****************************************************************************/

class ProblemObject {

	private $_mysqli;

	// member vars
	public $creator;
	public $heading;
	public $body;
	public $status;
	public $replies;

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
	 */
	public function create() {
		try {

			if (!isset($this->creator, $this->heading, $this->body)) {
				throw new Exception("unset vars", __FUNCTION__);
			}

			// prepare statement and execute
			if ($stmt = $this->_mysqli->prepare("INSERT INTO `threads` (`heading`, `body`, `creator`) VALUES (?, ?, ?)")) {
				throw new Exception($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("sssi", $this->heading, $this->body, $this->creator);
			$stmt->execute();


			}
		} catch (ThreadException $e) {
			return $e;
		}
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