/******************************************************************************
 * problem.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
function problem(url){
	// id var for access from all functions
	var id;

	// get the problem's numeric id
	var req = new APICaller('problem', 'getId');
	var params = {shorthand: url[1]};
	req.send(params, function(result){
		// populate the problem data
		var req = new APICaller('problem', 'load');
		var params = {id: result};
		req.send(params, onDataLoad);
	});
	
	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

	function onDataLoad (result) {

		if (result) {
			// set id
			id = result.id;

			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("/dashboard");
			alertHandler("alert", "Sorry, we couldn't find that problem.");
		}
	}

	// downvote listener
	$("#problem_downvotes").click(function(){
		var req = new APICaller("problem", "vote");
		var params = {pid: id, vote: -1};
		req.send(params, function(){
			log.debug("Problem", "User down voted problem " + id);
			$("#problem_upvotes").removeClass("problem_vote_hover");
			$("#problem_downvotes").addClass("problem_vote_hover");
		});
	});
	
	// upvote listener
	$("#problem_upvotes").click(function(){
		var req = new APICaller("problem", "vote");
		var params = {pid: id, vote: 1};
		req.send(params, function(){
			log.debug("Problem", "User up voted problem " + id);
			$("#problem_downvotes").removeClass("problem_vote_hover");
			$("#problem_upvotes").addClass("problem_vote_hover");
		});
	})
	// new thread listener
	$("#submit_thread").submit( function (event) {

		log.debug("submitting thread", problem);

		// prevent default submission
		event.preventDefault();

		var subject = $("#new_thread_subject").val();
		var body = $("#new_thread_body").val();

		// hide forms
		$("#discussions_container_toggle").click();

		// add the thread
		$discussions.createThread(id, subject, body);

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

function preproblem(url){
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if(!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});


	if($.isNumeric(url[1])) {
		console.log(url[1])
		var req = new APICaller('problem', 'getShorthand');
		var params = {id:url[1]};
		req.send(params, function(result) {
			log.debug("result of getShorthand: " + result, "problem");
			if(!result)
				ph.pageRequest("/dashboard");

			ph.pageRequest("/problem/" + result);
		});
		// fetch shorthand from id
	}
}

/**
 * populatePage()
 * Dynamicaly adds in data to page
 */
function populatePage(data){
	// show create solution
	$("#create_solution").css("display", "");

	// set subject
	window.document.title = "Problem: " + data.title;
	$("#banner #title").html(data.title);

	// set description
	$("#description").html(data.description);

	// set contributors
	$("#contributors").html("");
	$("#contributers").append("<li><small>" + data.creator.association + "</small> " + data.creator.user.first_name + " " +  data.creator.user.last_name + "</li>")

	// set vote count and vote status if set
	log.debug("problem", data.current_user_vote);
	if(data.current_user_vote < 0) {
		$("#problem_downvotes").addClass("problem_vote_hover");
	} else if(data.current_user_vote > 0) { 
		$("#problem_upvotes").addClass("problem_vote_hover");
	}

	// set popuate related projects


	


	// add threads and posts
	$.each(data.threads, function(i, value) {
		$("#discussions_container").loadThread(value);
	});
}