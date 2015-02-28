<?php
/******************************************************************************
 * Solution.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

require_once "models/UserSolution.php";
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


	/**
	 * create()
	 * Creates a new solution in the db and stores the assocated information.
	 * @return true on success, exception on fail
	 */
	public function createSolution() {
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['problem_id'], $this->_params['shorthand'], $this->_params['title'], 
				$this->_params['description'], $this->_params['creator']) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			$new_solution = new SolutionObject();

			$problemId = filter_var($this->_params['problemId'], FILTER_SANITIZE_NUMBER_INT);
			$new_solution->problemId = $problem_id;

			$shorthand = filter_var($this->_params['shorthand'], FILTER_SANITIZE_STRING);
			$new_solution->shorthand = $shorthand;

			$title = filter_var($this->_params['title'], FILTER_SANITIZE_STRING);
			$new_solution->title = $title;

			$description = strip_tags($this->_params['description']);
			$new_solution->description = $description;

			$creator = filter_var($this->_params['creator'], FILTER_SANITIZE_NUMBER_INT);
			$this->creator = $creator;

			if(!$new_solution->validateShorthand())
				throw new SolutionException("Shorthand exists", __FUNCTION__);
			
			$new_solution->create();

			return true;
		} catch (Exception $e) {
			return $e;
		} 
	}

	public function updateSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'], $this->_params['shorthand'], $this->_params['title'], 
				$this->_params['description'], $_SESSION['uid']) {
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
			}
		} catch (Exception $e){
			return $e;
		}
	}

	public function voteSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'], $this->_params['vote'],$_SESSION['uid'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}
			$vote = new Vote();

			$vote->addVote( $this->_mysqli, "solution", $this->_params['id'], $_SESSION['uid'], $this->_params['vote'] ){
			
			return true;

		} catch (Exception $e){
			return $e
		}
	}

	public function scoreSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'], $this->_params['vote'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}
			$vote = new Vote();

			$return $vote->fetchScore( $_mysqli, "solution", $this->_params['id'] );

		} catch (Exception $e) {
			return $e
		}
	}
	/**
	 * load()
	 * Load the associated content with the pre-set project id
	 * @return true on succcess, indicating this instance has all associated
	 * content
	 */
	public function loadSolution() {
		if(!isset($this->_params['id']) || !isset($this->_params['shorthand'])) {
			error_log(json_encode($this->_params));
			throw new Exception("Unset vars", __FUNCTION__);
		}

		$solution = new SolutionObject();

		if(isset($this->_params['id'])) {
			$solution->id = $this->_params['id'];
		}

		if(isset($this->_param['shorthand'])) {
			$solution->shorthand = $this->_params['shorthand'];
			$solution->getId();
		}

		$solution->load();

		if(isset($this->_params['plain-text'])){
			// TODO parse wiki markup before returning
		}

		$solutionData = Array("id" => $solution->id, "problem" => $solution->problem_id, "shorthand" => $this->shorthand,
			"title" => $solution->title, "description" => $solution->description, "created" => $solution->created, 
			"creator" => $solution->creator);

		return $solutionData;
	}

}