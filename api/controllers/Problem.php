<?php
/******************************************************************************
 * Problem.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a solution idea.
 ******************************************************************************/

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
	public function create() {
		
		try {
			if (!isset($_SESSON['uid'], $this->params['statement'], $this->['description']))
				throw new ProblemException("Unset vars.", __FUNCTION__);

			$problem = new Problem($this->mysqli);
			$problem->creator = $_SESSION['uid'];
			$problem->statement = $this->params['statement'];
			$problem->description = $this->params['description'];
			return $problem->create();

		} catch (ProblemException $e) {
			return $e;
		}
	}


	/**
	 * activate()
	 * Begins the next trial of a problem, presuming it is now inactive.
	 * @return true on success, exception on fail
	 */
	public function activate() {
		// TODO
	}

	/**
	 * deactivate()
	 * Ends the current trial and marks this problem as inactive.
	 * @return true on success, exception on fail
	 */
	public function deactivate() {
		// TODO
	}

	/**
	 * 
}