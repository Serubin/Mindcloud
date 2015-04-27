<?php
/******************************************************************************
 * Solution.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

require_once "models/SolutionObject.php";
require_once "include/vote.php";

class Solution
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	//TODO next up: make solution creation form.
	/**
	 * create()
	 * Creates a new solution in the db and stores the assocated information.
	 * @return true on success, exception on fail
	 */
	public function createSolution() {
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['problem_id'], $this->_params['title'], 
				$this->_params['description'], $_SESSION['uid'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			$solution = new SolutionObject($this->_mysqli);

			$problem_id = filter_var($this->_params['problem_id'], FILTER_SANITIZE_NUMBER_INT);
			$solution->problem_id = $problem_id;


			$title = filter_var($this->_params['title'], FILTER_SANITIZE_STRING);
			$solution->title = $title;

			$solution->description = strip_tags($this->_params['description']);
			$solution->description = strip_tags($solution->description);

			$creator = filter_var($_SESSION['uid'], FILTER_SANITIZE_NUMBER_INT);
			$solution->creator = $creator;

			if (isset($this->_params['shorthand'])) { // Uses user shorthand
				$solution->shorthand = $this->_params['shorthand']; 
			} else {  // Creates shorthand from title
				$solution->shorthand = $solution->title; 
			}

			$solution->shorthand = preg_replace("/[,!@#$%^&*()=\[\]{};:\'\"<>.,\/?\\~`]+/", "", $solution->shorthand); // Removes scary characters
			$solution->shorthand = str_replace(" ", "-", $solution->shorthand); // Removes spacy characters (always forgettin')
			$solution->shorthand = strtolower($solution->shorthand); // Get's ride of those cocky captials.
			$solution->shorthand = substr($solution->shorthand,0 ,200); // Shortens the fatter of the bunch.
			if(!$solution->validateShorthand()){
				$solution->shorthand = $solution->shorthand . substr(md5($solution->shorthand),0, 4); // Makes unquif if not?
			}
			
			$solution->create();

			return $solution->shorthand;
		} catch (Exception $e) {
			return $e;
		} 
	}

	public function updateSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'], $this->_params['shorthand'], $this->_params['title'], 
				$this->_params['description'], $_SESSION['uid'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			$solution = new SolutionObject();
			$solution->id = filter_var($this->_params['id'], FILTER_SANITIZE_NUMBER_INT);
			$solution->load();

			// Ensures use is authorized
			if($_SESSION['uid'] != $solution->uid)
				throw new SolutionException("User not authorized", __FUNCTION__);
				

			$shorthand = filter_var($this->_params['shorthand'], FILTER_SANITIZE_STRING);
			$solution->shorthand = $shorthand;

			$title = filter_var($this->_params['title'], FILTER_SANITIZE_STRING);
			$solution->title = $title;

			$description = strip_tags($this->_params['description']);
			$solution->description = $description;

			$solution->update();

			return true;

		} catch (Exception $e){
			return $e;
		}
	}

	/** 
	 * voteSolution() 
	 * Vote solution
	 *
	 * @param id - solution id
	 * @param vote - vote value (1, -1)
	 * @param session uid
	 * @return returns new score of solution
	 */
	public function voteSolution() {

		try {
			// check that we have the appropriate data
			if (!isset($this->_params['id'], $this->_params['vote'], $_SESSION['uid'])) {
				throw new SolutionException("Unset vars: id, vote", __FUNCTION__);
			}

			// validate vote value by taking absolute value
			if ($this->_params['vote'] != UPVOTE && $this->_params['vote'] != DOWNVOTE) {
				throw new SolutionException("Invalid vote passed", __FUNCTION__);
			}

			// submit vote
			$solution = new SolutionObject($this->_mysqli);
			$solution->id = $this->_params['id'];

			$solution->vote($_SESSION['uid'], $this->_params['vote']);

			return Vote::fetchScore( $this->_mysqli, "SOLUTION", $this->_params['id']);
		} catch (Exception $e) {
			return $e;
		}
	}

	public function scoreSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			$solution = new SolutionObject($this->_mysqli);
			$solution->id = $this->_params['id'];

			return $solution->getScore();
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	 * load()
	 * Load the associated content with the pre-set project id
	 * @return true on succcess, indicating this instance has all associated
	 * content
	 */
	public function loadSolution() {
		try {
			if (!isset($this->_params['id'])) {
				throw new SolutionException("Could not load problem; no id provided.", __FUNCTION__);
			}

			// initialize solution object
			$solution = new SolutionObject($this->_mysqli);
			$solution->id = $this->_params['id'];

			// inflate the solution with its own information
			$solution->loadFull();

			return $solution;
		} catch (Exception $e){
			return $e;
		}
	}

	public function loadPreviewSolution(){
		try {
			if(!isset($this->_params['id'])){
				throw new SolutionException("Could not load problem preview; no id provided", __FUNCTION__);
			}

			// initialize solution
			$solution = new SolutionObject($this->_mysqli);
			$solution->id = $this->_params['id'];

			// inflate solution with preview info
			$solution->loadPreview();

			return $solution;
		} catch(Exception $e) {
			return $e;
		}
	}

	/**
	 * getIdProblem()
	 * Loads id from shorthand
	 */
	public function getIdSolution(){
		try {
			if (!isset($this->_params['shorthand'])) {
				throw new SolutionException("Could not load problem id; no shorthand provided.", __FUNCTION__);
			}

			$solution = new SolutionObject($this->_mysqli);
			$solution->shorthand = $this->_params['shorthand'];
			$solution->getId();

			return $solution->id;
		} catch (Exception $e) {
			return $e;
		}
	}


	/**
	 * getShorthandProblem()
	 * Loads shorthand from id
	 */
	public function getShorthandSolution(){
		try {
			if (!isset($this->_params['id'])) {
				throw new SolutionException("Could not load problem shorthand; no id provided.", __FUNCTION__);
			}

			$solution = new SolutionObject($this->_mysqli);
			$solution->id = $this->_params['id'];
			$solution->getShorthand();

			return $solution->shorthand;
		} catch (Exception $e) {
			return $e;
		}
	}

	public function getRelatedSolution(){
		try {
			if(!isset($this->_params['problem_id'])){
				throw new SolutionException("Couldn't load related solutions, no id provided", __FUNCTION__);
			}

			$solution = new SolutionObject($this->_mysqli);
			$solution->problem_id;
			$related = $solution->getRelatedSolutions();

			$result = Array();

			foreach($related as $value) {
				$related_solution = new SolutionObject($this->_mysqli);
				$related_solution->id = $value;
				$related_solution->loadPreview();

				array_push($result, $related_solution);
			}

			return $result;
		} catch (Exception $e) {
			return $e;
		}
	}
}