/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

var user_preload_transaction = false;

function user(url) {

	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if (!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});

	if (url.length == 2 && url[1].toLowerCase() == "logout") {
		user_preload_transaction = true; //  avoid 404
		log.debug("User", "Logging user out");
		var req = new APICaller("user", "logout");
		req.send({}, function(result) {
			if (result)
				ph.pageRequest("/login");
			else
				new alertHandler("alert", "There was an error logging you out. Please try again later or ask us for help");
		});
	} else if (url.length == 2 && url[1].toLowerCase() == "settings") {
		log.debug("User", "Launching settings");
		ph.pageRequest("/user_settings", false);
	} else if (url.length == 3 && url[1].toLowerCase() == "notification") {
		log.info("User", "Notification handler started");
		// Handles updating read
		var req = new APICaller("notification", "update");
		var params = {
			id: url[2],
			read: 1
		};
		req.send(params, function(result) {
			log.debug("Notifications", "Notification " + url[2] + " has been marked as read.");
			log.debug("Notifications", "Marked as read result: " + result);
			console.log(notificationTopbar);
			notificationTopbar.recount();
		});

		// Loads notification
		var req = new APICaller("notification", "load");
		var params = {
			id: url[2]
		};
		req.send(params, function(result) {
			log.debug("Notifications", "Redirecting to notifiction url: " + result.url);
			if (result)
				ph.pageRequest(result.url);
			else
				ph.pageRequest("/dashboard");
		});
	} else {
		ph.pageRequest("error-404", false);
	}
}