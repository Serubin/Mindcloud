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

	var pkg = "pageHandler"; // For logger

	// public functions
	var pageRequest;
	var parseUrl;
	var setPreloadStatus;

	// class options
	var pageLoc;
	var registerEvents = true;
	var contentDiv = "#content";
	var animations = false;

	var preloadStatus;


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
			log.debug(pkg, "Registering pop events");
			$(window).on("popstate", popHandler);
		}
	}

	this.pageRequest = function(page, historypush, callback){
		preloadStatus = undefined;

		if(typeof page == "string"){
			if(page.indexOf("/") == 0)
				page = page.slice(1,page.length);
			page = page.split("/");
		}

		if(historypush || typeof historypush == "undefined"){
			var joinedPage = page;
			if(typeof page == "object")
				joinedPage = page.join("/");

			history.pushState({}, '', "/" + joinedPage);
		}

		if(typeof page == "object")
			page = page[0];

		log.info("PageHandler", "Loading " + page);

		// Temporary global function
		if(typeof page_handler_global != "undefined")
			page_handler_global();

		pageLoad(page, callback);
	}
	/**
	 * pageLoad()
	 * Page requests dynamicly loads in a new content
	 * @param page - the pages url (excluding pages/)
	 */
	function pageLoad(page, callback) {
	 	var $content = $(contentDiv);


		function processError(xhr, ajaxOptions, thrownError){
			if(xhr.status==404) {
				_this.pageRequest("error-404", false);
			} else if(xhr.status==403) {
				_this.pageRequest("error-403", false);
			} else if(xhr.status==500) {
				_this.pageRequest("error-500", false);
			}
		}

		/*
		 * success()
		 * Handles pre-process (animate vs no animate)
		 * @param result - results of ajax
		 */
		function success(result){
			log.debug("PageHandler", "Loaded " + page);
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
			if(typeof window[page] != "undefined") {
				window[page](_this.parseUrl());
			}

			$(document).foundation('reflow'); // Updates foundation stuff

			if(registerEvents) {
				log.debug(pkg, "Registering link events");

				// Gets all a tags that don't have "keep-native" class
				$("a").not(".keep-native").each(function(){
					var $el = $(this);
					if(typeof $el.attr("href") == "undefined" ||
					   $el.attr("href").toLowerCase() == "javascript:void(0);" || 
					   $el.attr("href").toLowerCase() == "#")
							return;
					$el.unbind("click");
					$el.click(function() {
						return linkHandler( $(this).attr("href") );
					});
				})
			}
		};

		// Pre load script
		var page = page.replace("/", "");
		log.debug("Pagehandler", "Checking for pre" + page + "(): " + typeof window["pre" + page])
		if(typeof window["pre" + page] != "undefined"){
				preloadStatus = window["pre" + page](_this.parseUrl()); // calls loader for page
		}


		// Ajax call
		$.ajax({
			url: pageLoc + page + ".php",
			success: success,
			error: processError
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
		// Removes hash
		aURL = aURL.split("#");
		aURL = aURL[0];
		// Removes ?
		aURL = aURL.split("?");
		aURL = aURL[0];

		// remove prefix and suffix

		aURL = aURL.slice(aURL.indexOf('.loc') + 5)

		if(aURL.lastIndexOf("#") > 0)
			aURL = aURL.substr(0, aURL.lastIndexOf("#"));

		var vars = aURL.split("/");

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
		log.debug(pkg, "Handling pop change");
		var params = _this.parseUrl(location.href);
		_this.pageRequest(params);
	}

	this.setPreloadStatus = function(status){
		preloadStatus = status;
	}

	construct();
}