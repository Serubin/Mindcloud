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
	// TODO modify for contributors

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	public function create() {
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

		$this->id = $this->_mysqli->insert_id;

		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `contributors`(`cid`, `type`, `uid`, `association`) VALUES (?,'PROBLEM',?,?,?)")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("iis", $this->id, $this->creator, Contributors::CREATOR);
		$stmt->execute();

		return true;
	}

   /**
	* loadFull()
	* Loads all of the necessary problem data for displaying on a dedicated page.
	*/
	public function loadFull() {
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
	}

	/**
	 * getPreview()
	 * For obtaining less innformation to display on dashboard.
	 */
	public function loadPreview() {
		if (isset($this->id)) {
			throw new SolutionException("Could not load preview: id not set.", __FUNCTION__);
		}

		// fetch from the db the information about this problem
		if (!$stmt = $this->_mysqli("SELECT `shorthand`, `title`, `description`, `created`, `creator` FROM `solutions` WHERE `id` = ? LIMIT 1")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->bind_result();
		if ($stmt->num_rows != 1) {
			throw new SolutionException("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
		}

		$stmt->bind_result($shorthand, $title, $description, $created, $creator_id);
		$stmt->fetch();


		// Set this object's member vars
		$this->shorthand = $shorthand;
		$this->statement = $title;
		$this->description = $description;
		$this->created = $created;

		$stmt->close();
	}

	/**
	 * update()
	 * Updates information in database
	 */
	public function update() {
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
	}


	/**
	 * getId()
	 * Gets the id from the shorthand value
	 */
	public function getId(){
		// Checks that all required post variables are set
		if (!isset($this->shorthand)) {
			throw new SolutionException("Shorthand not set", __FUNCTION__);
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
			throw new SolutionException("Couldn't locate shorthand", __FUNCTION__);

		// saves data
		$this->id = $db_id;
	}

	/**
	 * upvote
	 * Enter an upvote for a problem
	 */
	public function vote($val) {
		if (!isset($this->id, $this->creator))
			throw new SolutionException("Id or creator not set", __FUNCTION__);

		// submit vote
		return Vote::addVote($this->_mysqli, "SOLUTION", $this->id, $this->creator, $val);
	}


	/**
	 * getScore()
	 * Get the difference between upvotes and downvotes.
	 */
	public function getScore() {
		// check that we have the problem id
		if (!isset($this->id)) {
			throw new ProblemException("Unset id", __FUNCTION__);
		}

		// get score
		$this->score = Vote::fetchScore($this->_mysqli, "SOLUTION" , $this->id);
		return $this->score;
	}

	/**
	 * validateShorthand()
	 * Ensures shorthand is avalible
	 */
	public function validateShorthand(){
		// Checks that all required post variables are set
		if (!isset($this->shorthand)) {
			throw new SolutionException("Shorthand not set", __FUNCTION__);
		}

		// Prepares variables
		$this->shorthand = strtolower($this->shorthand);

		if (!$stmt = $this->_mysqli->prepare("SELECT `shorthand` FROM solutions WHERE `shorthand` = ?")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("s", $this->shorthand);
		$stmt->execute();
		$stmt->store_results();

		$rows = $stmt->num_rows;

		$stmt->close();

		if($rows >= 1)
			return false;

		return true;
	}
	// TODO add constants for association
	public function addContributor($uid, $association){
		if(!isset($this->id)){
			throw new SolutionException("Unset var: Id", __FUNCTION__);
		}

		if(!$stmt = $this->_mysql->prepare("INSERT INTO `contributors`(`cid`, `type`, `uid`, `association`) VALUES (?,?,?,?")){
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("isis", $this->id, "SOLUTION", $uid, $association);
		$stmt->execute();
	}

}



