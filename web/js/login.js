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

	/**
	 * Redirect user to the app if already logged in
	 */
	var req = new APICaller("user", "check");
	req.send({}, function (result) {
		if (result)
			ph.pageRequest("dashboard"); // loads dash
	});

	/**
	 * Login form submission, validation done by Foundation form-abide
	 */
	$('#login_form').on('valid', function() {

		var req = new APICaller('user', 'login');
		var params = {email: $("#login_email").val(), password:hex_sha512($("#login_password").val())};
		req.send(params, function(result) {
			console.log(result);
			switch (result) {
				case "unverified":
					alertHandler("info" ,"Your account has not been verified. Please check your email to verify the account.");
					break;
				case true:
					ph.pageRequest("dashboard");
					break;
				default:
					$("#password").val("");
					// TODO show prettier error
					alertHandler("alert", "Incorrect username or password")
					break;
				}
			});
	});
}

function prelogin(){

	var url = ph.parseUrl();

	// Handles validate
	if(url[1] == "validate" && url.length == 4){
		var req = new APICaller('user', 'verify');
		var params = {hash: url[2], uid: url[3]};
		req.send(params, function(result) {
			if(result == true){
				ph.pageRequest("login");
				alertHandler("info", "You've been verified! Go ahead and login");
			} else {
				console.log("failed!");
				ph.pageRequest("login");
			}
		});
	}
}
