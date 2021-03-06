<?php
/******************************************************************************
 * error.php
 * @author Michael Shullick
 * 31 January 2015
 * © mindcloud
 * This file contains the individual exceptions possible with every module.
 * Error digits are in hexadecimal.
 *****************************************************************************/

/**
 * MindcloudException
 * Contains any functions we may need for app-specific exceptions.
 * For use with module-specific exception handlers.
 */
class MindcloudException extends Exception {

	// numeric id for the individual module of error
	private $module_id;

	// numeric id for the individual function in which the error occurred
	private $function_id;

	/**
	 * consutructor
	 * @param $msg String containing an explaination of what happened
	 * @param $module numeric id for the individual module of error
	 * @param $function numeric id for theindividual function that failed
	 */
	public function __construct($msg, $module, $function) {
		parent::__construct($msg, (int) ($module . $function));

		$this->module_id = $module;
		$this->function_id = $function;

	}

	/*
	 * stringify()
	 * @return a pretty string containing the info of this exception
	 */
	public function stringify() {
		return 	"In " . $this->module_id . ", " . $this->function_id . ": " . $this->getMessage();
	}

}

/*
 * Class for exceptions in handling Users
 * Module ID: 0
 */
class UserException extends MindcloudException {

	private $MODULE_ID = '0';

	// Failure code points for individual function
	// codes deprecated?
	private static $codes = array(
			'REGISTER' => '0',
			'LOGIN' => '1',
			'CHECK' => '2'
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, "User" , $function);
	}
}

/*
 * Class for exceptions in handling posts
 * Module ID: 1
 */
class PostException extends MindcloudException {

	private $MODULE_ID = '1';

	// Failure code points for individual function
	private static $codes = array(
			
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in Problem handlers
 * Module ID: 2
 */
class ProblemException extends MindcloudException {

	private $MODULE_ID = '2';

	// Failure code points for individual function
	private static $codes = array(

		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in handling Solutions
 * Module ID: 3
 */
class SolutionException extends MindcloudException {
	
	private $MODULE_ID = '3';

	// Failure code points for individual function
	private static $codes = array(

		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in handling chat Threads
 * Module ID: 4
 */
class ThreadException extends MindcloudException {

	private $MODULE_ID = '4';

	// Failure code points for individual function
	private static $codes = array(
		
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions individual Forum
 * Module ID: 0
 */
class ForumException extends MindcloudException {

	private $MODULE_ID = '5';

	// Failure code points for individual function
	private static $codes = array(
		
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in Tag handlers
 * Module ID: 6
 */
class TagException extends MindcloudException {

	private $MODULE_ID = '6';

	// Failure code points for individual function
	private static $codes = array(

		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in Tag handlers
 * Module ID: 7
 */
class DashboardException extends MindcloudException {

	private $MODULE_ID = '7';

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}

/*
 * Class for exceptions in Flag handlers
 * Module ID: 8
 */
class FlagException extends MindcloudException {

	private $MODULE_ID = '8';

	public function __construct($msg, $function) {
		parent::__construct($msg, $this->MODULE_ID, $function);
	}

}