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
	 		throw new Exception("This is the specific problem");
	 	}

	 	return true;


	 } catch (Exception $e) {
	 	error_log("Generic description $e->getMessage()");
	 	return false;
	 }
 }

// No ? > (minues the space) at the end of any php files 