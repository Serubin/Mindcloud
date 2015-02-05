<?php
/******************************************************************************
 * standards.php
 * @author Oneof Us
 * 31 January 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/


class Thing {


	/**
	*	doAthing()
	* Here a thing is done. This is the controller for a generic thing.
	* Functions will always at least return false on failure, and either return
	* @param $withThisThing this guy changes what this thing does
	* @return data or true on success.
	*/
	function doAThing($withThisThing) {

	try {

		// underscores for variables names
		$this_variable = "something";
		$thing = new ThingObject();
		if (!doAnotherThing()) {
			throw new Exception(0001);
		}

		return true;


		} catch (Exception $e) { 
		return $e;	
		}
	}
}

class ThingObject {



}

// No ? > (minues the space) at the end of any php files 
