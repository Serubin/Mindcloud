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
	};

	// Ajax call
	$.ajax({
		url: "pages/" + page + ".html",
		success: success
	});

}