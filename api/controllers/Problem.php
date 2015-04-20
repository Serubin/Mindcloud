<?php
/******************************************************************************
 * Problem.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

require_once("models/ProblemObject.php");
require_once("models/TagObject.php");
require_once("include/vote.php");
require_once("include/Flag.php");

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

			// only set the shorthand if given
			if (isset($_params['shorthand'])) $problem->shorthand = $_params['shorthand'];
			return $problem->create();

		} catch (ProblemException $e) {
			return $e;
		}
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
		try {

			if (!isset($this->_params['id'])) {
				throw new ProblemException("Could not load problem; no id provided.", __FUNCTION__);
			}

			// initialize problem object
			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['id'];

			// inflate the problem with its own information
			$problem->loadFull();

			return Array(
				"id" => $problem->id,
				"title" => $problem->title,
				"shorthand" => $problem->shorthand,
				"description" => $problem->description,
				"creator" => Array("user" => $problem->creator, "association" => "creator"),
				"created" => $problem->created,
				"tags" => $problem->tags,
				"trial_no" => $problem->trial_no,
				"score" => $problem->score,
				"threads" => $problem->threads
			);

		} catch (ProblemException $e) {
			return $e;
		}

	}

	/** 
	 * upvoteProblem() 
	 * Give the specified problem an upvote
	 */
	public function voteProblem() {

		try {
			// check that we have the appropriate data
			if (!isset($this->_params['problem_id'], $this->_params['vote'], $_SESSION['uid'])) {
				throw new ProblemException("unset parameters", __FUNCTION__);
			}

			// validate vote value by taking absolute value
			if (abs($this->_params['vote']) != UPVOTE) {
				throw new ProblemException("Invalid vote passed", __FUNCTION__);
			}

			// submit vote
			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['problem_id'];
			$problem->creator = $_SESSION['uid'];
			$problem->vote($this->_params['vote']);

			return true;

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
}
