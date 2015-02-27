<?php
/******************************************************************************
 * Solution.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

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
				$this->_params['description']) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			$solution = new SolutionObject();
			$solution->id = filter_var($this->_params['id'], FILTER_SANITIZE_NUMBER_INT);
			$solution->load();

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

	public function voteSolution(){
		try {
			// Checks that all required post variables are set
			if (!isset($this->_params['id'], $this->_params['vote'],$_SESSION['uid'])) {
				error_log(json_encode($this->_params));
				throw new SolutionException("Unset vars", __FUNCTION__);
			}

			//TODO finish dis shit
	}
	/**
	 * load()
	 * Load the associated content with the pre-set project id
	 * @return true on succcess, indicating this instance has all associated
	 * content
	 */
	public function load() {
		// TODO
	}

}