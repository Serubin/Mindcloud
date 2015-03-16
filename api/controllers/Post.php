<?php
/******************************************************************************
 * Post.php
 * Authors: Solomon Rubin, Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Model for the object representation of a thread post. Tied to one specific
 * thread.
 ******************************************************************************/

require_once("models/PostObject.php");

class Post
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	/*
	 * create new post
	 */
	public function createPost() {
		try {

			// check for the right vars
			if (!isset($_SESSION['uid'], $this->_params['thread_id'], $this->_params['body'])) {
				throw new PostException("Unset vars", __FUNCTION__);
			}	

			// cleanse body
			$body = filter_var($this->_param['body'], FILTER_SANITIZE_STRING);

			// create the problem
			$new_post = new PostObject($this->_mysqli);
			$new_post->uid = $_SESSION['uid'];
			$new_post->thread_id = $this->_params['thread_id'];
			$new_post->body = $body;
			$new_post->create();

			// return success
			return true;

		} catch (PostException $e) {
			return $e;
		}
	}

	/**
	 * flag this post
	 */
	public function flagPost() {
		try {

		} catch (PostException $e) {

		}
	}

	/**
	 * delete/hide this post
	 */
	public function deletePost() {
		try {

		} catch (PostException $e) {

		}
	}

	/**
	 * edit post
	 */
	public function editPost() {
		try {

		} catch (PostException $e) {

		}
	}
}