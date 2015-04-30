/**
 * js for the about page
 * removes the top bar
 */
function about() {

	$(".top-bar").css("display", "none");

	var thing = randImgSelect();
	console.log(thing);

	$("#splash-background").css("background-image", thing);

	$("#splash-background").css("height", "115%");
	$("#splash-background").css("margin-top", "-45px");

}