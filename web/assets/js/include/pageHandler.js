/******************************************************************************
 * pageHandler.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for async page handling
 *****************************************************************************/
/**
 * pageHandler()
 * Page requests dynamicly loads in a new content
 * 
 * use .keep-native to avoid pageHandler from eating up links
 * @param args{
 *	pageLoc: 			page fragment locations - required	
 *	registerEvents: 	boolean to register events - default true
 *	contentDiv:  		content div to load frags into - default "#content"
 *	animations: 		enable animations - defualt false
 * }
 */
function pageHandler(args) {

	var _this = this;
	var history = window.history;

	// public functions
	var pageRequest;
	var parseUrl;

	// class options
	var pageLoc;
	var registerEvents = true;
	var contentDiv = "#content";
	var animations = false;


	function construct() {

		// Throws errors for required arguments
		if(typeof args.pageLoc == "undefined")
			throw "Argument undefined: page location (pageLoc)";

		// Sets options
		if(typeof args.registerEvents != "undefined")
			registerEvents = args.registerEvents;
		if(typeof args.contentDiv != "undefined")
			contentDiv = args.contentDiv;
		if(typeof args.animations != "undefined")
			animations = args.animations;
		pageLoc = args.pageLoc;

		// registers popstate event
		if(registerEvents) {
			$(window).on("popstate", popHandler);
		}
	}

	this.pageRequest = function(page, historypush){
		console.log(historypush);
		if(historypush || typeof historypush == "undefined")
			history.pushState({}, '', page);		
		pageLoad(page);
	}
	/**
	 * pageLoad()
	 * Page requests dynamicly loads in a new content
	 * @param page - the pages url (excluding pages/)
	 */
	function pageLoad(page) {

	 	var $content = $(contentDiv);			
		/*
		 * success()
		 * Handles pre-process (animate vs no animate)
		 * @param result - results of ajax
		 */
		function success(result){
			if(!animations)
				return process(result);

			$content.fadeOut(500, function() {
				process(result);
				$content.fadeIn();
			});
		}

		/*
		 * process()
		 * Processes received html data
		 * @param result - results of ajax
		 */
		function process(result) {
			$content.html(result); // Changes content
			if(typeof window[page] != "undefined")
				window[page](); // calls loader for page

			$(document).foundation(); // Updates foundation stuff
			// registers all a links to use js for redirection
			if(registerEvents) {
				$("a").unbind("click");
				$("a").not(".keep-native").click(function() {
					return linkHandler( $(this).attr("href") );
				});
			}
		};

		// Ajax call
		$.ajax({
			url: pageLoc + page + ".php",
			success: success
		});

	}

	/**
	 * parseGet()
	 * Parse hash bang parameters from a URL as key value object.
	 * #!x&y=3 -> { x:null, y:3 }
	 * 
	 * @param aURL URL to parse or null if window.location is used
	 * @return Object of key -> value mappings.
	 */
	this.parseUrl = function(aURL) {
	 
		aURL = aURL || window.location.href;
		
		var vars = {};
		// CHANGE BEFORE MOVING TO PROD
		var sHashes = aURL.slice(aURL.indexOf('web/') + 4).split("/");
		
		var hashes = [];
		for (var i = 0; i < sHashes.length; i += 2) {
			if(typeof sHashes[i+1] != "undefined")
				hashes.push(sHashes[i] + "/" + sHashes[i+1]);
			else
				hashes.push(sHashes[i])
		}
		for(var i = 0; i < hashes.length; i++) {
			var hash = hashes[i].split('/');
			if(hash.length > 1) {
				vars[hash[0]] = hash[1];
			} else {
				vars[hash[0]] = null;
			}			
		}
		
		// Returns single string if only one element
		if(Object.size(vars) == 1)
			return Object.keys(vars)[0];

		return vars;
	}

	/**
	 * linkHandler()
	 * handles a tag clicking. Pushes history state and loads new page dynamicly
	 * @param link - the href url
	 * @returns false - to cancel default action
	 */
	function linkHandler(link) {
		_this.pageRequest(link);

	    return false;
	}
	/**
	 * popHandler()
	 * Handles state movement, doesn't set history
	 * @param e - event
	 */
	function popHandler(e) {
		if (e.originalEvent.state !== null) {
			var params = _this.parseUrl(location.href);
			_this.pageRequest(params);
		}
	}

	construct();
}