/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

var ph; // page handler global variable
var tp;

$(function(){

	tp = new topBar();

	// Loads page handler
	ph = new pageHandler({"pageLoc": "/pages/", "animations": true});

	// if index page
	if(ph.parseUrl()[0] == ""){
		log.debug("App", "No start page, redirecting");
		ph.pageRequest("/welcome");
		return;
	}
	// loads page
	ph.pageRequest( ph.parseUrl(), false );
	
})