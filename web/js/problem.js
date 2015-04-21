/******************************************************************************
 * problem.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
 var problem_id;
 var problem_title;

function problem(url){
	// id var for access from all functions
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
			problem_id = result.id;
			problem_title = result.title;

			$("#create_solution_for").html(problem_title);

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
		var params = {pid: problem_id, vote: -1};
		req.send(params, function(){
			log.debug("Problem", "User down voted problem " + problem_id);
			$("#problem_upvotes").removeClass("project_vote_hover");
			$("#problem_downvotes").addClass("project_vote_hover");
			getScore();
		});
	});
	
	// upvote listener
	$("#problem_upvotes").click(function(){
		var req = new APICaller("problem", "vote");
		var params = {pid: problem_id, vote: 1};
		req.send(params, function(){
			log.debug("Problem", "User up voted problem " + problem_id);
			$("#problem_downvotes").removeClass("project_vote_hover");
			$("#problem_upvotes").addClass("project_vote_hover");
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
		$discussions.createThread(problem_id, subject, body);

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
		window.document.title = "Problem: " + data.title;
		$("#banner #title").html(data.title);

		// set description
		$("#description").html(wiky.process(data.description, {}));

		// set contributors
		$("#contributors").html("");
		$.each(data.contributors, function(key, value){
			$("#contributers").append("<li><small>" + value.association + "</small> " + value.user.first_name + " " +  value.user.last_name + "</li>");
		});

		// set vote count and vote status if set
		if(data.current_user_vote < 0) {
			$("#problem_downvotes").addClass("project_vote_hover");
		} else if(data.current_user_vote > 0) { 
			$("#problem_upvotes").addClass("project_vote_hover");
		}

		// set score
		$("#score").html(data.score);
		// set popuate related projects

		// add threads and posts
		$.each(data.threads, function(i, value) {
			$("#discussions_container").loadThread(value);
		});
	}

	function getScore() {
		var req = new APICaller("problem", "score");
		var params = {id: problem_id};
		req.send(params, function(result){
			$("#score").html(result);
		})
	}
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
