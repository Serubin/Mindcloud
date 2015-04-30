<?php
/******************************************************************************
 * Problem.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a problem idea.
 ******************************************************************************/

require_once "models/ProblemObject.php";
require_once "models/TagObject.php";
require_once "include/vote.php";
require_once "include/Flag.php";

class Problem 
{
	// the parameters of the request
	private $_params;

	// The handle on the db
	private $_mysqli;

	/** 
	 * Constructor
	 * @param $params request parameters
	 * @param $mysqli db handler
	 */
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	/**
	 * create()
	 * Creates a new problem in the db and stores the assocated information.
	 * @return true on success, exception on fail
	 */
	public function createProblem() {
		
		try {
			if (!isset($_SESSION['uid'], $this->_params['title'], $this->_params['description'], $this->_params['tags'], $this->_params['category'])) {
				error_log(json_encode($this->_params));
				throw new ProblemException("Unset vars.", __FUNCTION__);
			}

			$problem = new ProblemObject($this->_mysqli);
			$problem->creator = $_SESSION['uid'];
			$problem->title = $this->_params['title'];
			$problem->description = $this->_params['description'];
			$problem->tags = $this->_params['tags'];
			$problem->category = $this->_params['category'];

			// sanitize strings
			$problem->title = filter_var($problem->title, FILTER_SANITIZE_STRING);
			$problem->description = strip_tags($problem->description);
			//$problem->description = str_replace("\n\n", "[[#line#end#]]", $problem->description); //TODO make spacing work better
			$problem->shorthand = filter_var($problem->shorthand, FILTER_SANITIZE_STRING);

			if (isset($this->_params['shorthand'])) { // Uses user shorthand
				$problem->shorthand = $this->_params['shorthand']; 
			} else {  // Creates shorthand from title
				$problem->shorthand = $problem->title; 
			}

			$problem->shorthand = preg_replace("/[,!@#$%^&*()=\[\]{};:\'\"<>.,\/?\\~`]+/", "", $problem->shorthand); // Removes scary characters
			$problem->shorthand = str_replace(" ", "-", $problem->shorthand); // Removes spacy characters (always forgettin')
			$problem->shorthand = strtolower($problem->shorthand); // Get's ride of those cocky captials.
			$problem->shorthand = substr($problem->shorthand,0 ,200); // Shortens the fatter of the bunch.
			if(!$problem->validateShorthand()){
				// Makes unique if not
				$problem->shorthand = $problem->shorthand . substr(md5($problem->shorthand . date('Y-m-d H:i:s') . $_SESSION['uid']),0, 6);
			}
			
			return $return = $problem->create() ? $problem->shorthand : false;

		} catch (ProblemException $e) {
			return $e;
		}
	}

	/** validateShorthand()
	 * Verifies that shorthand is avalible
	 *
	 */
	public function validateShorthandProblem(){
		if(!isset($this->_params['shorthand'])){
			throw new ProblemException("Couldn't verify; no shorthand provided", __FUNCTION__);
		}
		$problem = new ProblemObject($this->_mysqli);
		$problem->shorthand = $this->_params['shorthand'];

		return $problem->validateShorthand();
	}

	/**
	 * getIdProblem()
	 * Loads id from shorthand
	 */
	public function getIdProblem(){
		if (!isset($this->_params['shorthand'])) {
			throw new ProblemException("Could not load problem id; no shorthand provided.", __FUNCTION__);
		}

		$problem = new ProblemObject($this->_mysqli);
		$problem->shorthand = $this->_params['shorthand'];
		$problem->getId();

		return $problem->id;
	}


	/**
	 * getShorthandProblem()
	 * Loads shorthand from id
	 */
	public function getShorthandProblem(){
		if (!isset($this->_params['id'])) {
			throw new ProblemException("Could not load problem shorthand; no id provided.", __FUNCTION__);
		}

		$problem = new ProblemObject($this->_mysqli);
		$problem->id= $this->_params['id'];
		$problem->getShorthand();

		return $problem->shorthand;
	}
	/**
	 * loadProblem()
	 * Function for loading content of problem page.
	 */
	public function loadProblem() {
		if (!isset($this->_params['id'])) {
			throw new ProblemException("Could not load problem; no id provided.", __FUNCTION__);
		}

		// initialize problem object
		$problem = new ProblemObject($this->_mysqli);
		$problem->id = $this->_params['id'];

		// inflate the problem with its own information
		$problem->loadFull();

		return $problem;
	}

