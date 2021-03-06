<?php
/******************************************************************************
 * TagObject.php
 * @author Michael Shullick, Solomon Rubin
 * 6 February 2015
 * Model representation of a problem.
 *****************************************************************************/

class TagObject {

	private $_mysqli;

	// member vars
	public $id;
	public $identifier;

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}

	/**
	 * create()
	 * Ensures that a tag with this identifier does not exist, then submits new tag 
	 * to the database
	 */
	public function create() {

			// ensure we have an identifier
			if (!isset($this->identifier)) {
				throw new TagException("Could not create tag; no identifier given.", __FUNCTION__);
			}

			// ensure the identifer is new
			if ($this->identifierExists()) {
				throw new Exception("Could not create tag: identifier exists", __FUNCTION__);
			}

			// Otherwise keep going and create new tag
			if (!$stmt = $this->_mysqli->prepare("INSERT INTO `tags` (`identifier`) VALUES (?)")) {
				throw new TagException($this->_mysqli->error, __FUNCTION__);
			}
			$stmt->bind_param("s", $this->identifier);
			$stmt->execute();

			$this->loadId();	

			return true;
	}

	/**
	 * identiferExists()
	 * Checks whether a tag with the set identifier already exists.
	 * USE idExists when possible, as it is based on numberic comparison
	 */
	public function identifierExists() {

			// ensure we have an identifier
			if (!isset($this->identifier)) {
				throw new TagException("Could not create tag; no identifier given.", __FUNCTION__);
			}

			// See if there are any tags with this identifer
			if (!$stmt = $this->_mysqli->prepare("SELECT * FROM `tags` WHERE `identifier` = ?")) {
				throw new TagException($this->_mysqli->error, __FUNCTION__);
			}
			$stmt->bind_param('s', $this->identifier);
			$stmt->execute();
			$stmt->store_result();
			return ($stmt->num_rows != 0) ?  true : false;

	}

	/**
	 * idExists
	 * Checks whether a tag with this numeric id exists in the db.
	 **/
	public function idExists() {

		try {
			// ensure we have an identifier
			if (!isset($this->id)) {
				throw new TagException("Could not check tag id; no id given.", __FUNCTION__);
			}

			// See if there are any tags with this identifer
			if (!$stmt = $this->_mysqli->prepare("SELECT * FROM `tags` WHERE `id` = ?")) {
				throw new TagException($this->_mysqli->error, __FUNCTION__);
			}
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$stmt->store_result();
			return ($stmt->num_rows == 1) ?  true : false;

		} catch (TagException $e) {

			error_log("Exception encountered in " . __FUNCTION__ . ": " . $e->getMessage());

			// default to true so the program procedes as if the tag exists
			return true;
		}
	}

	/**
	 * loadID
	 * Assuming that the identifer is set, load the tag id into this object.
	 * @return the tag's id
	 */
	public function loadId() {
		try {

			if (!isset($this->identifier)) 
				throw new TagException("Identifier not set", __FUNCTION__);

			if (!$stmt = $this->_mysqli->prepare("SELECT `id` FROM `tags` WHERE `identifier` = ?")) {
				throw new TagException($this->_mysqli->error, __FUNCTION__);
			}
			$stmt->bind_param("s", $this->identifier);
			$stmt->execute();
			$stmt->store_result();


			if ($stmt->num_rows != 1) {
				throw new TagException("There is/are " . $stmt->num_rows . " tag(s) with this identifer.", __FUNCTION__);
			}

			$stmt->bind_result($id);
			$stmt->fetch();

			// Store this tag's id.
			return $this->id = $id;

		} catch (TagException $e) {
			return $e;
		}
	}

	/**
	 * loadIdentifier
	 * Assuming that the id is set, load the tag identifer into this object.
	 */
	public function loadIdentifier() {
		try {

			if (!isset($this->id)) 
				throw new TagException("Identifier not set", __FUNCTION__);

			if (!$this->idExists)
				throw new TagException("There is no tag with this id.", __FUNCTION__);

			if (!$stmt = $this->_mysqli->prepare("SELECT `identifier` FROM `tags` WHERE `id` = ?")) {
				throw new TagException($this->mysqli->error, __FUNCTION__);
			}
			$stmt->bind_param("s", $this->id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($identifier);
			$stmt->fetch();

			// Store this tag's id.
			$this->identifier = $identifier;

		} catch (TagException $e) {
			return $e;
		}
	}

	/**
	 * isValid
	 * Checks that this tag is a valid, based on information in database.
	 */
	public function isValid() {

		try {
			if (!isset($this->id, $this->identifier)) {
				throw new TagException("Unset vars", __FUNCTION__);
			}

			if ($stmt = $this->_mysql->prepare("SELECT * FROM `tags` WHERE `id` = ? AND `identifer` = ?"))
				throw new TagException($this->_mysqli->error, __FUNCTION__);

			$stmt->bind_param("is", $this->id, $this->identifier);
			$stmt->execute();
			$stmt->store_result();
			switch ($stmt->num_rows) {
				case 0:
					return false;
					break;
				case 1: 
					return true;
					break;
				default:
					error_log("WARNING: tag duplicates found.");
					return true;
					break;
			}

		} catch (TagException $e) {
			return $e;
		}

	}

	/**
	 * createAssociation.
	 * Enters into tag associatios table an assocation between this tag and the
	 * passed ID. Assumes type will be "PROBLEM" and "SOLUTION".
	 */
	public function createAssociation($associate_id, $associate_type) {

			if (!isset($this->id)) {
				throw new TagException("Unset member vars", __FUNCTION__);
			}

			// Check that this tag exists; if it doesn't create it
			if (!$this->idExists()) {
				throw new TagException("Tag invalid: " . $this->id, __FUNCTION__);
			}
			else {
				$this->loadId();
			}

		if (!$stmt = $this->_mysqli->prepare("INSERT INTO `tag_associations` (`tag_id`, `associate`, `type`) VALUES (?, ?, ?)"))
				throw new TagException($this->_mysqli->error, __FUNCTION__);
		$stmt->bind_param("iis", $this->id, $associate_id, $associate_type);
		$stmt->execute();
		error_log("created association: error " . $stmt->error);
		return true;

	}

	/**
	 * search
	 */
	public static function search($mysqli, $input) {

		try {

			// Include in the search only tags that start with the input
			$search = $input . "%";

			// get first 10 suggestions
			if (!$stmt = $mysqli->prepare("SELECT `id`, `identifier` FROM `tags` WHERE `identifier` LIKE ? LIMIT 10"))
				throw new TagException($this->_mysqli->error, __FUNCTION__);

			$stmt->bind_param("s", $input);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $identifier);

			// array of search results
			$results = array();

			while ($stmt->fetch()) {
				$results[] = array($id, $identifier);
			}

		} catch(TagException $e) {
			error_log("Tag search failed: " . $e->getMessage);
			return false;
		}
	}

}