/******************************************************************************
 * project plugin
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

	$.fn.voter = function(project_type, project_id){
		this.click(function(event) {

			var $btn = $(this);
			var oppositeVote = ($btn.hasClass("upvote-btn")) ? ".downvote-btn" : ".upvote-btn";

			// only submit the vote if the user has not voted already
			if (!$btn.hasClass("selected-vote")) {

				// creates request with params
				var req = new APICaller(project_type, "vote");
				var params = {
					id: problem_id,
					vote: $(this).attr("data-value")
				};

				// sends request
				req.send(params, function (result) {
					if (result) {
						$btn.addClass("selected-vote");
						
						// deselect the opposite vote button
						$(oppositeVote).removeClass("selected-vote");

						// update the vote total
						$("#score").html(result);

					} else {
						console.log("vote submit failed");
						new alertHandler("alert", "There was an error submitting your vote<br /> we'll get this fixed soon.");
					}
				});
			}
		});
	}
})(jQuery);