<?php
/******************************************************************************
 * standards.php
 * @author Oneof Us
 * 31 January 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/

 /**
  *	doAthing()
  * Here a thing is done.
  * Functions will always at least return false on failure, and either return
  * data or true on success.
  * @param $withThisThing this guy changes what this thing does
  */
 function doAThing($withThisThing) {

 	try {

	 	// underscores for variables names
	 	$this_variable = "something";
	 	if (somethingFails()) {
	 		throw new Exception(0001);
	 	}

	 	return true;


	 } catch (Exception $e) {
	 	$error = new Error($e->getCode()); 
	 	$msg = "Error in doAThing: $error->message()";
	 	error_log($msg);
	 	return $error;
	 }
 }

// No ? > (minues the space) at the end of any php files 