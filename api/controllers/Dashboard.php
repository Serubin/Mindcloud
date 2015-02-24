<?php
/******************************************************************************
 * Browser.php
 * @author Michael Shullick, Solomon Rubin
 * 6 February 2015
 * Controller for Browser object. Handles searches and fetches content for 
 * displaying in problem/solution browser. Also handles sorting of results.
 *****************************************************************************/

class Dashboard {

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
	public function loadDashboard() {
		// TODO Load preferences to get what's being loaded and in what order

		try {

			// if problems are to be loaded
			// load most recent 10
			// TODO change constant 10 to be however can fit on screen
			if ($stmt = $this->_mysqli->prepare("SELECT `id`, `statement`, `created` FROM `problems` ORDER BY `created` LIMIT 10")) {
				$stmt->execute();
				$stmt->store_result();
				$problems = array();
				$stmt->bind_result($id, $pr_stmt, $date);
				while ($stmt->fetch()) {
					$problems[] = array($id, $pr_stmt, $date);
					error_log(html_entity_decode($pr_stmt));	
				}
				return $problems;
			}
			else {
				throw new Exception($this->_mysqli->error);
			}
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	* search() 
	* User search of problems/projects 
	* @return search results
	*/
	public function searchDashboard() {
		// TODO
	}

}