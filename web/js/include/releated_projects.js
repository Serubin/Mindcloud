/******************************************************************************
 * releated_solutions plugin
 * @author Solomon Rubin
 *
 *
 *****************************************************************************/

(function($) {

	$.fn.relatedProjects = function(solutions){
		var $el = this;
		$el.html(""); // Clears div

		if(solutions.length == 0)
			$el.html("<h4 id='no-display'>No related solutions... yet!</h4>");

		$.each(solutions, function(key, value){
			var $project_preview = $("<div></div>").addClass("solution-preview").attr("data-url", value.shorthand);
			var $title = $("<h4></h4>").html(value.title);
			var $content = $("<p></p>").html(wiky.process(value.description.substr(0,50), {strip: true}));

			$project_preview.append($title).append($content);

			$project_preview.click(function(){
				ph.pageRequest("/solution/" + $(this).attr("data-url"));
			})

			$el.append($project_preview);
		});
	}
})(jQuery);