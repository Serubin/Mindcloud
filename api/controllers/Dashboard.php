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
			if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `title`, `created`, `shorthand` FROM `problems` ORDER BY `created` LIMIT 20")) {
				error_log("failing");
				throw new DashboardException($this->_mysqli->error, __FUNCTION__);
			}

			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $pr_stmt, $date, $shorthand);
			while ($stmt->fetch()) {

				$vote_count = Vote::fetchScore($this->_mysqli, "PROBLEM", $id);
				$result['problems'][] = array($id, $pr_stmt, $date, $shorthand, $vote_count);
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

}