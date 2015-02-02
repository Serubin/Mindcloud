<?php
/******************************************************************************
 * Forum.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a forum.
 * Any instance of a forum is specific to one idea.
 ******************************************************************************/

class User
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}
}