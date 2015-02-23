/******************************************************************************
 * account.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

// initalize foundation last
$(document).foundation();
/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

var ph; // page handler global variable

$(function(){
	// Loads page handler

	ph = new pageHandler({"pageLoc": "pages/", "animations": true});

	// Loads navigation bar
	var navLoader = new pageHandler({
		"pageLoc": "pages/", 
		"registerEvents": false, 
		"contentDiv": "#navigation"
	});

	navLoader.pageRequest("topbar", false);

	// loads page
	ph.pageRequest( ph.parseUrl(), false );
	
})
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
	 * TODO; just change body content?
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
			switch (result) {
				case true:
					ph.pageRequest("dashboard");
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

/******************************************************************************
 * register.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for user registeration page
 *****************************************************************************/

/**
 *  User registration
 */
function register(){
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

	 // Foundation form abide
	$("#registration_form").on('valid', function() {

		alert("forms look good; registering");
		processRegistration();

	});

	/**
	 * processRegistration()
	 * Executes api requests to register user.
	 */
	function processRegistration() {
		var req = new APICaller("user", "create");

		// Prepare the submission parameters
		var params = 
			{
				first_name:$("#register_firstname").val(),
				last_name:$("#register_lastname").val(),
				email:$("#register_email").val(),
				password:hex_sha512($("#register_password").val()),
				gender:$("#register_gender").val(),
				year:$("#register_year").val(),
			};

		// React on the response from the server
		req.send(params, function(result) {
			console.log(result);
			if (result == true) {
				alert("yer good");
				
				// Redirect browser page
				ph.pageRequest("login");
				
			}
			else {
				$("#reg_error_alert").text(result);
				$("#reg_error_alert").css("display", "block");
				//$("#popup_msg").text(result);
				//$("#err_popup").popup("open
				console.log(result);		
			}
		});
	}

	// populate data of birthyear selector
	for (var y = 2014; y >= 1900; y--) {
		$("#register_year").append("<option value=\"" + y + "\">" + y + "</option>");
	}
}