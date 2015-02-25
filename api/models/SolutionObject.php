<?php
/******************************************************************************
* SolutionObject.php
 * @author Michael Shullick, Solomon Rubin
 * 20 Febuary 2015
 * This is a file that will describe the standards used for mindcloud.
 * All lines will fit with 80 columns. 
 *****************************************************************************/


/*
CREATE TABLE `solutions` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `shorthand` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` int(11) unsigned NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/
class SolutionObject{

	private $_mysqli;

	// member vars
	public $id;
	public $problemId;
	public $shorthand; // Short hand title/url
	public $title;
	public $description; // TODO strip tags on submit to filter out html
	public $created;
	public $creator;
	public $status;
	// TODO
	// TODO: content handlers
	// TODO: activity
	// TODO: forum/thread/posts

	/**
	 * Constructor
	 * Constructor will only ever require db handler (optional but likely)
	 * @param $mysqli db handler
	 */
	public function __construct($mysqli) {
		$this->_mysqli = $mysqli;
	}
}