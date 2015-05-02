<?php
/******************************************************************************
 * Help.php
 * Author: Michael Shullick, Solomon Rubin
 * ©mindcloud
 * 1 February 2015
 * Controller for help.
 ******************************************************************************/

// relative to index.php
require_once "include/mail/mail.php";

class Help
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	function sendHelp(){
		if(!isset($this->_params['name'], $this->_params['email'], $this->_params['subject'], $this->_params['body'], $this->_params['captcha'])){
			throw new Exception("Unset vars");
		}

		if($this->_params['captcha'] != $_SESSION['captcha'])
			return "captcha";

		$this->_params['body'] = "Please make sure to reply with \"Reply All\". You should hear back from us soon. <br/> ------------------------------ <br />" . $this->_params['body'];
		Mail::send(Array("rubin@mindcloud.io", "shullick@mindcloud.io", $this->_params['email']),$this->_params['name'] . " - " . $this->_params['subject'], $this->_params['body'] );

 		return true;
	}
}