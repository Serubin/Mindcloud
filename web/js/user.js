/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function user(url){
	if(url.length == 3 && url[1].toLowerCase() == "notification"){
 		var req = new APICaller("notification", "load");
		var params = {id: url[2]};
		req.send(params, function(result){
			log.debug("Notifications", "Redirecting to notifiction url: " + result.url);
			if(result)
				ph.pageRequest(result.url);
			else
				ph.pageRequest("/dashboard");
		});
 	} else {
 		ph.pageRequest("error-404", false);
 	}
}

 function preuser(url){
 	if(url.length == 2 && url[1].toLowerCase() == "logout"){
 		log.debug("User", "Logging user out");
 		var req = new APICaller("user", "logout");
 		req.send({}, function(result){
 			if (result)
 				ph.pageRequest("/login");
 			else
 				new alertHandler("alert", "There was an error logging you out. Please try again later or ask us for help");
 		});
 	}

 	if(url.length == 2 && url[1].toLowerCase() == "settings"){
 		log.debug("User", "Launching settings");
 		ph.pageRequest("/user-settings", false);
 	} 
 	// Notification handling
 	else if(url.length == 3 && url[1].toLowerCase() == "notification"){
 		log.debug("User", "Processing notification");

 		var req = new APICaller("notification","update");
 		var params = {
 			id: url[2],
 			seen: 1
 		};
 		req.send(params, function(result){
 			log.debug("Notifications", "Notification " + url[2] + " has been marked as seen.");
 			log.debug("Notifications", "Marked as seen result: " + result);
 			console.log(notificationTopbar);
 			notificationTopbar.recount();
 		});
 	}
 }