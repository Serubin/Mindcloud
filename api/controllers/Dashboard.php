<?php
/******************************************************************************
 * Browser.php
 * @author Michael Shullick, Solomon Rubin
 * 6 February 2015
 * Controller for Browser object. Handles searches and fetches content for 
 * displaying in problem/solution browser. Also handles sorting of results.
 *****************************************************************************/

require_once("include/vote.php");

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

	// number of problems to load
	private $_load_amount = 6;

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
			$result['votes'] = array();
			$problem_ids = array(); // retained for finding problems

			// handle on all of the problem ids 

			// if problems are to be loaded
			// load most recent 10
			// TODO change constant 10 to be however many can fit on screen
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `title`, `created`, `shorthand` FROM `problems` ORDER BY `created` LIMIT ?")) {
				error_log("failing");
				throw new DashboardException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->bind_param("i", $this->_load_amount);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $pr_stmt, $date, $shorthand);
			while ($stmt->fetch()) {

				$vote_count = Vote::fetchScore($this->_mysqli, "PROBLEM", $id);
				$result['problems'][] = array("id" => $id, "title" => htmlspecialchars_decode($pr_stmt, ENT_QUOTES), "data" => $date, "shorthand" => $shorthand, "votes" => $vote_count);
				$problem_ids[] = $id;
				//error_log(html_entity_decode($pr_stmt));	
			}

			// end
			$stmt->close();

			/** 
			 * load vote statuses
			 */

			// base query
			$query = "SELECT `vote`, `cid` FROM `votes` WHERE `uid` = ? AND `ctype` = 'PROBLEM' AND `cid` IN (";
			$typesByString = "";

			// add the correct number of variable params
			// Array of references for use in bind_param
			$problem_args = array();
			for ($i = 0; $i < count($problem_ids); $i++) {
				$problem_args[] = &$problem_ids[$i];
				// Add an s for every sku argument
				$typesByString .= "i";
				// A question mark for every parameter in the query
				$wildcards[] = "?";
			}

			// Tack on the question marks and right parenthetical
			$wildcards = implode(",", $wildcards);
			$query .= $wildcards . ")";

			// Create one giant array of arguments for call_user_func_array
			$typesByString = "i" . $typesByString; // account for uid
			$params = array_merge(array($typesByString, &$_SESSION['uid']), $problem_args);

			if (!$stmt = $this->_mysqli->prepare($query)) {
				error_log($query);
				throw new DashboardException("prepare failed: " . $this->_mysqli->error, __FUNCTION__);
			}

			// bind params
			// prepare query, accounting for a variable number of parameters with call_user_func_array
			if (!call_user_func_array(array($stmt, "bind_param"), $params)) {
				error_log($query);
				error_log(json_encode($params));
				throw new DashboardException("bind param failed: " . $stmt->error, __FUNCTION__);
			}

			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($vote, $cid);

			while ($stmt->fetch()) {
				$result['votes'][] = array($cid, $vote);
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

	/**
	 * extendDashboard()
	 * Get more problems for automatic scrolling
	 */
	public function extendDashboard() {

		// make sure we have which pagination to load
		if (!isset($this->_params['page'])) {
			throw new ProblemException("No page provided, cannot get more problems", __FUNCTION__);
		}

		// Which subset of problems to load
		$offset = $this->_load_amount * $this->_params['page'];

		// Prepare mysql statement
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `title`, `created`, `shorthand` FROM `problems` ORDER BY `created` LIMIT ? OFFSET ?")) {
			throw new DashboardException("Prepare failed: " . $this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("ii", $this->_load_amount, $offset);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $title, $created, $shorthand);

		$loaded_posts = array();

		while ($stmt->fetch()) {

			$vote_count = Vote::fetchScore($this->_mysqli, "PROBLEM", $id);
			$loaded_posts[] = array("id" => $id, "title" => htmlspecialchars_decode($title, ENT_QUOTES), "date" => $created, "shorthand" => $shorthand, "votes" => $vote_count	);
		}

		return $loaded_posts;

	}

}