	/**
	 * loadProblem()
	 * Function for loading content of problem page.
	 */
	public function loadPreviewProblem() {
		if (!isset($this->_params['id'])) {
			throw new ProblemException("Could not load problem; no id provided.", __FUNCTION__);
		}

		// initialize problem object
		$problem = new ProblemObject($this->_mysqli);
		$problem->id = $this->_params['id'];

		// inflate the problem with its own information
		$problem->loadPreview();

		return $problem;
	}

	public function updateProblem(){
		try { 
			if(!isset($this->_params['id'], $this->_params['title'], $this->_params['description'], $this->_params['status'], $_SESSION['uid'])) {
				throw new ProblemException("Couldn't update problem; missing paramters", __FUNCTION__);
			}

			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['id'];
			$problem->loadPreview();

			if(!$problem->can_edit)
				throw new ProblemException("Unauthorized request", __FUNCTION__);

			$problem->title = $this->_params['title'];
			$problem->description = $this->_params['description'];
			$problem->status = $this->_params['status'];

			// sanitize strings
			$problem->title = filter_var($problem->title, FILTER_SANITIZE_STRING);
			$problem->description = strip_tags($problem->description);
			$problem->status = filter_var($problem->status, FILTER_SANITIZE_NUMBER_INT);
			
			return $problem->update();
		} catch(Exception $e) {
			return $e;
		}
	}

	/** 
	 * voteProblem() 
	 * Vote problem
	 *
	 * @param id - problem id
	 * @param vote - vote value (1, -1)
	 * @param session uid
	 * @return returns new score of solution
	 */
	public function voteProblem() {

		try {
			// check that we have the appropriate data
			if (!isset($this->_params['id'], $this->_params['vote'], $_SESSION['uid'])) {
				error_log("Request parameter dump" . json_encode($this->_params));
				throw new ProblemException("Unset vars", __FUNCTION__);
			}

			// validate vote value by taking absolute value
			if ($this->_params['vote'] != UPVOTE && $this->_params['vote'] != DOWNVOTE) {
				throw new ProblemException("Invalid vote passed", __FUNCTION__);
			}

			// submit vote
			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['id'];

			$problem->vote($_SESSION['uid'], $this->_params['vote']);

			return Vote::fetchScore( $this->_mysqli, "PROBLEM", $this->_params['id']);
		} catch (ProblemException $e) {
			return $e;
		}
	} 

	/**
	 * Submit a flag on a problem
	 */
	public function flagProblem() {

		try {
			if (!isset($this->_params['problem_id'], $this->_params['flag'], $_SESSION['uid'])) {
				error_log(json_encode($this->_params));
				throw new ProblemException("Unable to flag problem, unset params", __FUNCTION__);
			}

			// check that the flag is valid
			// TODO: Currently hardcoded for the sake of time. Change this to be dynamic later on
			if ($this->_params['flag'] != 1 && $this->_params['flag'] != 2) {
				throw new ProblemException("Invalid flag passed", __FUNCTION__);
			}

			// submit the flag
			if (!Flag::addFlag($this->_mysqli, $this->_params['problem_id'], $_SESSION['uid'], $this->_params['flag'])) {
				throw new ProblemException("Failed to flag problem", __FUNCTION__);
			}

			// return successfull
			return true;

		} catch (Exception $e) {
			return $e;
		}
	}

	public function scoreProblem(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'])) {
				error_log(json_encode($this->_params));
				throw new ProblemException("Unset vars", __FUNCTION__);
			}

			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['id'];

			return $problem->getScore();
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	 * Identify problem()
	 * Retrieve the id of the passed tag identifier or create a new and return
	 * @return the id of the given tag
	 */
	public function identifyProblem() {
		try {

			// first check that we have the identifier 
			if (!isset($this->_params['identifer'])) {
				throw new ProblemException("Cannot identify tag; no identifier given", __FUNCTION__);
			}

			// setup
			$problem = new ProblemObject($this->_mysqli);
			$problem->identifier = $this->params['identifer'];

			// then check if it's in the database

			// if it is, return it

			// else, create a new problem, and return the new id


		} catch (ProblemException $e) {
			return $e;
		}
	}


	/**
	 * activate()
	 * Begins the next trial of a problem, presuming it is now inactive.
	 * @return true on success, exception on fail
	 */
	public function activateProblem() {
		// TODO
	}

	/**
	 * deactivate()
	 * Ends the current trial and marks this problem as inactive.
	 * @return true on success, exception on fail
	 */
	public function deactivateProblem() {
		// TODO
	}

	/**
	 * getCategoriesProblem()
	 */
	public function getcategoriesProblem() {
		
		$categories = array();

		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `name` FROM `categories`")) {
				throw new DashboardException($this->_mysqli->error);
			}

		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $name);
		while($stmt->fetch()) {
			$categories[] = array($id, $name);
		}

		return $categories;

	}
}
