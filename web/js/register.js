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

	//TODO why no work

	$("#reload-captcha").click(function(){
		d = new Date();
		$("#captcha-img").attr("src","/assets/images/captcha.php?"+d);
	});

	 // Foundation form abide
	$("#registration_form").on('valid', function() {
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
				gender:$("#register_gender-m").val() || $("#register_gender-f").val() || $("#register_gender-o").val(),
				year:$("#register_year").val(),
				captcha:$("#register_captcha").val(),
			};

		// React on the response from the server
		req.send(params, function(result) {
			console.log(result);
			if (result == true) {
				new alertHandler("info","<p>You've been registered! Check your email to confirm your account</p>");
				
				// Redirect browser page
				ph.pageRequest("login");
				
			}
			else {
				if(result == "captcha-mismatch") {
					console.log("merp");
					new alertHandler("alert", "You're captcha code couldn't be verified. Are you human?");
					return;
				}
				if(result == "duplicate-email") {
					new alertHandler("alert", "This email has already been registered");
					return;
				} else {
					new alertHandler("alert", "There was an error processing your request. Pleast try again later");
				}	
			}
		});
	}

	// populate data of birthyear selector
	for (var y = 2014; y >= 1900; y--) {
		$("#register_year").append("<option value=\"" + y + "\">" + y + "</option>");
	}
}

function preregister(){
	//Redirect user to the app if already logged in
	var req = new APICaller("user", "check");
	req.send({}, function (result) {
		if (result)
			ph.pageRequest("dashboard"); // loads dash
	});
}