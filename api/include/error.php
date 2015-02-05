<?php
/******************************************************************************
 * error.php
 * @author Michael Shullick
 * 31 January 2015
 * © mindcloud
 * This file contains the individual exceptions possible with every module.
 * Error digits are in hexadecimal.
 *****************************************************************************/

class MindcloudException extends Exception {

	private $module_id;
	private $function_id;

	public function __construct($msg, $module, $function) {
		parent::__construct($msg, (int) ($MODULE_ID . $function));

		$this->module_id = $module;
		$this->function_id = $function;

	}

	/*
	 * stringify()
	 * @return string containing the info of this exception
	 */
	public function stringify() {
		return 	$module_id . $function_id . $this->getMessage();
	}

}


/*
 * Class for problems in Users
 */
class UserException extends MindcloudException {

	$MODULE_ID = '0';

	// Failure code points for individual function
	private static $codes = array(
			'REGISTER' => '0',
			'LOGIN' => '1',
			'CHECK' => '2'
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}
}

class PostException extends MindcloudException {

	$MODULE_ID = '1';

	// Failure code points for individual function
	private static $codes = array(
			
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}

}

class ProblemException extends MindcloudException {

	$MODULE_ID = '2';

	// Failure code points for individual function
	private static $codes = array(

		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}

}

class SolutionException extends MindcloudException {
	
	$MODULE_ID = '3';

	// Failure code points for individual function
	private static $codes = array(

		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}

}

class ThreadException extends MindcloudException {

	$MODULE_ID = '4';

	// Failure code points for individual function
	private static $codes = array(
		
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}

}

class ForumException extends MindcloudException {

	$MODULE_ID = '5';

	// Failure code points for individual function
	private static $codes = array(
		
		);

	public function __construct($msg, $function) {
		parent::__construct($msg, $MODULE_ID, $function);
	}

}

