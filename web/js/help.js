function help(){
	window.document.title = "Mindcloud: Help";
	// set background
	$("#splash-background").css("background-image", randImgSelect());

	d = new Date();
	$("#captcha-img").attr("src", "/assets/images/captcha.php?" + d);

	$("#reload-captcha").click(function() {
		d = new Date();
		$("#captcha-img").attr("src", "/assets/images/captcha.php?" + d);
	});

	$('#help_form').on('valid', function() {

		var req = new APICaller('help', 'send');
		var params = {
			name: $("#help_name").val(),
			email: $("#help_email").val(),
			subject: $("#help_subject").val(),
			body: $("#help_body").val(),
			captcha: $("#help_captcha").val()
		};
		req.send(params, function(result) {
			// handles return cases
			switch (result) {
				case "captcha":
					new alertHandler("alert", "YPlease verify that you are not a robot.");
					// reloads captcha
					d = new Date();
					$("#captcha-img").attr("src", "/assets/images/captcha.php?" + d);
					return;
					break;
				case true:
					new alertHandler("info", "Your message was sent! You should hear back soon!");
					ph.pageRequest("/help");
					break;
				default:
					new alertHandler("alert", "Something went wrong");
					break;
			}
		});
	});
}