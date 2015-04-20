<?php
/******************************************************************************
 * Flag.php
 * @author Michael Shullick, Solomon Rubin
 * 14 March 2015
 * Static class for managing flags on solutions and problems
 * All lines will fit with 80 columns. 
 *****************************************************************************/

class Flag {

	/**
	 * addFlag
	 * Creates a flag in the database associated with the given content.
	 */ 
	public static function addFlag( $mysqli, $cid, $uid, $flag_val ) {
		if (!$stmt = $mysqli->prepare("INSERT INTO `flags` (`uid`, `cid`, `value`)  VALUES (?, ?, ?)")) {
			throw new FlagException("prepare failed: " . $this->_mysqli->error, __FUNCTION__);
		}

		$stmt->bind_param("iii", $uid, $cid, $flag_val);
		$stmt->execute();
		return true;
	}
}