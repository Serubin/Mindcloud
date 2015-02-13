/******************************************************************************
 * login.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for login page
 *****************************************************************************/

/**
 * Overrides 
 * + password: Match all alphanumeric character and predefined wild characters.
  		Password must consists of at least 8 characters and not more than 25 
   		characters.
 */
function login(){
	$(document)
		.foundation({
			abide: {
				patterns: {
					password: /^([a-zA-Z0-9@*#&]{8,25})$/
				}
			}
		});

	/**
	 * Redirect user to the app if already logged in
	 * TODO; just change body content?
	 */
	var req = new APICaller("user", "check");
	req.send({}, function (result) {
		if (result)
			window.location.replace("https://mindcloud.io/");
	});

	/**
	 * Login form submission, validation done by Foundation form-abide
	 */
	$('#login_form').on('valid.fndtn.abide', function() {

		var req = new APICaller('user', 'login');
		var params = {email: $("#login_email").val(), password:hex_sha512($("#login_password").val())};
		req.send(params, function(result) {
			switch (result) {
				case true:
					window.location.replace("http://mindcloud.io/web/");
					break;
				default:
					$("#password").val("");
					// TODO show prettier error
					alert("Please try again.");
					break;
				}
			});
	});
}