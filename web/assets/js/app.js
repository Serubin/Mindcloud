$(function(){

	// loads page
	pageRequest( parseGet() );

	// registers popstate event
	$(window).on("popstate", popHandler);
})