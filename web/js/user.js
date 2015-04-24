/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

var user_preload_transaction = false;

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
 	} else if(user_preload_transaction) {
 		// just sit tight.
 	} else {
 		ph.pageRequest("error-404", false);
 	}
}

 function preuser(url){
 	if(url.length == 2 && url[1].toLowerCase() == "logout"){
 		user_preload_transaction = true; //  avoid 404
 		log.debug("User", "Logging user out");
 		var req = new APICaller("user", "logout");
 		req.send({}, function(result){
 			if(result)
 				ph.pageRequest("/login");
 			else
 				new alertHandler("alert", "There was an error logging you out. Please try again later or contact our support");
 		});
 	} else if(url.length == 2 && url[1].toLowerCase() == "settings"){
 		user_preload_transaction = true // avoid 404
 		log.debug("User", "Launching settings");
 		ph.pageRequest("/user-settings", false);
 	} 
 	// Notification handling
 	else if(url.length == 3 && url[1].toLowerCase() == "notification"){
 		log.debug("User", "Processing notification");
 		user_preload_transaction = true; // avoid 404

 		var req = new APICaller("notification","update");
 		var params = {
 			id: url[2],
 			read: 1
 		};
 		req.send(params, function(result){
 			log.debug("Notifications", "Notification " + url[2] + " has been marked as read.");
 			log.debug("Notifications", "Marked as read result: " + result);
 			console.log(notificationTopbar);
 			notificationTopbar.recount();
 		});

 	}
 }