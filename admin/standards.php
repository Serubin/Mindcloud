<?php
/******************************************************************************
 * standards.php
 * @author Michael Shullick, Solomon Rubin
 * 31 January 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/


/**
* Thing
* Here a thing is done. This is the controller for a generic thing.
* The controller is basically the runner of the model class.
* Functions will always at least return either the model's data on success
* or the model's excetion on fail.
*/
class Controller {

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
	* doAThing()
	* This function creates an instance of the model, sets the appropriate
	* member vars for the model, and runs the model's respective functions.
	* @param $withThisThing this guy changes what this thing does
	* @return data or true on success.
	*/
	function action() {

		if (isset($this->_params['foo'], ...)) {

			// instantiate the model
			// Pass the db handler to the constructor if needed
			$thing = new ThingObject($this->_mysqli);

			// Set the approprate member variables of the model
			$this->member_data_foo = $_params['foo'];
			
			// Perform the actual model actions
			// Will return true/data on success, or exception on fail
			return $thing->doAThing();
		} 
		else {
			// Return false on invalid request
			return false;
		}


	}
}

/**
 * model format
 * + All exception are thrown in the model
 * + All database calls are in the model
 */
class ThingObject {

	// underscores for variables names
	// Not all will be set at each instantation, only those needed for the 
	// action
	public $member_data_foo;
	public $member_data_bar;

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
	* doAThing()
	*
	* @return data / true on success, exception on fail
	*/
	function doAThing() {

		// All exection in model is wrapped in try/catches
		try {
			// underscores for variables names
			$this_variable = "something";
			
			if (!doSomethingImportant()) {
				// throw a module-specific exception
				// PROCESS can just be the name of the function, but it must
				// correlate with what the model is trying to do or the general
				// goal of this action. E.g., login or register for the User
				// model.
				throw new ThingException("Brief explaination of what happened",
					"PROCESS");
			}

			// Return true or data on success
			return true;

		} catch (Exception $e) {
			// Return exception on fail
			return $e;
		}
	}

}

// No ? > (minues the space) at the end of any php files 
