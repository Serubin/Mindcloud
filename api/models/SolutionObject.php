<?php
/******************************************************************************
* SolutionObject.php
 * @author Michael Shullick, Solomon Rubin
 * 31 January 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/

class SolutionObject.php

	private $_mysqli;

	// member vars
	// TODO
	// TODO: content handlers
	// TODO: activity
	// TODO: forum/thread/posts

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}
}