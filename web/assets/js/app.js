/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

$(function(){

	// Loads navigation bar
	var success = function(result){
		$("#navigation").html(result);
	}

	$.ajax({
		url: "pages/topbar.php",
		success: success
	});

	// loads page
	pageRequest( parseGet() );

	// registers popstate event
	$(window).on("popstate", popHandler);
})