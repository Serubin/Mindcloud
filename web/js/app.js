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

	var req = new APICaller("user", "check");
	req.send({}, function(result){
		if(result)
			connectNotifications();
	});

})

function connectNotifications(){
	log.debug("Notification Listener", "Starting!")
	var req = new APICaller("user", "loadConfidential");
	req.send({}, function(user){
		log.debug("Notification Listener", "Started!");
		var socket = io('http://mindcloud.loc:8000', {
	        transports: ['websocket']
	    });

	    socket.on(user.notification_hash, function (data) {
	    	var $notificationHTML = $("<a></a>");
	    	$notificationHTML.attr("href", data.url);
	    	$notificationHTML.html("<p>" + data.message + "</p>");

	        new alertHandler("info", $notificationHTML);

	        notificationTopbar.recount();
	    });
	});
}