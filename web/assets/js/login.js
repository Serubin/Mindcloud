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

	/**
	 *  User registration
	 */

	 // Foundation form abide
	$("#registration_form").on('valid.fndtn.abide', function() {

		alert("forms look good; registering");
		register();

	});

	/**
	 * register()
	 * Executes api requests to register user.
	 */
	function register() {
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
			if (result == true) {
				alert("yer good");
				
				// Redirect browser page
				window.location.replace("http://mindcloud.io/web/")
				
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