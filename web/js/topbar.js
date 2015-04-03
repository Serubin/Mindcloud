/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for topbar
 *****************************************************************************/
// App Global
var notificationTopbar;

 function topBar(){
 	var _this = this;

 	// public functions
 	var load;
 	var reload;
 	var notificationElement;

 	// vars
 	var navLoader;
 	var $navLinks;

 	function construct(){
	 	// Loads navigation bar
		navLoader = new pageHandler({
			"pageLoc": "/pages/", 
			"registerEvents": false, 
			"contentDiv": "#navigation"
		});
		_this.load();
	}

	/* load()
	 * Loads topbar html and calculates links
	 * gets called when topbar object is created
	 */
	this.load = function(){
		navLoader.pageRequest("topbar", false);
		window["topbar"] = function(){
			$navLinks = $("#right-topbar");
			checkUser();
			log.info("Topbar", "Topbar loaded");
		}
	}

	/* reload()
	 * recalculates links
	 */
	this.reload = function(){
		checkUser();
		log.info("Topbar","Topbar reloaded");
	}

	/* checkUser()
	 * Checks if user is logged in and creates links based on login status
	 */
	function checkUser(){
		var req = new APICaller("user", "check");
		req.send({}, function(result){
			if(!result){
				log.debug("Topbar","User not logged in creating default links");
				defaultLinks($navLinks);
				return;
			}
			log.debug("Topbar","User logged in creating user links");
			userLinks(result, $navLinks);
		});
	}

	/* defaultLinks()
	 * Creates links for not logged in or default
	 * @param $links - right-topbar ul element
	 */
	function defaultLinks($links){
		$("#home-link").attr("href", "/login"); // sets home link to login

		$links.html(""); // clears links area
		$links.append(createTopbarItem("/login", "log in"));
		$links.append(createTopbarItem("/register", "sign up"));
		$links.append(createTopbarItem("/about", "what is this?"));
	}

	/* userLinks()
	 * Creates links for logged in
	 * @param $uid - user id for user
	 * @param $links - right-topbar ul element
	 */
	function userLinks(uid, $links){
		var req = new APICaller("user", "load");
		var params = {uid: "SESSION"};
		req.send(params, function(result){
			console.log(result);
			// Sets home link to dashboard
			$("#home-link").attr("href", "/dashboard");

			$links.html(""); // clears current link
			$links.append(createTopbarItem("#","pose a problem")); //TODO attach/create global pose problem context
			$links.append(createTopbarItem("#","pose a solution")); //TODO attach/create global pose solution context
			
			//Notifications
			notificationTopbar = new notificationElement();
			 $links.append(notificationTopbar.getElement());

			// formats name
			result.first_name = result.first_name.toLowerCase();
			result.last_name = result.last_name.toLowerCase()
			
			// Creates dropdown
			var $dropdownWrapper = createTopbarItem("users/" + result.first_name + "-" + result.last_name, result.first_name + " " + result.last_name);
			$dropdownWrapper.addClass("has-dropdown"); // foundation dropdown class
			
			// List for actual dropdown
			var $dropdown = $("<ul class='dropdown'><ul>");
			$dropdown.append(createTopbarItem("/user/settings", "account settings"));
			$dropdown.append(createTopbarItem("/user/logout", "log out"));
			$dropdownWrapper.append($dropdown);

			// Adds bar to page and allows foundation to do it's magic.
			$links.append($dropdownWrapper);
			$(document).foundation('topbar', 'reflow');
			$(".parent-link").css("display", "none", "important"); // Fixes magic
		});
	}

	/* createTopbarItem()
	 * Creates basic link li element
	 * @param link - url for link, shouldn't be html
	 * @param text - text for link, can be html
	 */
	function createTopbarItem(link, text){
		var $li = $("<li class=''></li>");
		var $a = $("<a></a>");

		$a.attr("href", link);
		$a.append(text);

		$li.append($a);

		return $li;
	}


	function notificationElement(){
		var __this = this;
		var $notificationEl;

		var getElement;
		var recount;
		var open;

		function construct(){
			$notificationEl = createTopbarItem("#","0");
			$notificationEl.children().attr("id", "notification_number");
			// Fetch notification number
			__this.recount();
		}
		
		this.getElement = function(){
			return $notificationEl;
		}

		this.recount = function(){
			var $notificationNum = $('#notification_number');
			var req = new APICaller("notification", "fetchAllUser");
			var params = {uid: "SESSION"};
			$notificationNum.addClass("hover");
			console.log($notificationEl);
			req.send(params, function(result){
				$notificationNum.html(result.length);
				setTimeout(function(){$notificationNum.removeClass("hover");}, 250);
			});
		}

		construct();
	}

	construct();
 }