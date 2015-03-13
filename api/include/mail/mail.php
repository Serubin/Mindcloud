<?php
/*
 * Mail Script
 * To Be used with Solomon's Login Script 
 *
 *
 * Author: Serubin323 (Solomon Rubin)
 * Version: 1.0 - beta/dev
 * Do not distribute
 *
 *
 *
 * The following should not be touched unless you know what your doing, even then, don't.
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */
 
require_once './include/mail/class.phpmailer.php';
define('GUSER', 'noreply@serubin.net'); // GMail username
define('GPWD', 'tntBlack!'); // GMail password

define('master', '
<div style="height:45px;width:100%;background:#4754a4;">
</div>
<div style="margin:25px auto;">
	%BODY%
</div>
<div style="background:#4754a4;width:100%;height:49px;color:#aaa;">
<p style="padding:10px;">This message was automatically generated a <a style="color:#f7f7f7;border-bottom:1px #f7f7f7 dashed;text-decoration:none;" href="mindcloud.io">mindcloud.io</a>. To <a href="mindcloud.io/preferences/unsubscribe">click here to unsubscribe</a>
</div>');

class Mail{

	private static function mailer($to, $from, $from_name, $subject, $body, $master) { 
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		$mail->Username = GUSER;  
		$mail->Password = GPWD;           
		$mail->IsHTML(true); 
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = str_replace('%BODY%',$body,$master);
		$mail->AddAddress($to);
		if(!$mail->Send()) {
			return false;
		} else {
			return true;
		}
	}

	public static function send($email, $subject, $body){

		return Mail::mailer($email,"noreply@serubin.net","Mindcloud - No Reply", $subject, $body, master);		

	}
}
?>