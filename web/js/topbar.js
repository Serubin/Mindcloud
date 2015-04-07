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
 	var repopulate;
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
			var $dropdownWrapper = createTopbarItem("javascript:void(0);", result.first_name + " " + result.last_name);
			$dropdownWrapper.addClass("has-dropdown"); // foundation dropdown class
			
			// List for actual dropdown
			var $dropdown = $("<ul><ul>").addClass("dropdown");
			$dropdown.append(createTopbarItem("/user/settings", "account settings"));
			$dropdown.append(createTopbarItem("/user/logout", "log out"));
			$dropdownWrapper.append($dropdown);

			// Adds bar to page and allows foundation to do it's magic.
			$links.append($dropdownWrapper);
			$(document).foundation('topbar', 'reflow');


			setTimeout(function(){
				$(".back h5").css("display", "none", "important"); 
			}, 1000);// Fixes magic

			notificationTopbar.recount();
		});
	}

	/* createTopbarItem()
	 * Creates basic link li element
	 * @param link - url for link, shouldn't be html
	 * @param text - text for link, can be html
	 */
	function createTopbarItem(link, text){
		var $li = $("<li class=''></li>");
		var $a = $("<a></a>").attr("href", link);

		$a.append(text);
		$li.append($a);

		return $li;
	}


	function notificationElement(){
		var __this = this;

		// data
		var total = 0;
		var notificationIds;

		var displayed = 0;

		// Dom
		var $notificationEl; // topbar item
		var $notificationNum;  // number item

		// public functions
		var getElement;
		var recount;
		var open;

		function construct(){
			// create topbar item
			$notificationEl = createTopbarItem("javascript:void(0);","0");
			// adds id to number element
			$notificationEl.children().attr("id", "notification_number")

			var $dropdown = $("<ul><ul>").addClass("dropdown").attr("id", "notification-dropdown");
			$notificationEl.append($dropdown);
		}
		
		/* getelement()
		 * returns topbar item element
		 */
		this.getElement = function(){
			return $notificationEl;
		}

		/* recount()
		 * recounts notifications and saves all ids
		 * handles animation
		 */
		this.recount = function(){
			return internalRecount();
		}


		this.repopulate = function(population){
			return internalPopulate(population);
		};

		function internalRecount(){
			var req = new APICaller("notification", "fetchAllUser");
			var params = {uid: "SESSION"};
			
			$notificationNum = $('#notification_number');
			// adds hover class to begin animation
			$notificationNum.addClass("hover");

			req.send(params, function(result){

				// sets new number
				total = result.length
				$notificationNum.html(total);

				// adds/removes dropdown ability
				if(total > 0)
					$notificationEl.addClass("has-dropdown");
				else
					$notificationEl.removeClass("has-dropdown");

				// saves
				notificationIds = result;
				
				internalPopulate(5);

				// waits 1/4 of a second to complete animation
				setTimeout(function(){$notificationNum.removeClass("hover");}, 250);
			});
		}

		function internalPopulate(population){
			var req = new APICaller("notification", "load");

			var $nfDropdown = $("#notification-dropdown");
			$nfDropdown.html("");
			console.log($nfDropdown.html());
			for(var i = 0;i < displayed + population;i++){
				if(typeof notificationIds[i] == "undefined")
					continue;

				console.log(notificationIds[i]);
				var params = {id: notificationIds[i]};
				req.send(params, function(data){
					$nfDropdown.append(createTopbarItem(data.url, data.message));
				});
			}
		}

		construct();
	}

	construct();
 }