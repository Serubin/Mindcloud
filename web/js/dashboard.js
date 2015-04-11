/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function dashboard() {

	// handle on content container
	var $problems = $('#container');

	// initial load
	loadDashboard();

	// initalize tag handler
	$('#tag_container').tagsInput({

		// New tag callback
		'onAddTag': function(tag){
			// request the tag id
			var tag_check_request = new APICaller("tag", "identify");
			tag_check_request.send({
				identifier: tag
			}, function (result) {
				// set the retrieved id as the element id of the tag
				console.log(result);
				$('#tag_container').setId(tag, result);
			});
		}
	});
	
	// Problem creation submission listener
	$('#submit_problem').on('valid', function() {
		$("#tag_container").getAllTags();
		var req = new APICaller('problem', 'create');
		var params = {
			title: $("#form_problem_statement").val(), 
			description:$("#form_problem_desc").val(), 
			tags: $("#tag_container").getAllTags(),
			category: $("#form_problem_cat").val()
		};
		req.send(params, function(result) {
				if (result) {
					$("#create_problem_modal").foundation('reveal', 'close');
					loadDashboard();

				}
			});
	}).on('invalid', function() {
		//problem_tags.getAllTags();
	});

	/**
	 * Sets up the isotope container and loads inital content
	 */
	function loadDashboard() {

		// initialize isotope
		/*$problems.isotope({

		  itemSelector : '.isotope-item',
		  layoutMode : 'masonry',
		  masonry: {
		  	columnWidth: 50
		  }
		  // options...
		});*/

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
			new_problems[i] = 
				"<div class='isotope-item' datetime='" + value[2] + "' id=" + value[0] + ">" + 
					"<div class='row'>" +
						"<div class='small-9 column problem-statement'>" +
							"<span text-left'>" + 
								value[1] + 
							"</span>" +
						"</div>" +
						"<div class='small-3 column voter'>" +
							"<div class='arrow'><i class='fi-arrow-up'></i></div>" +
							"<div class='arrow'><i class='fi-arrow-down'></i></div>" +
						"</div>" +
					"<div>" + // end row
				"</div>";
		});

		$problems.isotope('appended', new_problems);
	}


	/** TEMPORARY 
	 * test for discussion div
	 */
	 var problem_id = 0;

	 var $disc_container = $("#discussion_container");
	 $disc_container.Discussion();

	 // set up thread creator
	 $("#create_thread").click(function (event) {
	 	if ($("#thread_test_title").val().length > 0 && $("#thread_test_body").val().length > 0)
	 	$disc_container.addThread(problem_id, $("#thread_test_title").val(), $("#thread_test_body").val());
	 });
	 $("#create_post").click(function (event) {
	 	if ($("#post_test").val() > 0 ) {
	 		$disc_container.addPost(problem_id, $("#post_test").val());
	 	}
	 });


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
	$(document).foundation('reflow');
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