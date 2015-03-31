/******************************************************************************
 * login.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for login page
 *****************************************************************************/

function login(){
	/**
	 * Overrides 
	 * + password: Match all alphanumeric character and predefined wild characters.
	  		Password must consists of at least 8 characters and not more than 25 
	   		characters.
	 */
	$(document).foundation({
		abide: {
			patterns: {
				password: /^([a-zA-Z0-9@*#&!]{8,25})$/
			}
		}
	});

	$("#reload-captcha").click(function(){
		d = new Date();
		$("#captcha-img").attr("src","/assets/images/captcha.php?"+d);
	});

	/**
	 * Login form submission, validation done by Foundation form-abide
	 */
	$('#login_form').on('valid', function() {

		var req = new APICaller('user', 'login');
		var params = {email: $("#login_email").val(), password:hex_sha512($("#login_password").val()), login_captcha:$("#login_captcha").val()};
		req.send(params, function(result) {
			switch (result) {
				case "unverified":
					alertHandler("info" ,"Your account has not been verified. Please check your email to verify the account.");
					return;
					break;
				case "captcha":
					alertHandler("alert", "You have logged in incorrectly too many times. Please verify that you are not a robot.");
					$("#c_input").html('<input type="text" name="login_captcha" id="login_captcha" required />');
					$(".captcha").fadeIn(300);
					return;
				case true:
					tp.reload();
					ph.pageRequest("dashboard");
					break;
				default:
					$("#password").val("");
					alertHandler("alert", "Incorrect username or password")
					break;
				}
			});
	});
}

function prelogin(){


	//Redirect user to the app if already logged in
	var req = new APICaller("user", "check");
	req.send({}, function (result) {
		if (result)
			ph.pageRequest("dashboard"); // loads dash
	});
	
	// Reloads topbar for consistenacy
	tp.reload();

	var url = ph.parseUrl();

	// Handles validate
	if(url[1] == "validate" && url.length == 4){
		log.debug("Login","processesing validate");
		var req = new APICaller('user', 'verify');
		var params = {hash: url[2], email: url[3].replace("-", ".")};
		req.send(params, function(result) {
			if(result === true){
				ph.pageRequest("/login");
				log.debug("Login", "Validate was successful");
				alertHandler("info", "You've been verified! Go ahead and login");
			} else {
				log.debug("Login", "Validate was unsuccessful");
				ph.pageRequest("/login");
			}	
		});

		return false;
	}
	return true;
}
