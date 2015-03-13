/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function dashboard() {

	// initial load
	loadDashboard();

	/** tag handler **/
	var problem_tags = $('#tag_container').tagsInput({

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

	// Problem creation submission
	$('#submit_problem').on('valid', function() {
		$("#tag_container").getAllTags();
		var req = new APICaller('problem', 'create');
		var params = {
			title: $("#form_problem_statement").val(), 
			description:$("#form_problem_desc").val(), 
			tags: $("#tag_container").getAllTags()
		};
		req.send(params, function(result) {
				if (result) {
					$("#register_modal").foundation('reveal', 'close');
					refreshProblems();

				}
			});
	}).on('invalid', function() {
		//problem_tags.getAllTags();
	});

	function loadDashboard() {
		// initial load
		var req = new APICaller("dashboard", "load");

		// load and display problem
		req.send({}, 
			// loader callback
			function(result) {
				$("#container").empty();
				$.each(result.problems, function(i, value) {
					$("#container").append(
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
						"</div>");
				});

				var $container = $('#container');

				// initialize isotope
				$container.isotope({

				  itemSelector : '.isotope-item',
				  layoutMode : 'masonry',
				  masonry: {
				  	columnWidth: 50
				  }
				  // options...
				});

				// filter items when filter link is clicked
				/*$('#filters a').click(function(){
				  var selector = $(this).attr('data-filter');
				  $container.isotope({ filter: selector });
				  return false;
				});
				*/	
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
				}); 

				// display categories
				$.each(result.categories, function (i, value) {
					$("#form_problem_cat").append("<option value='" + value[0] + "'>" + value[1] + "</option");
				});

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