<?php
/******************************************************************************
 * Problem.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

require_once("models/ProblemObject.php");
require_once("include/vote.php");

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
			if (!isset($this->_params['uid'], $this->_params['statement'], $this->_params['description'], $this->_params['tags'])) {
				error_log(json_encode($this->_params));
				throw new ProblemException("Unset vars.", __FUNCTION__);
			}

			$problem = new ProblemObject($this->_mysqli);
			$problem->creator = $this->_params['uid'];
			$problem->statement = $this->_params['statement'];
			$problem->description = $this->_params['description'];
			$problem->tags = $this->_params['tag'];
			// only set the shorthand if given
			if (isset($_params['shorthand'])) $problem->shorthand = $_params['shorthand'];
			return $problem->create();

		} catch (ProblemException $e) {
			return $e;
		}
	}

	/**
	 * loadProblem()
	 * Function for loading content of problem page.
	 */
	public function loadProblem() {

		try {

			if (!isset($_params['id'])) {
				throw new ProblemException("Could not load problem; no id provided.", __FUNCTION__);
			}

			// inflate the problem with its own information
			$problem = new ProblemObject($this->_mysqli);
			$problem->loadFull();
			return $problem;

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
			if (!isset($this->_params['problem_id'], $this->_params['vote'], $_SESSON['uid'])) {
				throw new Exception("No problem id given", __FUNCTION__);
			}

			// validate vote value by taking absolute value
			if (abs($this->_params['vote']) != UPVOTE) {
				throw new Exception("Invalid vote passed", __FUNCTION__);
			}

			// submit vote
			$problem = new ProblemObject($_mysqli);
			$problem->id = $this->_params['problem_id'];
			$problem->creator = $_SESSION['uid'];
			$problem->vote(UPVOTE);

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