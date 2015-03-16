<?php
/******************************************************************************
* SolutionObject.php
 * @author Michael Shullick, Solomon Rubin
 * 20 Febuary 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/


class SolutionObject {

	private $_mysqli;

	// member vars
	public $id;
	public $problem_id;
	public $shorthand; // Short hand title/url
	public $title;
	public $description; // TODO strip tags on submit to filter out html
	public $created;
	public $creator;
	public $status;

	public $userVote;
	// TODO
	// TODO: content handlers
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

	public function create() {
		try {
			// Checks that all required post variables are set
			if (!isset($this->problem_id, $this->shorthand, $this->title, $this->description, $this->creator)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}

			// Prepares variables
			$this->shorthand = strtolower($this->shorthand);

			// Submits solution information
			if (!$stmt = $this->_mysqli->prepare("INSERT INTO solutions (`pid`, `shorthand`, `title`, `description`, `creator`) VALUES (?,?,?,?,?)")) {
				throw new SolutionException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param('isssi', $this->problem_id, $this->shorthand, $this->title, $this->description, $this->creator);
			$stmt->execute();
				
			$stmt->close();

			$this->id = $this->_mysqli->insert_id;

			return true;
			// Report any failure
		} catch (Exception $e) {
			return $e;
		}
	}

	public function load() {
		try {
			// Checks that all required post variables are set
			if (!isset($this->id)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}
			// fetches all data for solutions
			if (!$stmt = $this->_mysqli->prepare("SELECT * FROM solutions WHERE `id` = ?")) {
				throw new SolutionException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->store_results();

			// stores results from query in variables corresponding to statement
			$stmt->bind_result($db_id, $this->problem_id, $this->shorthand, $this->title, $this->description, $this->created, $this->creator, $this->status);
			$stmt->fetch();

			$stmt->close();

			return true;
		} catch (Exception $e) {
			return $e;
		}
	}

	public function update() {
		try{
			// Checks that all required post variables are set
			if (!isset($this->id, $this->shorthand, $this->title, $this->description, $this->status)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}

			// Prepares variables
			$this->shorthand = strtolower($this->shorthand);

			if ($stmt = $this->_mysqli->prepare("UPDATE solutions SET `shorthand`=?,`title`=?,`description`=?,`status`=? WHERE `id` = ?")) {
				throw new SolutionException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("sssii", $this->shorthand, $this->title, $this->description, $this->status,$this->id);
			$stmt->execute();

			$stmt->close();

			return true;
		} catch (Exception $e) {
			return $e;
		}
	}

	public function voteUp(){
		try{
			// Checks that all required post variables are set
			if (!isset($this->id)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}

	}
	public function getId(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->shorthand)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}

			// Prepares variables
			$this->shorthand = strtolower($this->shorthand);

			if ($stmt = $this->_mysqli->prepare("SELECT `id`, `shorthand` FROM solutions WHERE `shorthand` = ?")) {
				throw new SolutionException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("s", $this->shorthand);
			$stmt->execute();
			$stmt->store_results();

			// stores results from query in variables corresponding to statement
			$stmt->bind_result($db_id, $db_shorthand);
			$stmt->fetch();

			$rows = $stmt->num_rows;

			$stmt->close();

			if($rows < 1)
				return false;

			// saves data
			$this->id = $db_id;

			return true
		} catch(Exception $e) {
			return $e;
		}
	}

	public function validateShorthand(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->shorthand)) {
				throw new SolutionException("unset vars.", __FUNCTION__);
			}

			// Prepares variables
			$this->shorthand = strtolower($this->shorthand);

			if ($stmt = $this->_mysqli->prepare("SELECT `shorthand` FROM solutions WHERE `shorthand` = ?")) {
				throw new SolutionException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("s", $this->shorthand);
			$stmt->execute();
			$stmt->store_results();

			$stmt->bind_result($db_shorthand);
			$stmt->fetch();

			$rows = $stmt->num_rows;

			$stmt->close();

			if($rows >= 1)
				return false;

			return true
		} catch(Exception $e) {
			return $e;
		}
	}

}



