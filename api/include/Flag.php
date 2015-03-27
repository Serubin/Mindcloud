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
	public static addFlag( $mysqli, $ctype, $cid, $uid, $flag_val ) {
		if (!$stmt = $mysqli->prepare("INSERT INTO `flags` "))
	}
}