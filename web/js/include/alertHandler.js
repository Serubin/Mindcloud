/******************************************************************************
 * alertHandler.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Handles alert utility
 *****************************************************************************/
function alertHandler(type, html) {
	var _this = this;

	var timeout = 30 * 1000; // 30 seconds

	if (arguments.length == 3) {
		timeout = arguments[2];
	}

	var $alertBox = $("<div></div>")
		.data("alert", true)
		.addClass("alert-box")
		.addClass(type)
		.css("display", "none");

	var $alertInner = $("<div></div>")
		.addClass("alert-inner")
		.html(html);

	var $alertClose = $("<span></span>")
		.addClass(".keep-native")
		.addClass("close")
		.html("&times;");

	$alertBox.append($alertInner)
		.append($alertClose);

	$("#alert-wrapper").prepend($alertBox);
	$alertClose.click(function() {
		inClose();
	});

	$(document).foundation('alert', 'reflow');

	$alertBox.fadeIn(300);

	log.debug("AlertHandler", "Opening alert");

	setTimeout(function() {
		log.debug("AlertHandler", "Closing alert after timer");
		inClose();
	}, timeout);


	this.close = function() {
		inClose()
	}

	function inClose() {
		$alertBox.fadeOut(300, function() {
			$alertBox.remove();
		});
	}
}