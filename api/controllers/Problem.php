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
			if (!isset($this->_params['uid'], $this->_params['title'], $this->_params['description'], $this->_params['tags'], $this->_params['category'])) {
				error_log(json_encode($this->_params));
				throw new ProblemException("Unset vars.", __FUNCTION__);
			}

			$problem = new ProblemObject($this->_mysqli);
			$problem->creator = $this->_params['uid'];
			$problem->title = $this->_params['title'];
			$problem->description = $this->_params['description'];
			$problem->tags = $this->_params['tags'];
			$problem->category = $this->_params['category'];

			if (isset($this->_params['shorthand'])) {
				$problem->shorthand = $this->_params['shorthand']; // Uses user shorthand
				if(!$problem->validateShorthand()){
					throw new ProblemException("shorthand unavalible", __FUNCTION__);
				}
			} else { 
				// Creates shorthand
				$problem->shorthand = preg_replace("/[^ \w]+/", "", $this->_params['title']); // Removes scary characters
				$problem->shorthand = str_replace(" ", "-", $problem->shorthand); // Removes spacy characters (always forgettin')
				$problem->shorthand = strtolower($problem->shorthand); // Get's ride of those cocky captials.
				$problem->shorthand = substr($problem->shorthand,0 ,200); // Shortens the fatter of the bunch.
				if(!$problem->validateShorthand()){
					$problem->shorthand = $problem->shorthand . substr(md5($problem->shorthand),0, 4); // Makes unquif if not?
				}
			}
			
			return $return = $problem->create() ? $problem->shorthand : false;;

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
				"threads" => $problem->threads,
				"current_user_vote" => $problem->current_user_vote
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
			if (!isset($this->_params['pid'], $this->_params['vote'], $_SESSION['uid'])) {
				throw new ProblemException("Unset vars: pid, vote", __FUNCTION__);
			}

			error_log("Problem vote: " . $this->_params['vote'] . " " . json_encode(Array($this->_params['vote'] != UPVOTE, $this->_params['vote'] != DOWNVOTE)));
			// validate vote value by taking absolute value
			if ($this->_params['vote'] != UPVOTE && $this->_params['vote'] != DOWNVOTE) {
				throw new ProblemException("Invalid vote passed", __FUNCTION__);
			}

			// submit vote
			$problem = new ProblemObject($this->_mysqli);
			$problem->id = $this->_params['pid'];

			return $problem->vote($_SESSION['uid'], $this->_params['vote']);
		} catch (ProblemException $e) {
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
