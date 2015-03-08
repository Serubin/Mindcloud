/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for account page
 *****************************************************************************/

function dashboard() {

	// initial load
	refreshProblems();

	// Login form submission, validation done by Foundation form-abide

	$('#submit_problem').on('valid', function() {
		console.log("submitting");
		var req = new APICaller('problem', 'create');
		var params = {
			statement: $("#form_problem_statement").val(), 
			description:$("#form_problem_desc").val(), 
			tags: $("#tag_container").val().split(',')
		};
		req.send(params, function(result) {
				if (result) {
					$("#register_modal").foundation('reveal', 'close');
					refreshProblems();

				}
			});
	}).on('invalid', function() {
		console.log("invalid");
	});

	function refreshProblems() {
		// initial load
		var req = new APICaller("dashboard", "load");
		req.send({}, function(result) {
			$("#container").empty();
			$.each(result, function(i, value) {
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
	$(document).foundation('reflow');

	/** tag handler **/
	$('#tag_container').tagsInput({
		'onAddTag': function(tag){
			var tag_check_request = new APICaller("tag", "check");
			tag_check_request.send({
				identifer: tag
			}, function (result) {
				console.log(result);
			});

			$(document).foundation('reflow');
		},
		'onRemoveTag' : function(tag) {

		}
	});

	// autocomplete


}