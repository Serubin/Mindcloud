/******************************************************************************
 * dashboard.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for dashboard page
 *****************************************************************************/

function dashboard() {

	// initial load
	refreshProblems();

	// Login form submission, validation done by Foundation form-abide
	$('#submit-problem').on('valid', function() {
		var req = new APICaller('problem', 'create');
		var params = {statement: $("#form_problem_statement").val(), description:$("#form_problem_desc").val()};
		req.send(params, function(result) {
				if (result) {
					$("#register_modal").foundation('reveal', 'close');
					refreshProblems();

				}
			});
	});

	function refreshProblems() {
		// initial load
		var req = new APICaller("dashboard", "load");
		req.send({}, function(result) {
			$("#container").empty();
			$.each(result, function(i, value) {
				$("#container").append("<li class='curated-content'><div id=" + value[0] + " ><p>" + value[1] + "</p></div></li>");
			});

			// reload wookmark content curator

			var wookmark = new Wookmark('#container', {
				// Prepare layout options.
				autoResize: true, // This will auto-update the layout when the browser window is resized.
				container: $('#container'), // Optional, used for some extra CSS styling
				offset: 5, // Optional, the distance between grid items
				outerOffset: 10, // Optional, the distance to the containers border
				itemWidth: 210 // Optional, the width of a grid item
			});

			// Update the layout.
        	wookmark.layout(true);

			// Capture clicks on grid items.
			/*$('#container li').on('click', 'li', function () {
				// Randomize the height of the clicked item.
				var newHeight = $('img', this).height() + Math.round(Math.random() * 300 + 30);
				$(this).css('height', newHeight+'px');
				// Update the layout.
				wookmark.layout(true);
			});*/
		});
	}


    //$(document).foundation();
}