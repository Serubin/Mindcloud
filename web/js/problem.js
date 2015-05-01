/******************************************************************************
 * problem.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/

function problem(url) {


	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

	// id var for access from all functions
	// get the problem's numeric id
	var req = new APICaller('problem', 'getId');
	var params = {
		shorthand: url[1]
	};
	req.send(params, function(result) {
		// populate the problem data
		var req = new APICaller('problem', 'load');
		var params = {
			id: result
		};
		req.send(params, onDataLoad);
	});

	function onDataLoad(result) {

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

	// voting 
	$(".vote").voter("problem", problem_id);



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

		// set subject
		window.document.title = "Problem: " + data.title;
		$("#banner #title").html(data.title);

		// set description
		$("#description").html(wiky.process(data.description, {}));

		// set contributors
		$("#contributors").html("");
		$.each(data.contributors, function(key, value) {
			$("#contributors").append("<li><small>" + value.association + "</small> " + value.user.first_name + " " + value.user.last_name + "</li>");
		});

		if (data.can_edit) {
			$("<a></a>", {
				href: "/edit/problem/" + data.shorthand,
				class: "right",
				id: "edit-link"
			}) // creates initial link
			.html($("<i></i>", {
				class: "fi-pencil"
			})) // adds icon
			.append("edit") // adds text
			.insertBefore("#description"); // adds before description

			ph.captureLink($("#edit-link")); // link listener
		}

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
		var req = new APICaller("problem", "score");
		var params = {
			id: problem_id
		};
		req.send(params, function(result) {
			$("#score").html(result);
		})
	}
}

function preproblem(url) {
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if (!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});

	if ($.isNumeric(url[1])) {
		var req = new APICaller('problem', 'getShorthand');
		var params = {
			id: url[1]
		};
		req.send(params, function(result) {
			log.debug("result of getShorthand: " + result, "problem");
			if (!result)
				ph.pageRequest("/dashboard");

			ph.pageRequest("/problem/" + result);
		});
		// fetch shorthand from id
	}
}