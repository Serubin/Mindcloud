<?php
/******************************************************************************
 * Vote.php
 * @author Michael Shullick, Solomon Rubin
 * 27 Febuary 2015
 * Adds and returns votes
 * All lines will fit with 80 columns. 
 *****************************************************************************/

define("UPVOTE", 1);
define("DOWNVOTE", -1);

class Vote {


	/**
	 * addVote()
	 * Adds vote to db
	 * @param $_mysqli mysqli object
	 * @param $ctype type string
	 * {
	 * 	"PROBLEM",
	 * 	"SOLUTION"
	 * }
	 * @param $cid content id
	 * @param $vote vote -1 or 1 (down, up)
	 * @return true on success
	 */
	public static function addVote( $mysqli, $ctype, $cid, $uid, $vote ){
		if (!$stmt = $mysqli->prepare("INSERT INTO votes (`ctype`, `cid`, `uid`, `vote`) VALUES (?,?,?,?)")) {
			throw new MindcloudException($mysqli->error, "vote", __FUNCTION__);
		}

		$stmt->bind_param("siii", $ctype, $cid, $uid, $vote);
		$stmt->execute();

		$stmt->close();

		return true;
	}
	/**
	 * fetchVote()
	 * Fetches vote user casted
	 * @param $_mysqli mysqli object
	 * @param $ctype type string
	 * {
	 * 	"PROBLEM",
	 * 	"SOLUTION"
	 * }
	 * @param $cid content id
	 * @param $uid 
	 * @return vote or false
	 */
	public static function fetchVote( $mysqli, $ctype, $cid, $uid ){
		if (!$stmt = $this->_mysqli->prepare("SELECT `ctype`, `cid`, `uid`, `vote` FROM votes WHERE `ctype`= ? AND `cid` = ? AND`uid` = ?")) {
			throw new MindcloudException($mysqli->error, "vote", __FUNCTION__);
		}

		$stmt->bind_param("sii", $ctype, $cid, $uid);
		$stmt->execute();
		$stmt->store_result();

		// Throws exception if multiple rows are returned
		if($stmt->num_rows > 1)
			throw new MindcloudException("Multiple entries", "vote",__FUNCTION__);
		// stores results from query in variables corresponding to statement
		$stmt->bind_result($db_ctype, $db_cid, $db_uid, $db_vote);
		$stmt->fetch();

		$rows = $stmt->num_rows;

		$stmt->close();

		if($rows < 1)
			return false;

		return $db_vote;
	}
	/**
	 * fetchScore()
	 * Provides score from up votes and down votes
	 * @param $_mysqli mysqli object
	 * @param $ctype type string
	 * {
	 * 	"PROBLEM",
	 * 	"SOLUTION"
	 * }
	 * @param $cid content id
	 * @return $score - score of content
	 */
	public static function fetchScore( $mysqli, $ctype, $cid ){
		$up = Vote::fetchTotal( $mysqli, $ctype, $cid, 1 );

		$down = Vote::fetchTotal( $mysqli, $ctype, $cid, -1 );

		return $up - $down;
	}


	/**
	 * fetchVoteTotal()
	 * Provides vote totals
	 * @param $_mysqli mysqli object
	 * @param $ctype type string
	 * {
	 * 	"PROBLEM",
	 * 	"SOLUTION"
	 * }
	 * @param $cid content id
	 * @param $vote vote -1 or 1 (down, up)
	 * @return $votes - votes of content;
	 */
	public static function fetchTotal( $mysqli, $ctype, $cid, $vote ){
		if (!$stmt = $mysqli->prepare("SELECT `ctype`, `cid`, `vote` FROM votes WHERE `ctype`= ? AND `cid` = ? AND`vote` = ?")) {
			throw new MindcloudException($mysqli->error, "vote", __FUNCTION__);
		}

		$stmt->bind_param("sii", $ctype, $cid, $vote);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->close();

		return $rows;
	}
}