/**
 * Overrides 
 * + password: Match all alphanumeric character and predefined wild characters.
  		Password must consists of at least 8 characters and not more than 25 
   		characters.
 */
$(document)
	.foundation({
		abide: {
			patterns: {
				password: /^([a-zA-Z0-9@*#&]{8,25})$/
			}
		}
	});

// Redirect user to the app if already logged in
var req = new APICaller("user", "check");
req.send({}, function (result) {
	if (result)
		window.location.replace("https://mindcloud.io/");	
});

// Login form submission, validation done by Foundation form-abide
$('#login_form').on('valid.fndtn.abide', function() {

	var req = new APICaller('user', 'login');
	var params = {email: $("#login_email").val(), password:hex_sha512($("#login_password").val())};
	req.send(params, function(result) {
		switch (result) {
			case true:
				window.location.replace("https://shoptimizeapp.com/web/app.php");
				break;
			default:
				$("#password").val("");
				// TODO show prettier error
				alert("Please try again.");
				break;
			}
		});
});


// On opening register modal
$(document).on('opened.fndtn.reveal', '#register_modal', function () {
  var modal = $(this);

	// populate data of birthyear selector
	for (var y = 2014; y >= 1900; y--) {
		$("#register_year").append("<option value=\"" + y + "\">" + y + "</option>");
	}
});

// initalize foundation last
$(document).foundation();