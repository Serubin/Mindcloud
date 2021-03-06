/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for topbar
 *****************************************************************************/
// App Global
var notificationTopbar;

function topBar() {
	var _this = this;

	// public functions
	var load;
	var reload;
	var repopulate;
	var notificationElement;

	// vars
	var navLoader;
	var $navLinks;

	function construct() {
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
	this.load = function() {
		navLoader.pageRequest("topbar", false);
		window["topbar"] = function() {
			$navLinks = $("#right-topbar");
			checkUser();
			log.info("Topbar", "Topbar loaded");
		}
	}

	/* reload()
	 * recalculates links
	 */
	this.reload = function() {
		checkUser();
		log.info("Topbar", "Topbar reloaded");
	}

	/* checkUser()
	 * Checks if user is logged in and creates links based on login status
	 */
	function checkUser() {
		var req = new APICaller("user", "check");
		req.send({}, function(result) {
			if (!result) {
				log.debug("Topbar", "User not logged in creating default links");
				defaultLinks($navLinks);
				return;
			}
			log.debug("Topbar", "User logged in creating user links");
			userLinks(result, $navLinks);
		});
	}

	/* defaultLinks()
	 * Creates links for not logged in or default
	 * @param $links - right-topbar ul element
	 */
	function defaultLinks($links) {
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
	function userLinks(uid, $links) {
		var req = new APICaller("user", "load");
		var params = {
			uid: "SESSION"
		};
		req.send(params, function(result) {

			// Sets home link to dashboard
			$("#home-link").attr("href", "/dashboard");
			$links.html(""); // clears current link

			// dashboard
			var $dashboard = createTopbarItem("/dashboard", "dashboard");
			$dashboard.attr("id", "back_to_dashboard").css("display", "none");
			$links.append($dashboard);

			// problem
			var $problem = createTopbarItem("#", "pose a problem");
			$problem.attr("data-reveal-id", "pose_problem_modal")
			$links.append($problem); //TODO attach/create global pose problem context

			// solution
			var $solution = createTopbarItem("#", "create a solution");
			$solution.attr("data-reveal-id", "create_solution_modal")
			$solution.attr("id", "create_solution").css("display", "none");
			$links.append($solution); //TODO attach/create global pose solution context

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
			$dropdown.append(createTopbarItem("/user/settings", "<i class=\"fi-widget right\"></i><span>settings</span>"));
			$dropdown.append(createTopbarItem("/legal", "<i class=\"fi-torso-business right\"></i><span>legal</span>"));
			$dropdown.append(createTopbarItem("/help", "<i class=\"fi-alert right\"></i><span>help</span>"));
			$dropdown.append(createTopbarItem("/problem/feedback", "<i class=\"fi-results-demographics right\"></i><span>feedback</span>"));
			$dropdown.append(createTopbarItem("/faq", "FAQ"));
			$dropdown.append(createTopbarItem("/user/logout", "<i class=\"fi-arrow-right right\"></i></span>log out</span>"));
			$dropdownWrapper.append($dropdown);

			// Adds bar to page and allows foundation to do it's magic.
			$links.append($dropdownWrapper);
			$(document).foundation('topbar', 'reflow');
			$(document).foundation('reveal', 'reflow');


			setTimeout(function() {
				$(".back h5").css("display", "none", "!important");
			}, 1000); // Fixes magic

			notificationTopbar.recount();
		});
	}

	/* createTopbarItem()
	 * Creates basic link li element
	 * @param link - url for link, shouldn't be html
	 * @param text - text for link, can be html
	 */
	function createTopbarItem(link, text) {
		var $li = $("<li class=''></li>");
		var $a = $("<a></a>").attr("href", link);

		$a.append(text);
		$li.append($a);

		return $li;
	}

	/* notificationElement()
	 * handles notification menu and counter
	 */
	function notificationElement() {
		//TODO move to it's own class. Isolate from foundation and topbar
		var __this = this;

		// data
		var total = 0;
		var notificationIds;
		var notificationData;

		var displayed = 0;

		// Dom
		var $notificationEl; // topbar item
		var $notificationNum; // number item

		// public functions
		var getElement;
		var recount;
		var open;

		function construct() {
			// create topbar item
			$notificationEl = createTopbarItem("javascript:void(0);", "0");
			// adds id to number element
			$notificationEl.children().attr("id", "notification_number")

			var $dropdown = $("<ul><ul>").addClass("dropdown").attr("id", "notification-dropdown");
			$notificationEl.append($dropdown);
		}

		/* getelement()
		 * returns topbar item element
		 */
		this.getElement = function() {
			return $notificationEl;
		}

		/* recount()
		 * recounts notifications and saves all ids
		 * handles animation
		 */
		this.recount = function() {
			return internalRecount();
		}

		/** repopulate()
		 * repopulates notifications
		 */
		this.repopulate = function(population) {
			return internalPopulate(population);
		};

		function internalRecount() {
			var req = new APICaller("notification", "fetchAllId");
			var params = {
				read: 0,
				uid: "SESSION"
			};

			$notificationNum = $('#notification_number');
			// adds hover class to begin animation
			$notificationNum.addClass("hover");

			req.send(params, function(result) {

				// sets new number
				total = result.length
				$notificationNum.html(total);

				// adds/removes dropdown ability
				//if(total > 0)
				$notificationEl.addClass("has-dropdown");
				//else
				//$notificationEl.removeClass("has-dropdown");

				// saves
				notificationIds = result;

				internalPopulate(25);

				// waits 1/4 of a second to complete animation
				setTimeout(function() {
					$notificationNum.removeClass("hover");
				}, 250);
			});
		}

		function internalPopulate(population) {
			var req = new APICaller("notification", "loadArray");
			var params = {
				ids: JSON.stringify(notificationIds),
				read: 0
			};

			var $nfDropdown = $("#notification-dropdown");

			$nfDropdown.html("");
			req.send(params, function(data) {

				// Handles no notifications
				if (data.length == 0) {
					var $message = $("<p></p>").html("No Notifications");
					$nfDropdown.append(createTopbarItem("#", $message).addClass("text-center"));
				}

				// Populates notification dropdown
				for (var i = 0; i < displayed + population; i++) {

					if (typeof data[i] == "undefined")
						continue;

					var dashFind = new RegExp("-", 'g'); // Replaces dashes (firefox fix)
					var time = data[i].time.replace(dashFind, "/"); // firefox fix

					// Populates notification item
					var $date = $("<small></small>").addClass("text-right time").html(new Date(time).toLocaleString());
					var $message = $("<p></p>").html(data[i].message).append($date);
					$nfDropdown.append(createTopbarItem("/user/notification/" + data[i].id, $message));
				}

				displayed += population;
				showMore();
			});

			function showMore() {
				displayed = population;
				if (displayed < total) {
					var $more = $("<li></li>").html("<a class='text-center'>Show more</a>");
					$more.hover(function() {
						notificationTopbar.repopulate(displayed + 20);
					});
					$nfDropdown.append($more);
				}
			}
		}

		construct();
	}

	construct();
}