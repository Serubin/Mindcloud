<?php
/******************************************************************************
 * error.php
 * @author Michael Shullick
 * 31 January 2015
 * © mindcloud
 * Generic error class for both mapping error codes to msgs.
 *****************************************************************************/

// always use require_once
include_once "/full/path/to/file";

class Error 
{
	public $code;
	public $message;

	// Static array mapping error codes to msgs.
	public static $codeMsg = array(
		0001 => "reserved"
		);

	public function __construct($code) {
		$this->code = $code;
		$this->message = Error::$codeMsg[$code];
	}
}
