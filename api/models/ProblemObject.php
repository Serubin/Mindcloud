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
	public $uid;
	public $title;
	public $shorthand;
	public $description;
	public $created;
	public $tags;
	public $trial_no;

	public $score;
	public $current_user_vote;
	// TODO: activity
	public $threads;

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
	 * On tags:
	 * The javascript should take care of delivering the tag IDs in the request
	 * rather than the strings themselves. 
	 * 
	 */
	public function create() {

		if (!isset($this->title, $this->shorthand, $this->creator, $this->description, $this->tags, $this->category)) {
			error_log(json_encode(Array(
					"title" => $this->title,
					"shorthand" => $this->shorthand,
					"creator" => $this->creator,
					"description" => $this->description,
					"tags" => $this->tags,
					"category" => $this->category
				)));
			throw new ProblemException("Unset required instance vars", __FUNCTION__);
		}

		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `problems` (`creator`, `title`, `description`, `shorthand`, `category`) VALUES (?, ?, ?, ?, ?)")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		// insert problem into db
		$stmt->bind_param('isssi', $this->creator, $this->title, $this->description, $this->shorthand, $this->category);
		$stmt->execute();

		$this->id = $this->_mysqli->insert_id;

		// associate tags
		foreach ($this->tags as $tag_id) {
			$tag_object = new TagObject($this->_mysqli);
			$tag_object->id = $tag_id;
			if ($tag_object->createAssociation($this->id, 'PROBLEM') != true) {
				throw new ProblemException("Failed to associate problem tag " . $tag, __FUNCTION__);
			}
		}

		// return true on success
		return true;
	}

	/**
	 * load()
	 * Loads all of the necessary problem data for displaying on a problem page.
	 */
	public function loadFull() {
		// try to load problem with an id
		if (!isset($this->id, $_SESSION['uid'])) {
			throw new ProblemException("Unset variable: ID", __FUNCTION__);
		}

		// fetch from the db the information about this problem based on its id
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `shorthand`, `title`, `description`, `created`, `creator`, `status`, `current_trial` FROM `problems` WHERE `id` = ? LIMIT 1")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}
		$stmt->bind_param("i", $this->id);

		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows != 1) {
			throw new ProblemException("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
		}

		$stmt->bind_result($id, $shorthand, $title, $description, $created, $creator_id, $status, $current_trial);
		$stmt->fetch();

		// Set this object's member vars
		$this->shorthand = $shorthand;
		$this->title = $title;
		$this->description = $description;
		$this->created = $created;
		$this->status = $status;
		$this->trial_no = $current_trial;

		// fetch creator info
		$this->creator = new UserObject($this->_mysqli);
		$this->creator->uid = $creator_id;
		$this->creator->load();

		// set score
		$this->scor = $this->getScore();

		$this->current_user_vote = Vote::fetchVote($this->_mysqli, "PROBLEM", $this->id, $_SESSION['uid']);

		// get array of afficiliated thread ids
		$this->getThreads();
	}


	/**
	 * getPreview()
	 * For obtaining less innformation to display on dashboard.
	 */
	public function loadPreview() {
		if (!isset($this->id)) {
			throw new ProblemException("Could not load preview: id not set.", __FUNCTION__);
		}

		// fetch from the db the information about this problem
		if (!$stmt = $this->_mysqli->prepare("SELECT `shorthand`, `title`, `description`, `created`, `creator`, `current_trial` FROM `problems` WHERE `id` = ? LIMIT 1")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows != 1) {
			throw new ProblemException("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
		}

		$stmt->bind_result($shorthand, $title, $description, $created, $creator_id, $current_trial);
		$stmt->fetch();


		// Set this object's member vars
		$this->shorthand = $shorthand;
		$this->title = $title;
		$this->description = $description;
		$this->created = $created;
		$this->trial_no = $current_trial;

		$stmt->close();
	}
	
	/**
	 * getId()
	 * Gets ID from shorthand
	 */
	public function getId(){
		if(!isset($this->shorthand))
			throw new ProblemException("Shorthand not set", __FUNCTION__);
		
		if(!$stmt = $this->_mysqli->prepare("SELECT `id`, `shorthand` FROM `problems` WHERE `shorthand`= ? LIMIT 1")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		$this->shorthand = strtolower($this->shorthand);

		$stmt->bind_param("s", $this->shorthand);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows < 1) {
			throw new ProblemException("Unable to fetch id, less than one row returned", __FUNCTION__);
		}

		$stmt->bind_result($db_id, $shorthand);
		$stmt->fetch();

		$this->id = $db_id;
	}

	/**
	 * getShorthand()
	 * Gets shorthand from ID
	 */
	public function getShorthand(){
		if(!isset($this->id))
			throw new ProblemException("id not set", __FUNCTION__);
		
		if(!$stmt = $this->_mysqli->prepare("SELECT `id`, `shorthand` FROM `problems` WHERE `id`= ? LIMIT 1")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows < 1) {
			throw new ProblemException("Unable to fetch id, less than one row returned", __FUNCTION__);
		}

		$stmt->bind_result($db_id, $db_shorthand);
		$stmt->fetch();
		
		$this->shorthand = $db_shorthand;		
	}

	/**
	 * upvote
	 * Enter an upvote for a problem
	 */
	public function vote($user, $val) {
		if (!isset($this->id))
			throw new ProblemException("Id or creator not set", __FUNCTION__);
		
		// submit vote
		$voteResult = Vote::addVote($this->_mysqli, "PROBLEM", $this->id, $user, $val);
		if($voteResult)
			return $val;

		return false;
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
		$this->score = Vote::fetchScore($this->_mysqli, "PROBLEM" , $this->id);
		return $this->score;
	}

	/**
	 * validateShorthand()
	 * Ensures shorthand is avalible
	 */
	public function validateShorthand(){
		// Checks that all required post variables are set
		if (!isset($this->shorthand)) {
			throw new ProblemsException("Couldn't validate, shorthand not set.", __FUNCTION__);
		}

		// Prepares variables
		$this->shorthand = strtolower($this->shorthand);

		if (!$stmt = $this->_mysqli->prepare("SELECT `shorthand` FROM problems WHERE `shorthand` = ?")) {
			throw new ProblemsException($this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("s", $this->shorthand);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($db_shorthand);
		$stmt->fetch();

		$rows = $stmt->num_rows;

		$stmt->close();

		if($rows >= 1)
			return false;

		return true;
	}

	/**
	 * getThreads
	 * Queries for a list of ids of threads specfic to this problem
	 */
	public function getThreads() {

		// check that we have the id of the problem
		if (!isset($this->id)) {
			throw new ProblemException("Couldn't get threads; no id given", __FUNCTION__);
		}

		// prepare statement
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `subject`, `created` FROM `threads` WHERE `problem_id` = ? ORDER BY `created` DESC")) {
			throw new ProblemException("Prepared failed: " . $this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($thread_id, $subject, $created);
		$result = array();
		while ($stmt->fetch()) {
			$result[] = array("id" => $thread_id, "subject" => $subject, "created" => $created);
		}

		$this->threads = $result;
	}
	
	/**
	 * toArray()
	 * Get this problem in array form.
	 */
	public function toArray(){
		@$result = Array(
			"id" => $this->id,
			"title" => $this->title,
			"shorthand" => $this->shorthand,
			"description" => $this->description,
			"contributors" => Array(Array("user" => $this->creator, "association" => "creator")),
			"created" => $this->created,
			"tags" => $this->tags,
			"trial_no" => $this->trial_no,
			"score" => $this->score,
			"threads" => $this->threads,
			"current_user_vote" => $this->current_user_vote
		);
		
		return $result;
	}
}