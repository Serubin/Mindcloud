<?php
/******************************************************************************
* SolutionObject.php
 * @author Michael Shullick, Solomon Rubin
 * 20 Febuary 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/
require_once "include/contributors.php";
require_once "models/ProblemObject.php";

class SolutionObject {

	private $_mysqli;

	// member vars
	public $id;
	public $problem_id;
	public $problem;
	public $shorthand; // Short hand title/url
	public $title;
	public $description; // TODO strip tags on submit to filter out html
	public $created;
	public $contributors;
	public $status;

	public $score;
	public $userVote;

	public $related_solutions;
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
		if (!$stmt = $this->_mysqli->prepare("INSERT INTO solutions (`pid`, `shorthand`, `title`, `description`) VALUES (?,?,?,?)")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param('isss', $this->problem_id, $this->shorthand, $this->title, $this->description);
		$stmt->execute();

		$this->id = $this->_mysqli->insert_id;

		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `contributors`(`cid`, `uid`, `association`) VALUES (?,?,?)")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("iis", $this->id, $this->creator, Contributors::$CREATOR);
		$stmt->execute();

		return true;
	}

   /**
	* loadFull()
	* Loads all of the necessary problem data for displaying on a dedicated page.
	*/
	public function loadFull() {
		// try to load problem with an id
		if (!isset($this->id, $_SESSION['uid'])) {
			throw new SolutionException("Unset variable: ID", __FUNCTION__);
		}

		// fetch from the db the information about this problem based on its id
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `pid`, `shorthand`, `title`, `description`, `created`, `status`, `current_trial` FROM `solutions` WHERE `id` = ? LIMIT 1")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}
		$stmt->bind_param("i", $this->id);

		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows != 1) {
			throw new SolutionException("Unable to fetch solution data: " . $stmt->num_rows . " returned. id: " . $this->id, __FUNCTION__);
		}

		$stmt->bind_result($id, $pid, $shorthand, $title, $description, $created, $status, $current_trial);
		$stmt->fetch();

		// Set this object's member vars
		$this->problem_id = $pid;
		$this->shorthand = $shorthand;
		$this->title = $title;
		$this->description = $description;
		$this->created = $created;
		$this->status = $status;
		$this->trial_no = $current_trial;

		$stmt->close();

		// Fetch contributors
		$contributors = Array();

		if (!$stmt = $this->_mysqli->prepare("SELECT `cid`, `uid`, `association` FROM `contributors` WHERE `cid` = ?")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}
		$stmt->bind_param("i", $this->id);

		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($cid_db, $uid_db, $db_association);

		while($stmt->fetch()) {
			$user = new UserObject($this->_mysqli);
			$user->uid = $uid_db;
			$user->load();

			array_push($contributors, Array(
				"association" => $db_association, 
				"user" => $user
			));
		}

		$stmt->close();

		$this->contributors = $contributors;

		// set score
		$this->score = $this->getScore();

		$this->current_user_vote = Vote::fetchVote($this->_mysqli, "SOLUTION", $this->id, $_SESSION['uid']);

		$this->problem = new ProblemObject($this->_mysqli);
		$this->problem->id = $this->problem_id;
		$this->problem->loadPreview();
		// get array of afficiliated thread ids
		//$this->getThreads();

		$related = $this->getRelatedSolutions();

		$this->related_solutions = Array();
		foreach($related as $value) {
			$related_solution = new SolutionObject($this->_mysqli);
			$related_solution->id = $value;
		
			$related_solution->loadPreview();

			array_push($this->related_solutions, $related_solution);
		}
	}

	/**
	 * getPreview()
	 * For obtaining less innformation to display on dashboard.
	 */
	public function loadPreview() {
		if (!isset($this->id)) {
			throw new SolutionException("Could not load preview: id not set.", __FUNCTION__);
		}

		// fetch from the db the information about this problem
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `pid`, `shorthand`, `title`, `description`, `created` FROM `solutions` WHERE `id` = ? LIMIT 1")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows != 1) {
			throw new SolutionException("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
		}

		$stmt->bind_result($id, $pid, $shorthand, $title, $description, $created);
		$stmt->fetch();


		// Set this object's member vars
		$this->problem_id = $pid;
		$this->shorthand = $shorthand;
		$this->statement = $title;
		$this->description = $description;
		//$this->created = $created;

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

		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `shorthand` FROM `solutions` WHERE `shorthand` = ?")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("s", $this->shorthand);
		$stmt->execute();
		$stmt->store_result();

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
	public function vote($user, $val) {
		if (!isset($this->id))
			throw new SolutionException("Id or creator not set", __FUNCTION__);

		// submit vote
		return Vote::addVote($this->_mysqli, "SOLUTION", $this->id, $user, $val);
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
		$stmt->store_result();

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


	public function getRelatedSolutions(){
		if(!isset($this->id, $this->problem_id)) {
			throw new SolutionException("Unset var: id, problem_id", __FUNCTION__);
		}

		if(!$stmt = $this->_mysqli->prepare("SELECT `id`, `pid` FROM `solutions` WHERE `pid` = ? AND NOT `id` = ?")) {
			throw new SolutionException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("ii", $this->problem_id, $this->id);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($db_id, $db_pid);

		$result = Array();
		while($stmt->fetch()) {
			array_push($result, $db_id);
		}

		$stmt->close();

		return $result;
	}
}



