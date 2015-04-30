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

	var $alertBox = $("<div></div>");
	$alertBox.data("alert", true);
	$alertBox.addClass("alert-box");
	$alertBox.addClass(type);
	$alertBox.css("display", "none");

	var $alertInner = $("<div></div>");
	$alertInner.addClass("alert-inner");
	$alertInner.html(html);

	var $alertClose = $("<span></span>")
	$alertClose.addClass(".keep-native");
	$alertClose.addClass("close");
	$alertClose.html("&times;")

	$alertBox.append($alertInner);
	$alertBox.append($alertClose);

	$("#alert-wrapper").prepend($alertBox);
	$alertClose.click(function() {
		$alertBox.remove();
	});

	$(document).foundation('alert', 'reflow');

	$alertBox.fadeIn(300);

	setTimeout(_this.close, timeout);


	this.close = function() {
		$alertBox.fadeOut(300, function() {
			$alertBox.remove();
		});
	}
}