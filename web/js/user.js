/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

 function preuser(url){
 	if(url.length == 2 && url[1] == "logout"){
 		log.debug("User", "Logging user out");
 		var req = new APICaller("user", "logout");
 		req.send({}, function(result){
 			if(result)
 				ph.pageRequest("/login");
 			else
 				new alertHandler("alert", "There was an error logging you out. Please try again later or contact our support");
 		})
 	}
 }