<?php
/******************************************************************************
* Status.php
 * @author Michael Shullick, Solomon Rubin
 * 27 Febuary 2015
 * Fetches id <-> english
 * All lines will fit with 80 columns. 
 *****************************************************************************/


class Status {

	/**
	 * fetchId()
	 * Provides id from name
	 * @param $_mysqli mysqli object
	 * @param $name status string
	 */
	public function fetchId($_mysqli, $name ){
		if (!$stmt = $_mysqli->prepare("SELECT `id`, `value` FROM status WHERE `name` = ?")) {
			throw new MindcloudException($_mysqli->error, "status", __FUNCTION__);
		}

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
	 * @param $_mysqli mysqli object
	 * @param $id status id
	 */
	public function fetchName($_mysqli, $id ){
		if (!$stmt = $_mysqli->prepare("SELECT `id`, `value` FROM status WHERE `id` = ?")) {
			throw new MindcloudException($_mysqli->error, "status", __FUNCTION__);
		}

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