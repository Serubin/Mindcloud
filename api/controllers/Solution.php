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
	public function create() {
		// TODO
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