/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

var ph; // page handler global variable

$(function(){
	// Loads page handler

	ph = new pageHandler({"pageLoc": "pages/", "animations": true});

	// Loads navigation bar
	var navLoader = new pageHandler({
		"pageLoc": "pages/", 
		"registerEvents": false, 
		"contentDiv": "#navigation"
	});

	navLoader.pageRequest("topbar");

	// loads page
	ph.pageRequest( ph.parseUrl() );
	
})