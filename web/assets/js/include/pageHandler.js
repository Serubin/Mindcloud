/******************************************************************************
 * pageHandler.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for async page handling
 *****************************************************************************/

/**
 * pageRequest()
 * Page requests dynamicly loads in a new content
 * @param page - the pages url (excluding pages/)
 */
function pageRequest(page){

	/*
	 * success()
	 * Processes received html data
	 */
	var success = function(result) {
		var $content = $("#content");

		$content.fadeOut(500, function(){
			$content.html(result); // Changes content
			if(typeof window[page] != "undefined")
				window[page](); // calls loader for page

			$(document).foundation(); // Updates foundation stuff
			// registers all a links to use js for redirection
			$("a").unbind("click");
			$("a").click(function(){
				return linkHandler( $(this).attr("href") );
			});
			$content.fadeIn();
		});
	};

	// Ajax call
	$.ajax({
		url: "pages/" + page + ".php",
		success: success
	});

}

/**
 * linkHandler()
 * handles a tag clicking. Pushes history state and loads new page dynamicly
 * @param link - the href url
 * @returns false - to cancel default action
 */
function linkHandler(link){
	history.pushState({}, '', link);

	pageRequest(parseGet());

    return false;
}
/**
 * popHandler()
 * Handles state movement, doesn't set history
 * @param e - event
 */
function popHandler(e) {
	if (e.originalEvent.state !== null) {
		var params = parseGet(location.href);
		pageRequest(params);
	}
}