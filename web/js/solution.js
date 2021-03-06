/******************************************************************************
 * solution.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
function solution(url) {
	// id var for access from all functions
	var solution_id;

	// get the problem's numeric id
	var req = new APICaller('solution', 'getId');
	var params = {
		shorthand: url[1].toLowerCase()
	};
	req.send(params, function(result) {
		// populate the problem data
		var req = new APICaller('solution', 'load');
		var params = {
			id: result
		};
		req.send(params, onDataLoad);
	});

	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

	function onDataLoad(result) {

		if (result) {
			// set id
			solution_id = result.id;

			updateCreateSolution(result.problem_id, result.problem.title); // Updates create solution modal

			$(".vote").voter("solution", solution_id);
			
			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("/dashboard");
			alertHandler("alert", "Sorry, we couldn't find that solution.");
		}
	}

	// new thread listener
	$("#submit_thread").submit(function(event) {

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
	function populatePage(data) {
		// show create solution
		$("#create_solution").css("display", "");
		$("#back_to_dashboard").css("display", "");

		// set subject
		window.document.title = "Solution: " + data.title;
		$("#banner #title").html(data.title);

		$("#solves_for").html(data.problem.title).attr("href", "/problem/" + data.problem.shorthand);
		ph.captureLink($("#solves_for"));

		if (data.can_edit) {
			$("<a></a>", {
				href: "/edit/solution/" + data.shorthand,
				class: "right",
				id: "edit-link"
			}) // creates initial link
			.html($("<i></i>", {
				class: "fi-pencil"
			})) // adds icon
			.append("edit") // adds text
			.insertBefore("#description"); // adds before description

			ph.captureLink($("#edit-link")); //link listener
		}

		// set description
		$("#description").html(wiky.process(data.description, {}));

		// set contributors
		$("#contributors").html("");
		$.each(data.contributors, function(key, value) {
			$("#contributors").append("<li><small>" + value.association + "</small> " + value.user.first_name + " " + value.user.last_name + "</li>");
		});


		// set vote count and vote status if set

		if (data.current_user_vote == -1) { // downvote 
			$(".downvote-btn").addClass("selected-vote");
		} else if (data.current_user_vote == 1) { //upvote
			$(".upvote-btn").addClass("selected-vote");
		}

		// set score
		$("#score").html(data.score);

		// set popuate related projects
		$("#related-solutions").relatedProjects(data.related_solutions);

		// add threads and posts
		$("#discussions_container").clearAll();
		$("#discussions_container").addThreadThumbnails(data.threads);

	}

	function getScore() {
		var req = new APICaller("solution", "score");
		var params = {
			id: id
		};
		req.send(params, function(result) {
			$("#score").html(result);
		});
	}
}

function presolution(url) {
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if (!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});

	if ($.isNumeric(url[1])) {
		var req = new APICaller('solution', 'getShorthand');
		var params = {
			id: url[1]
		};
		req.send(params, function(result) {
			log.debug("result of getShorthand: " + result, "solution");
			if (!result)
				ph.pageRequest("/dashboard");

			ph.pageRequest("/solution/" + result);
		});
		// fetch shorthand from id
	}
}