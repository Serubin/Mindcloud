/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

var ph; // page handler global variable

$(function(){

	var topbar = new topBar();
	topbar.load();

	// Loads page handler
	ph = new pageHandler({"pageLoc": "/pages/", "animations": true});

	// if index page
	if(ph.parseUrl()[0] == ""){
		ph.pageRequest("/welcome");
		return;
	}
	// loads page
	ph.pageRequest( ph.parseUrl(), false );
	
})