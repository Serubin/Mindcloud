<?php
/******************************************************************************
 * Thread.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a forum thread for discussion. Tied
 * to one forum.
 ******************************************************************************/

class Thread
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	public function create() {
		
	}
}