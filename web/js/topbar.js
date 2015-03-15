/******************************************************************************
 * topbar.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/
 function topBar(){
 	var _this = this;

 	var navLoader;
 	var $navLinks;

 	this.reload;

 	function construct(){
	 	// Loads navigation bar
		navLoader = new pageHandler({
			"pageLoc": "/pages/", 
			"registerEvents": false, 
			"contentDiv": "#navigation"
		});
	}

	this.load = function(){
		navLoader.pageRequest("topbar", false);
		window["topbar"] = function(){
			$navLinks = $("#right-topbar");
			checkUser();
			console.log("loaded");
		}
	}

	this.reload = function(){
		console.log("wil");

	}

	function checkUser(){
		var req = new APICaller("user", "getCurrent");
		req.send({}, function(result){
			console.log(result);
			if(result < 1){
				console.log("false");
				defaultLinks($navLinks);
				return;
			}
			console.log("true?");
			userLinks(result, $navLinks);
		});
	}

	function defaultLinks($links){
		$("#home-link").attr("href", "/login"); // sets home link to login

		$links.html(""); // clears links area
		$links.append(createTopbarItem("/login", "log in"));
		$links.append(createTopbarItem("/register", "sign up"));
		$links.append(createTopbarItem("/about", "what is this?"));
	}

	function userLinks(uid, $links){
		var req = new APICaller("user", "load");
		var params = {"uid": uid};
		req.send(params, function(result){
			// Sets home link to dashboard
			$("#home-link").attr("href", "/dashboard");

			$links.html(""); // clears current link
			$links.append(createTopbarItem("#","pose a problem"));
			$links.append(createTopbarItem("#","pose a solution"));
			// formats name
			result.first_name = result.first_name.toLowerCase();
			result.last_name = result.last_name.toLowerCase()
			// Creates dropdown
			$dropdownWrapper = createTopbarItem("users/" + result.first_name + "-" + result.last_name, result.first_name + " " + result.last_name);
			$dropdownWrapper.addClass("has-dropdown"); // foundation dropdown class
			// List for actual dropdown
			$dropdown = $("<ul class='dropdown'><ul>");
			$dropdown.append(createTopbarItem("/user/settings", "account settings"));
			$dropdown.append(createTopbarItem("/user/logout", "log out"));
			$dropdownWrapper.append($dropdown);

			// Adds bar to page and allows foundation to do it's magic.
			$links.append($dropdownWrapper);
			$(document).foundation('topbar', 'reflow');
			$(".parent-link").css("display", "none", "important"); // Fixes magic
		});
	}

	function createTopbarItem(link, text){
		var $li = $("<li class=''></li>");
		var $a = $("<a></a>");

		$a.attr("href", link);
		$a.append(text);

		$li.append($a);

		return $li;
	}
	construct();
 }