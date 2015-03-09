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
	public $shorthand;
	public $description;
	public $creation_datetime;
	public $tags;
	public $trial_no;
	public $score;
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
	 * On tags:
	 * The javascript should take care of delivering the tag IDs in the request
	 * rather than the strings themselves. 
	 * 
	 */
	public function create() {

		if (!isset($this->statement, $this->creator, $this->description, $this->tags)) {
			throw new ProblemException("Unset required instance vars", __FUNCTION__);
		}

		if (!isset($this->shorthand)) {
			// TODO: create random shorthand for url / mentions, maximum length?
		}

		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `problems` (`creator`, `title`, `description`, `shorthand`) VALUES (?, ?, ?, ?)")) {
			throw new ProblemException($this->_mysqli->error, __FUNCTION__);
		}

		// sanitize strings
		$this->statement = filter_var($this->statement, FILTER_SANITIZE_STRING);
		$this->description = strip_tags($this->statement);
		$this->shorthand = filter_var($this->shorthand, FILTER_SANITIZE_STRING);

		// insert problem into db
		$stmt->bind_param('isss', $this->creator, $this->statement, $this->description, $this->shorthand);
		$stmt->execute();

		// associate tags
		error_log(json_encode($this->tags));
		foreach ($this->tags as $tag_id) {
			$tag_object = new TagObject($this->_mysqli);
			$tag_object->id = $tag_id;
			$tag_object->identifier = $this->tags[$tag_id];
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
	public function loadFull()
	{
		try {
			// ensure we have the necessary data
			if (!isset($this->id)) {
				throw new ProblemException("Could not load problem; no id provided.", __FUNCTION__);
			}

			// fetch from the db the information about this problem
			if (!$stmt = $this->_mysqli("SELECT `shorthand`, `title`, `description`, `created`, `creator`, `status`, `current_trial` FROM `problem` WHERE `id` = ? LIMIT 1")) {
				throw new ProblemException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->bind_result();
			if ($stmt->num_rows != 1) {
				throw new Exception("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
			}

			$stmt->bind_result($shorthand, $title, $description, $created, $creator_id, $status, $current_trial);
			$stmt->fetch();

			// Set this object's member vars
			$this->shorthand = $shorthand;
			$this->statement = $title;
			$this->description = $description;
			$this->created = $creation_datetime;
			$this->status = $status;
			$this->trial_no = $current_trial;

			// fetch creator info
			$this->creator = new UserObject();
			$this->creator->id = $creator_id;
			$this->creator->load();

			// set score
			$this->getScore();

		} catch (ProblemException $e) {
			return $e;
		}
	}

	/**
	 * upvote
	 * Enter an upvote for a problem
	 */
	public function vote($val) {
		try {
			if (!isset($this->id, $this->creator))
				throw new ProblemException("Id or creator not set", __FUNCTION__);

			// submit vote
			return Vote::addVote($this->_mysqli, "PROBLEM", $this->id, $this->creator, $val);

		} catch (ProblemException $e) {
			return $e;
		}
	}

	/**
	 * getScore()
	 * Get the difference between upvotes and downvotes.
	 */
	public function getScore() {
		try {

			// check that we have the problem id
			if (!isset($this->id)) {
				throw new Exception("Unset id", __FUNCTION__);
			}

			// get score
			$this->score = Vote::fetchScore($this->_mysqli, "PROBLEM" , $this->id);
			return $this->score;

		} catch (ProblemException $e) {
			return $e;
		}
	}

	/**
	 * getPreview()
	 * For obtaining less innformation to display on dashboard.
	 */
	public function loadPreview() {

		try {
			if (isset($this->id)) {
				throw new ProblemException("Could not load preview: id not set.", __FUNCTION__);
			}

			// fetch from the db the information about this problem
			if (!$stmt = $this->_mysqli("SELECT `shorthand`, `title`, `description`, `created`, `creator`, `current_trial` FROM `problem` WHERE `id` = ? LIMIT 1")) {
				throw new ProblemException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$stmt->bind_result();
			if ($stmt->num_rows != 1) {
				throw new Exception("Unable to fetch problem data: " . $stmt->num_rows . " returned.", __FUNCTION__);
			}

			$stmt->bind_result($shorthand, $title, $description, $created, $creator_id, $current_trial);
			$stmt->fetch();

			// Set this object's member vars
			$this->shorthand = $shorthand;
			$this->statement = $title;
			$this->description = $description;
			$this->created = $creation_datetime;
			$this->trial_no = $current_trial;

			


		} catch (ProblemException $e) {
			return $e;
		}
	}

}