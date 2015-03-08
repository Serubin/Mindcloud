<?php
/******************************************************************************
 * Tag.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 28 February 2015
 * Model for the object representation of a problem/solution tag.
 ******************************************************************************/


class Tag
{
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
	 * Autocomplete tags for problem/solution creation
	 * Tags in the user's inital input and returns the tags that start with that
	 * input. 
	 */
	public function suggestTag() {
		try {
			if (!isset($this->_params['input']))
				throw new TagException("No user input provided", __FUNCTION__);

			return Tag::search($this->_mysqli, $this->params['input']);

		}
		catch (TagException $e) {
			return $e;
		}
	}

	public function checkTag() {
		try {
			if (!isset($this->_params['identifer'])) {
				throw new TagException("No identifier provided", __FUNCTION__);
			}

			$tag = new TagObject($this->_mysqli);
			$tag->identifer = $this->_params['identifiers'];

			return ($tag->identifierExists) ? $tag->loadId() : -1;
		} catch (TagException $e) {
			return $e;
		}
	}
}