/******************************************************************************
 * login.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for login page
 *****************************************************************************/

function login() {
	/**
	 * Overrides 
	 * + password: Match all alphanumeric character and predefined wild characters.
			Password must consists of at least 8 characters and not more than 25 
			characters.
	 */
	$(document).foundation({
		abide: {
			patterns: {
				password: /^([a-zA-Z0-9@*#&$.^!]{8,64})$/
			}
		}
	});

	// set background
	$("#splash-background").css("background-image", randImgSelect());
	window.document.title = "Mindcloud: Login";

	$("#reload-captcha").click(function() {
		d = new Date();
		$("#captcha-img").attr("src", "/assets/images/captcha.php?" + d);
	});

	/**
	 * Login form submission, validation done by Foundation form-abide
	 */
	$('#login_form').on('valid', function() {

		var req = new APICaller('user', 'login');
		var params = {
			email: $("#login_email").val(),
			password: hex_sha512($("#login_password").val()),
			login_captcha: $("#login_captcha").val()
		};
		req.send(params, function(result) {
			// handles return cases
			switch (result) {
				case "unverified":
					new alertHandler("info", "Your account has not been verified. Please check your email or <a href='/login/resend/" + $("#login_email").val().replace(/\./g, "-") + "'>click here to resend</a> the email.");
					return;
					break;
				case "captcha":
					new alertHandler("alert", "You have logged in incorrectly too many times. Please verify that you are not a robot.");
					$("#c_input").html('<input type="text" name="login_captcha" id="login_captcha" required />');
					// reloads captcha
					d = new Date();
					$("#captcha-img").attr("src", "/assets/images/captcha.php?" + d);

					$(".captcha").fadeIn(300); // fades in
					return;
					break;
				case true:
					tp.reload(); // reloads top bar
					initPoseProblem(); // initiates pose/create
					initCreateSolution();
					ph.pageRequest("dashboard");
					break;
				default:
					$("#password").val("");
					var alert = new alertHandler("alert", "Incorrect username or password");
					break;
			}
		});
	});
}

function prelogin() {


	//Redirect user to the app if already logged in
	var req = new APICaller("user", "check");
	req.send({}, function(result) {
		if (result)
			ph.pageRequest("dashboard"); // loads dash
	});

	// Reloads topbar for consistenacy
	tp.reload();

	var url = ph.parseUrl();

	// Handles validate
	if (url[1] == "validate" && url.length == 4) {
		log.debug("Login", "processesing validate");
		var req = new APICaller('user', 'verify');
		var params = {
			hash: url[2],
			email: url[3].replace(/-/g, ".")
		};
		req.send(params, function(result) {
			if (result === true) {
				ph.pageRequest("/login");
				log.debug("Login", "Validate was successful");
				alertHandler("info", "You've been verified! Go ahead and login");
			} else {
				log.debug("Login", "Validate was unsuccessful");
				ph.pageRequest("/login");
			}
		});

		return false;
	} else if (url[1] == "resend" && url.length == 3) {
		var req = new APICaller('user', 'resendVerification');
		var params = {
			email: url[2].replace(/-/g, ".")
		};

		req.send(params, function(result) {
			if (result) {
				log.debug("Login", "Validation resent");
				new alertHandler("info", "<p>Your email verification has been resent! Check your email to confirm your account</p>");
				ph.pageRequest("/login");
			} else {
				ph.pageRequest("/login");
			}
		});
	}
	return true;
}