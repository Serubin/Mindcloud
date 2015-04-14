/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function dashboard() {

	// handle on content container
	var $problems = $("#problems");

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

		$problems.gridalicious({
			animate: true,
			animationOptions: {
				queue: true,
				speed: 200,
				duration: 300,
				effect: 'fadeInOnAppear',
				complete: onComplete
				}
		});

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

	function onComplete() {

		$(document).foundation('reflow');
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
			new_problems[i] = $('<div></div>', {id: value[0], datetime: value[2], class: 'problem'}).append(
				// row div 
				$('<div></div>', {class: 'row'})
				.append(
					// vote button containers
					$('<div></div>', {class: 'small-2 column voter'})
						.append( $("<div></div>", {class:'problem-btn vote', 'data-value' : '1'}).html("<i class='fi-arrow-up'>"))
						.append( $("<div></div>", {class:'problem-btn flag'}).html("<i class='fi-flag'></i></div>"))
						.append( $("<div></div>", {class:'problem-btn vote', 'data-value' : '-1'}).html("<i class='fi-arrow-down'></i></div>"))
					)
				.append(
					// description, etc. container
					$('<div></div>', {class: 'small-9 column problem-statement'})
						.append( $('<span></span>', {class: 'text-left'}).text(value[1]))
				)

				/** SOLOMON LOOK HERE **/
				.append( $('<div></div>', {class: 'small-1 column problem-btn'}).html(


							'<button href="#" data-dropdown="drop1" aria-controls="drop1" aria-expanded="false" class="button dropdown"></button><br>' +
							'<ul id="drop1" data-dropdown-content class="f-dropdown" aria-hidden="true">' +
								'<li><a href="#">This is a link</a></li>' +
								'<li><a href="#">This is another</a></li>' +
								'<li><a href="#">Yet another</a></li>' +
							'</ul>'


							))
			);
		});

		$problems.gridalicious('append', new_problems);
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
	$(document).on("click", ".vote", function (event) {
		console.log("voting for " + $(this).parent().parent().parent().attr('id') + " ...");
		var req = new APICaller("problem", "vote");
		req.send({
			vote: $(this).attr("data-value"),
			problem_id: $(this).parent().parent().parent().attr('id')
		}, function (result) {
			if (result) {
				$(this).css("background-color", "black");
			}
			else {
				alert("failed");
			}
		});	
	});
}

function predashboard(url){
	var _this = this;
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result){
		if(!result){ 
			$.xhrPool.abortAll();
			ph.pageRequest("/login");
		}
	});
}