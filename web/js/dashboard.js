/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function dashboard() {

	window.document.title = "Mindcloud: Dashboard"

	// handle on content container
	var $problems = $("#problems");

	// initial load
	loadDashboard();

	/**
	 * Sets up the isotope container and loads inital content
	 */
	function loadDashboard() {

		// TODO: This stuff will be useful for sorting problems
		// filter items when filter link is clicked
		/*$('#filters a').click(function(){
		  var selector = $(this).attr('data-filter');
		  $container.isotope({ filter: selector });
		  return false;
		});
			
		// set selected menu items
		var $optionSets = $('.inline-list'),
		$optionLinks = $optionSets.find('a');

		$optionLinks.click(function(){
			var $this = $(this);
		    // don't proceed if already selected
		    if ( $this.hasClass('selected') ) {
		        return false;
		    }
		   var $optionSet = $this.parents('.inline-list');
		   $optionSet.find('.selected').removeClass('selected');
		   $this.addClass('selected'); 
		});*/

		// initial load
		var req = new APICaller("dashboard", "load");

		// load and display problem
		// TODO: include which of problems and solutions to refresh
		req.send({}, 
			// loader callback
			function(result) {

				// display categories
				$.each(result.categories, function (i, value) {
					$("#form_problem_cat").append("<option value='" + value[0] + "'>" + value[1] + "</option");
				});

				// display problems from request
				repopulateProblems(result.problems);

				// add votes
				$.each(result.votes, function (i, value) {

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
							alertHandler("alert", "Received an invalid vote");
							break;
					}

					$problem = $("#" + value[0] + ".problem");
					$problem.addClass("voted");
					$problem.find(voteClass).addClass("selected");


				});

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

			// overall container
			new_problems[i] = $('<li></li>', {id: value[0], datetime: value[2], class: 'problem', 'data-title' : value[3]}).append(
				// row div 
				$('<div></div>', {class: 'row'})
				.append(
					// vote button containers
					$('<div></div>', {class: 'small-2 column voter'})
						.append( $("<div></div>", {class:'problem-btn vote upvote', 'data-value' : '1'}).html("<i class='fi-arrow-up'>"))
						.append( $("<div></div>", {class:'problem-btn flag-reveal'}).html("<i class='fi-flag'></i></div>")
							.append( $("<div></div>", {class: "dropdown"})
								.append( $("<ul></ul>", { tabindex : "-1", role: "menu", 'aria-hidden': "true"})
									.append($("<li></li>").html('<a data-value="1" class="flag-val" href="#">duplicate</a>'))
									.append($("<li></li>").html('<a data-value="2" class="flag-val" href="#">innapropriate</a>'))
									//.append($("<li></li>").html('<a class="flag-stupid" href="#">stupid</a>'))
									)
							)
						)
						.append( $("<div></div>", {class:'problem-btn vote downvote', 'data-value' : '-1'}).html("<i class='fi-arrow-down'></i></div>"))
					)
				.append(
					// description, etc. container
					$('<div></div>', {class: 'small-9 column problem-statement'})
						.append( $('<span></span>', {class: 'text-left'}).text(value[1]))
				)
			);

			$problems.append(new_problems[i]);

		});
	}
}

function predashboard(url){
	var _this = this;
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result){
		if(!result){
			ph.pageRequest("/login");
		}
	});

}