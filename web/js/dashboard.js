/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

// preloader
function predashboard(url) {
	var _this = this;
	// Checks for user login
	var req = new APICaller('user', 'check');

	req.send({}, function(result) {
		if (!result) {
			// redirect to the landing page
			ph.pageRequest("/login");
		}
	});
}

// pagination of problems with reference to db
var page = 1;
var more = true; // assume there are more until we get a result less then max

function dashboard() {

	window.document.title = "Mindcloud: Dashboard";
	$("#back_to_dashboard").css("display", "none");

	// handle on content container
	var $problems = $("#problems");

	// initial load
	loadDashboard();

	// initialize keeping track of auto load
	page = 1;
	more = true;

	/**
	 * Sets up the isotope container and loads inital content
	 */
	function loadDashboard() {

		// remove all past listeners
		$("#content").off("click");

		// initial load
		var req = new APICaller("dashboard", "load");

		// load and display problem
		// TODO: include which of problems and solutions to refresh
		req.send({},
			// loader callback
			function(result) {

				// display categories
				$.each(result.categories, function(i, value) {
					$("#form_problem_cat").append("<option value='" + value[0] + "'>" + value[1] + "</option");
				});

				// display problems from request
				repopulateProblems(result.problems);
				more = result.more;

				// add votes
				$.each(result.votes, function(i, value) {

					// get what the vote is
					var voteClass;
					switch (value[1]) {
						case 1:
							voteClass = ".upvote";
							break;
						case -1:
							voteClass = ".downvote";
							break;
						default:
							new alertHandler("alert", "Received an invalid vote");
							break;
					}

					$problem = $("#" + value[0] + ".problem");
					$problem.addClass("voted");
					$problem.find(voteClass).addClass("selected");

				});

				// reload DOM
				$(document).foundation('reflow');

			});
	}

	function reloadProblems() {

		var req = new APICaller("refresh", "load");

		// TODO: include which of problems and solutions to refresh
		req.send({}, function(result) {
			repopulateProblems(problems);
		});

	}

	/**
	 * Takes an array of problems that have
	 * 1: id
	 * 2: title
	 * 3: date
	 * And populates the content container with them.
	 */
	function repopulateProblems(new_problems) {

		// clear old problems
		$problems.empty();

		// append new problems		
		$.each(new_problems, function(i, value) {

			new_problems[i] = problemFormatter(value);

			$problems.append(new_problems[i]);

		});
	}



	// Problem create form
	$(document).foundation({
		abide: {
			validators: {
				tagsValid: function(el, required, parent) {
					return el.value.split(",").length >= 5;
				}
			}
		}
	});

	/** 
	 * problem events
	 */

	// voting 
	$("#content").on("click", ".vote", function(event) {

		var $btn = $(this);
		var $parent = $(this).parents(".problem");
		var oppositeVote = ($btn.hasClass("upvote")) ? ".downvote" : ".upvote";

		// only submit the vote if the user has not voted already
		if (!$btn.hasClass("selected")) {
			var req = new APICaller("problem", "vote");
			req.send({
				id: $parent.attr('id'),
				vote: $(this).attr("data-value")
			}, function(result) {
				if (result) {
					$btn.addClass("selected");
					$parent.addClass("voted");

					// deselect the opposite vote button
					$parent.find(oppositeVote).removeClass("selected");

					// update the vote total
					$parent.find(".vote-counter > span").html(result);

				} else {
					console.log(" vote submit failed");
				}
			});
		}
	});

	// show flag menu
	$("#content").on("click", ".flag-reveal", function(event) {

		$menu = $(this).children(".dropdown");
		if (!$menu.hasClass('open')) {
			$("#content").append($("<div></div", {
				class: "overlay"
			}));
			$menu.addClass("open");
			$(this).addClass("selected");
		}
	});

	// hiding flag menu
	$("#content").on('click', ".overlay", function(event) {

		console.log("overlay clicked");
		$(".dropdown").removeClass('open');
		$(".dropdown").parent().removeClass("selected");
		$('.overlay').remove();
	});

	// Link to problem pages
	$("#content").on('click', '.problem-statement', function(event) {

		ph.pageRequest("/problem/" + $(this).parent().parent().attr('data-title'));
	});

	// flag actions
	$("#content").on('click', ".flag-val", function(event) {

		event.preventDefault();

		var problem_id = $(this).parents(".problem").attr('id');

		var req = new APICaller("problem", "flag");

		var params = {
			problem_id: problem_id,
			flag: $(this).attr('data-value')
		};

		req.send(params, function(result) {
			if (result) {
				$(".overlay").click();
				alertHandler("success", "This problem has been flagged for review.");
			} else {
				console.log("Create flag failed");
			}
		})
	});

}

// auto load on pagination
$(window).scroll(function() {

	// if the botom of the page is reached, request more problems
	if ($(window).scrollTop() + $(window).height() == $(document).height() && ph.currentPage == "dashboard" && more) {

		// prepare 
		var req = new APICaller("dashboard", "extend");
		var params = {
			"page": page
		};

		log.debug("Dashboard", "Loading page " + page);
		// send the request
		req.send(params, function(result) {

			if (result) {

					// cycle through the new problems and append them
					$.each(result.problems, function(i, value) {

						$("#problems").append(problemFormatter(value));

					});

					page++;
					$(document).foundation('reflow');

					more = result.more;

			} else {
				new alertHandler("alert", "Failed to load more problems");
			}

		});


	}
});

function problemFormatter(problem) {

	// overall container
	var new_problem = $('<li></li>', {
		id: problem['id'],
		datetime: problem['date'],
		class: 'problem',
		'data-title': problem['shorthand']
	}).append(

		// row div 
		$('<div></div>', {
			class: 'row'
		})
		.append(
			// vote button containers
			$('<div></div>', {
				class: 'small-2 column voter'
			})
			.append($("<div></div>", {
				class: 'problem-btn vote upvote',
				'data-value': '1'
			}).html("<i class='fi-arrow-up'>"))
			.append($("<div></div>", {
				class: 'vote-counter'
			}).html("<span>" + problem['votes'] + "</span>"))
			.append($("<div></div>", {
				class: 'problem-btn vote downvote',
				'data-value': '-1'
			}).html("<i class='fi-arrow-down'></i></div>"))
		)
		.append(
			// description, etc. container
			$('<div></div>', {
				class: 'small-9 column problem-statement'
			})
			.append($('<span></span>', {
				class: 'text-left'
			}).text(problem['title']))
			// flag button and menu
		).append(

			$('<div></div>', {
				class: 'small-1 column problem-btn flag-reveal'
			}).html("<i class='fi-flag'></i></div>")
			.append($("<div></div>", {
					class: "dropdown"
				})
				.append($("<ul></ul>", {
						tabindex: "-1",
						role: "menu",
						'aria-hidden': "true"
					})
					.append($("<li></li>").html('<a data-value="1" class="flag-val keep-native" href="#">duplicate</a>'))
					.append($("<li></li>").html('<a data-value="2" class="flag-val keep-native" href="#">innapropriate</a>'))
					//.append($("<li></li>").html('<a class="flag-stupid" href="#">stupid</a>'))
				)
			)
		)
	);

	return new_problem;

}