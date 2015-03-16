<?php
/******************************************************************************
 * Thread.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a forum thread for discussion. Tied
 * to one forum.
 ******************************************************************************/

class Thread
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	/**
	 * Create Thread
	 * submits to the database a newthread with the passed request parameters.
	 */
	public function createThread() {
		try {

			// check for required parameters
			if (!isset($this->_params['header'], $this->_params['body'], $this->_params['problem_id'], $_SESSION['uid'])) {
				throw new ThreadException("unset vars; cannot create thread.", __FUNCTION__ );
			}

			// create thread
			$new_thread = new ThreadObject($this->_mysqli);
			$new_thread->creator = $_SESSION['uid'];
			$new_thread->heading = $this->_params['header'];
			$new_thread->body = $this->_params'body'];
			return $new_thread->create();

		} catch (ThreadException $e) {

		}
		
	}

	/**
	 * lockThread
	 * locks thread from being changed, but maintains visibility to users.
	 */
	public function lockThread() {
	
	}

	/**
	 * loadThread
	 * Gets thread content and associated posts
	 */
	public function loadThread() {


	}

	/**
	 * flagThread
	 * submit flag on this thread
	 */
	public function flagThread() {

	}

	/**
	 * editThread
	 * submits updates on an existing thread
	 */
	public function editThread() {

	}

	/**
	 * hideThread
	 * Hides thread from user visibility
	 * Primarily for administrative purposes
	 */
	public function hideThread() {
	
	}
}