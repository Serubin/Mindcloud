/******************************************************************************
 * problem.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/

function problem(url){

	
	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

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

	function onDataLoad (result) {

		if (result) {

			updateCreateSolution(result.id, result.title); // Updates create solution modal

			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("/dashboard");
			alertHandler("alert", "Sorry, we couldn't find that problem.");
		}
	}
	//TODO take a look, modify
	// voting 
	$(document).on("click", ".vote", function (event) {

		var $btn = $(this);
		//var $parent = $(this).parents(".problem");
		var oppositeVote = ($btn.hasClass("upvote-btn")) ? ".downvote-btn" : ".upvote-btn";

		// only submit the vote if the user has not voted already
		if (!$btn.hasClass("selected-vote")) {
			var req = new APICaller("problem", "vote");
			req.send({
				vote: $(this).attr("data-value"),
				problem_id: problem_id
			}, function (result) {
				if (result) {
					$btn.addClass("selected-vote");
					
					// deselect the opposite vote button
					$(oppositeVote).removeClass("selected-vote");

					// update the vote total
					$("#score").html(result);

				}
				else {
					console.log("vote submit failed");
					alertHandler("alert", "Failed to submit vote");
				}
			});
		}
	});

	// new thread listener
	$("#submit_thread").submit( function (event) {

		//log.debug("submitting thread to", problem_id);

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
			console.log(value);
			$("#contributors").append("<li><small>" + value.association + "</small> " + value.first_name + " " +  value.last_name + "</li>");
		});

		// set vote count and vote status if set
		// downvote 
		if(data.current_user_vote == -1) {
			$(".downvote-btn").addClass("selected-vote");
		//upvote
		} else if(data.current_user_vote  == 1) { 
			$(".upvote-btn").addClass("selected-vote");
		}
		
		// set score
		$("#score").html(data.score);

		// set popuate related projects
		var $related_projects = $("#related-solutions");
		if(data.related_solutions.length == 0)
			$related_projects.html("<h4>No related solutions... yet!</h4>");

		$.each(data.related_solutions, function(key, value){
			var $project_preview = $("<div></div>").addClass("solution-preview").attr("data-url", value.shorthand);
			var $title = $("<h4></h4>").html(value.title);
			var $content = $("<p></p>").html(value.description.substr(0,50));

			$project_preview.append($title).append($content);
			
			$project_preview.click(function(){
				ph.pageRequest("/solution/" + $(this).attr("data-url"));
			})

			$related_projects.append($project_preview);
		});

		// add threads and posts
		$("#discussions_container").clearAll();
		$("#discussions_container").addThreadThumbnails(data.threads);
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
