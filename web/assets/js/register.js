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