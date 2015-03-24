/******************************************************************************
 * problem.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
function problem(url){

	// id var for access from all functions
	var id;

	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

	// populate problem data
	var req = new APICaller('problem', 'load');
	var params = {identifier: url[1]};
	req.send(params, function (result) {

		if (result) {
			// set id
			id = result.id;

			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("dashboard");
			alertHandler("alert", "Sorry, we couldn't find that problem.");
		}
	});

	// new thread listener
	$("#submit_thread").on("valid", function () {
		console.log("Adding thread");

		var thread_title = $("#new_thread_title").val();
		var body = $("#new_thread_body").val();

		var req = new APICaller("thread", "create");
		var params = {
			problem_id: id,
			title: title,
			body: body
		}
		req.send(params, function (result) {
			if (result) {
				$discussions.addThread(id, title, body);
			} else {
				alertHandler("alert", "<p>Failed to create new thread</p>");
			}
		});
	});

	// TODO: post listener
	// Add listener for creating threads
	/*$("#new_post_field").keypress(function (event) {
		if (event.which == 13) {
			event.preventDefault();
			$discussions.addPost
		}
	})*/
}

function preproblem(){
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result){
		if(!result) {
			ph.pageRequest("login");
			alertHandler("alert", "Please log in.");
		}
	});
}

/**
 * populatePage()
 * Dynamicaly adds in data to page
 */
function populatePage(data){
	//console.log(data);
	// set title
	$("#banner #title").html(data.title);

	// set description
	$("#description").html(data.description);

	// set contributors
	$("#contributers").append("<li><small>" + data.creator.association + "</small> " + data.creator.user.first_name + " " +  data.creator.user.last_name + "</li>")

	// add threads and posts
}