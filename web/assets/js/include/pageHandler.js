/**
 * pageRequest()
 * Page requests dynamicly loads in a new content
 * @param page - the pages url (excluding pages/)
 *
 * 
 */
function pageRequest(page){

	/*
	 * success()
	 * Processes received html data
	 */
	var success = function(result) {

		$("#content").html(result); // Changes content
		if(typeof window[page] != "undefined")
			window[page](); // calls loader for page

		$(document).foundation(); // Updates foundation stuff

		// registers all a links to use js for redirection
		$("a").unbind("click");
		$("a").click(function(){
			return linkHandler( $(this).attr("href") );
		});
	};

	// Ajax call
	$.ajax({
		url: "pages/" + page + ".html",
		success: success
	});

}

function linkHandler(link){
	history.pushState({}, '', link);

	pageRequest(parseGet());

    return false;
}

function popHandler(e) {
	if (e.originalEvent.state !== null) {
		var params = parseGet(location.href);
		pageRequest(params);
	}
}