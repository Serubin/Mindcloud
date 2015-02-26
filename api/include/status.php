<?php
/******************************************************************************
* Status.php
 * @author Michael Shullick, Solomon Rubin
 * 20 Febuary 2015
 * Fetches id <-> english
 * All lines will fit with 80 columns. 
 *****************************************************************************/


class Status{

	private $_mysqli;

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * fetchId()
	 * Provides id from name
	 * @param $name status string
	 */
	public function fetchId( $name ){
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `value` FROM status WHERE `name` = ?"))
			throw new MindcloudException($this->_mysqli->error, "status", __FUNCTION__);

		$stmt->bind_param("s", $name);
		$stmt->execute();
		$stmt->store_result();

		// stores results from query in variables corresponding to statement
		$stmt->bind_result($id, $value);
		$stmt->fetch();

		$stmt->close();

		if($stmt->num_rows < 1)
			return false;

		return $id;
	}

	/**
	 * fetchName()
	 * Provides name from id
	 * @param $id status id
	 */
	public function fetchName( $id ){
		if (!$stmt = $this->_mysqli->prepare("SELECT `id`, `value` FROM status WHERE `id` = ?"))
			throw new MindcloudException($this->_mysqli->error, "status", __FUNCTION__);

		$stmt->bind_param("i", $value);
		$stmt->execute();
		$stmt->store_result();

		// stores results from query in variables corresponding to statement
		$stmt->bind_result($id, $value);
		$stmt->fetch();

		$stmt->close();

		if($stmt->num_rows < 1)
			return false;

		return $value;
	}
}