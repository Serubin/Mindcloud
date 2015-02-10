<?php
/******************************************************************************
 * ProblemObject.php
 * @author Michael Shullick, Solomon Rubin
 * 6 February 2015
 * Model representation of a problem.
 *****************************************************************************/

class ProblemObject {

	private $_mysqli;

	// member vars
	public $id;
	public $creator;
	public $statement;
	public $description;
	public $creation_datetime;
	public $trial_no;
	// TODO: activity
	// TODO: forum/thread/posts

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * create()
	 * Submits new problem to the database
	 */
	public function create() {
		try {
			if (!isset($this->statement, $this->creator, $this->description)) {
				throw new ProblemException("Unset instance vars", __FUNCTION__);
			}

			if (!$stmt = $this->_mysqli->prepare("INSERT INTO `problems` (`creator`, `statement`, `description`) VALUES (?, ?, ?))")) {
				throw new ProblemException("prepare failed");
			}

			// sanitize strings
			$this->statement = filter_var($this->statement, FILTER_SANITIZE_STRING);
			$this->description = filter_var($this->description, FILTER_SANITIZE_STRING);

			$stmt->bind_param('iss');
			$stmt->execute();

			// return true on succecss
			return true;

		} catch (ProblemException $e) {
			return $e;
		}
	}


}