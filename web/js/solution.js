/******************************************************************************
 * solution.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
function solution(url){
	// id var for access from all functions
	var id;

	// get the problem's numeric id
	var req = new APICaller('solution', 'getId');
	var params = {shorthand: url[1].toLowerCase()};
	req.send(params, function(result){
		// populate the problem data
		var req = new APICaller('solution', 'load');
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

			updateCreateSolution(result.problem_id, result.problem.title); // Updates create solution modal

			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("/dashboard");
			alertHandler("alert", "Sorry, we couldn't find that solution.");
		}
	}

	// downvote listener
	$("#solution_downvotes").click(function(){
		var req = new APICaller("solution", "vote");
		var params = {pid: id, vote: -1};
		req.send(params, function(){
			log.debug("Problem", "User down voted solution " + id);
			$("#solution_upvotes").removeClass("selected-vote");
			$("#solution_downvotes").addClass("selected-vote");
			getScore();
		});
	});
	
	// upvote listener
	$("#solution_upvotes").click(function(){
		var req = new APICaller("solution", "vote");
		var params = {pid: id, vote: 1};
		req.send(params, function(){
			log.debug("Problem", "User up voted problem " + id);
			$("#solution_downvotes").removeClass("selected-vote");
			$("#solution_upvotes").addClass("selected-vote");
			getScore();
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

/**
 * populatePage()
 * Dynamicaly adds in data to page
 */
	function populatePage(data){
		// show create solution
		$("#create_solution").css("display", "");

		// set subject
		window.document.title = "Solution: " + data.title;
		$("#banner #title").html(data.title);

		$("#solves_for").html(data.problem.title).attr("href", "/problem/" + data.problem.shorthand);


		// set description
		$("#description").html(wiky.process(data.description, {}));

		// set contributors
		$("#contributors").html("");
		$.each(data.contributors, function(key, value){
			$("#contributors").append("<li><small>" + value.association + "</small> " + value.user.first_name + " " +  value.user.last_name + "</li>");
		});


		// set vote count and vote status if set
		if(data.current_user_vote < 0) {
			$("#solution_downvotes").addClass("selected-vote");
		} else if(data.current_user_vote > 0) { 
			$("#solution_upvotes").addClass("selected-vote");
		}

		// set score
		$("#score").html(data.score);
		// set popuate related projects

		var $related_projects = $("#related-solutions");
		if(data.related_solutions.length == 0)
			$related_projects.html("<h4>No related solutions... yet!</h4>");

		$.each(data.related_solutions, function(key, value){
			var $project_preview = $("<div></div>").addClass("solution-preview");
			var $title = $("<h4></h4>").html(value.title);
			var $content = $("<p></p>").html(value.description.substr(0,50));

			$project_preview.append($title).append($content);
			$related_projects.append($project_preview);
		});

		// add threads and posts
		$.each(data.threads, function(i, value) {
			$("#discussions_container").loadThread(value);
		});

	}

	function getScore() {
		var req = new APICaller("solution", "score");
		var params = {id: id};
		req.send(params, function(result){
			$("#score").html(result);
		});
	}
}

function presolution(url){
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if(!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});

	if($.isNumeric(url[1])) {
		var req = new APICaller('solution', 'getShorthand');
		var params = {id:url[1]};
		req.send(params, function(result) {
			log.debug("result of getShorthand: " + result, "solution");
			if(!result)
				ph.pageRequest("/dashboard");

			ph.pageRequest("/solution/" + result);
		});
		// fetch shorthand from id
	}
}