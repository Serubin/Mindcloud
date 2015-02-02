<?php
/******************************************************************************
 * Post.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a thread post. Tied to one specific
 * thread.
 ******************************************************************************/

class Post
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}
}