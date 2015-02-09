<?php
/******************************************************************************
 * Browser.php
 * @author Michael Shullick, Solomon Rubin
 * 6 February 2015
 * Controller for Browser object. Handles searches and fetches content for 
 * displaying in problem/solution browser. Also handles sorting of results.
 *****************************************************************************/

class Browser {

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
	 * load()
	 * Inital load of user logging in.
	 * TODO: Decide on what to show first, based on preferences
	 * @return array of content for initial display
	 */
	public load() {
		// TODO Load preferences to get what's being loaded and in what order

		// if problems are to be loaded
		// load most recent 10
		// TODO change constant 10 to be however can fit on screen
		if (!$stmt = $this->_mysqli->("SELECT (`id`, `statement`) FROM `problems` ORDER BY `creation_data` LIMIT 10")) {
			$problems = new array();
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $stmt);
			while ($stmt->fetch) {
				$problems[] = array($id, $stmt);
			}
			return $problems;
		}
	}

	/**
	* search() 
	* User search of problems/projects 
	* @return search results
	*/
	public search() {
		// TODO
	}

}