/******************************************************************************
 * solution.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for problem pages
 *****************************************************************************/
function solution(url){
	// id var for access from all functions
	var id;

	// get the problem's numeric id
	var req = new APICaller('solution', 'getId');
	var params = {shorthand: url[1]};
	req.send(params, function(result){
		// populate the problem data
		var req = new APICaller('solution', 'load');
		var params = {id: result};
		req.send(params, onDataLoad);
	});
	
	// initialize discussion container
	$discussions = $("#discussions_container");
	$discussions.Discussion();

	function onDataLoad (result) {

		if (result) {
			// set id
			id = result.id;

			// Loads problem
			populatePage(result);
		}
		// if the problem wasn't found, redirect to dashboard
		else {
			ph.pageRequest("/dashboard");
			alertHandler("alert", "Sorry, we couldn't find that solution.");
		}
	}

	// downvote listener
	$("#solution_downvotes").click(function(){
		var req = new APICaller("solution", "vote");
		var params = {pid: id, vote: -1};
		req.send(params, function(){
			log.debug("Problem", "User down voted solution " + id);
			$("#solution_upvotes").removeClass("project_vote_hover");
			$("#solution_downvotes").addClass("project_vote_hover");
			getScore();
		});
	});
	
	// upvote listener
	$("#solution_upvotes").click(function(){
		var req = new APICaller("solution", "vote");
		var params = {pid: id, vote: 1};
		req.send(params, function(){
			log.debug("Problem", "User up voted problem " + id);
			$("#solution_downvotes").removeClass("project_vote_hover");
			$("#solution_upvotes").addClass("project_vote_hover");
			getScore();
		});
	})
	// new thread listener
	$("#submit_thread").submit( function (event) {

		log.debug("submitting thread", problem);

		// prevent default submission
		event.preventDefault();

		var subject = $("#new_thread_subject").val();
		var body = $("#new_thread_body").val();

		// hide forms
		$("#discussions_container_toggle").click();

		// add the thread
		$discussions.createThread(id, subject, body);

	});

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
	function populatePage(data){
		console.log("DATA YO.");
		console.log(data);
		// show create solution
		$("#create_solution").css("display", "");

		// set subject
		window.document.title = "Solution: " + data.title;
		$("#banner #title").html(data.title);

		// set description
		$("#description").html(wiky.process(data.description, {}));

		// set contributors
		$("#contributors").html("");
		$.each(data.contributors, function(key, value){
			$("#contributers").append("<li><small>" + value.association + "</small> " + value.user.first_name + " " +  value.user.last_name + "</li>");
		});


		// set vote count and vote status if set
		if(data.current_user_vote < 0) {
			$("#solution_downvotes").addClass("project_vote_hover");
		} else if(data.current_user_vote > 0) { 
			$("#solution_upvotes").addClass("project_vote_hover");
		}

		// set score
		$("#score").html(data.score);
		// set popuate related projects

		// add threads and posts
		$.each(data.threads, function(i, value) {
			$("#discussions_container").loadThread(value);
		});
	}

	function getScore() {
		var req = new APICaller("solution", "score");
		var params = {id: id};
		req.send(params, function(result){
			$("#score").html(result);
		})
	}
}

function presolution(url){
	// Checks for user login
	var req = new APICaller('user', 'check');
	req.send({}, function(result) {
		if(!result) {
			ph.pageRequest("/login");
			alertHandler("alert", "Please log in.");
		}
	});


	if($.isNumeric(url[1])) {
		console.log(url[1])
		var req = new APICaller('solution', 'getShorthand');
		var params = {id:url[1]};
		req.send(params, function(result) {
			log.debug("result of getShorthand: " + result, "problem");
			if(!result)
				ph.pageRequest("/dashboard");

			ph.pageRequest("/solution/" + result);
		});
		// fetch shorthand from id
	}
}