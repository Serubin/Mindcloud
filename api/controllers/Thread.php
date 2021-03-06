<?php
/******************************************************************************
 * Thread.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a forum thread for discussion. Tied
 * to one forum.
 ******************************************************************************/

require_once("models/ThreadObject.php");
require_once("models/PostObject.php");
require_once("models/NotificationObject.php");
require_once("models/ProblemObject.php");

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
			if (!isset( $_SESSION['uid'] , $this->_params['problem_id'], $this->_params['subject'], $this->_params['body'])) {
				error_log(json_encode($this->_params));
				throw new ThreadException("unset vars; cannot create thread.", __FUNCTION__ );
			}

			// sanitize
			$subject = filter_var($this->_params['subject'], FILTER_SANITIZE_STRING);
			$body = filter_var($this->_params['body'], FILTER_SANITIZE_STRING);	

			// create thread object
			$new_thread = new ThreadObject($this->_mysqli);
			$new_thread->op = $_SESSION['uid'];
			$new_thread->subject = $subject;
			$new_thread->body = $body;
			$new_thread->problem_id = $this->_params['problem_id'];
			if (!$new_thread->create()) {
				throw new ThreadException("Something went wrong?", __FUNCTION__);
			}

			// load the data on the problem so we can get a handle on the problem poser
			$p = new ProblemObject($this->_mysqli);
			$p->id = $this->_params['problem_id'];
			$p->loadPreview();

			// notify the poser of the problem if he is not also the poster of the thread
			if ($_SESSION['uid'] != $p->uid) {
				NotificationObject::notify($p->uid, "/problem/" . $p->id, "A new thread has been created on \"" . 
					$p->title . "\"", $this->_mysqli);
			}

			// return success
			return $new_thread;

		} catch (ThreadException $e) {
			return $e;
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

		try {

			if (!isset($this->_params['thread_id'])) {
				throw new ThreadException("Failed to load thead, no id provided", __FUNCTION__);
			}

			// load the thread
			$thread = new ThreadObject($this->_mysqli);
			$thread->id = $this->_params['thread_id'];
			$thread->loadPosts();

			return $thread->posts;

		} catch (Exception $e) {
			return $e;
		}

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