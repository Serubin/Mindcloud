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
  * @param $withThisThing this guy changes what this thing does
  * @returns data or true on success.
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
	 	return $e;	
	 }
 }

// No ? > (minues the space) at the end of any php files 
