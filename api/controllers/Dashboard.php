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

			// initialize result array
			$result = array();
			$result['problems'] = array();
			$result['categories'] = array();

			// if problems are to be loaded
			// load most recent 10
			// TODO change constant 10 to be however many can fit on screen
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `title`, `created`, `shorthand` FROM `problems` ORDER BY `created` LIMIT 10")) {
				error_log("failing");
				throw new DashboardException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $pr_stmt, $date, $shorthand);
			while ($stmt->fetch()) {
				$result['problems'][] = array($id, $pr_stmt, $date, $shorthand);
				//error_log(html_entity_decode($pr_stmt));	
			}

			// load categories
			$stmt->close();
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `name` FROM `categories`")) {
				throw new DashboardException($this->_mysqli->error);
			}

			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $name);
			while($stmt->fetch()) {
				$result['categories'][] = array($id, $name);
			}

			//error_log(json_encode($result));

			return $result;

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