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
require_once("models/NotificationObject.php");

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
			if (!isset($_SESSION['uid'], $this->_params['thread_id'], $this->_params['post_body'])) {
				throw new PostException("Unset vars", __FUNCTION__);
			}	

			// cleanse body
			$body = filter_var($this->_params['post_body'], FILTER_SANITIZE_STRING);

			// create the problem
			$new_post = new PostObject($this->_mysqli);
			$new_post->uid = $_SESSION['uid'];
			$new_post->thread_id = $this->_params['thread_id'];
			$new_post->body = $body;
			$new_post->create();

			// obtain the poster of the thread and of the problem
			$thread = new ThreadObject($this->_mysqli);
			$thread->id = $this->_params['thread_id'];
			$thread->loadPreview();

			// create a notification if the post is not made by the poster of the thread
			// here we're notifying only the creator of the thread
			if ($thread->op != $_SESSION['uid']) {
				NotificationObject::notify($thread->op, "/problem/" . $thread->problem_id, "A new post has been created on your thread, \"" .
					$thread->subject . "\"",
					$this->_mysqli);
			}

			// return success
			return $new_post;

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