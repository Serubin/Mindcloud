/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

var ph; // page handler global variable
var tp;

var problem_id; // globals for create solutions
var problem_title; 


$(function(){
	log.info("Mindcloud", "Loading mindcloud");
	tp = new topBar();

	// Loads page handler
	ph = new pageHandler({"pageLoc": "/pages/", "animations": true});

	// if index page
	if(ph.parseUrl()[0] == ""){
		log.debug("App", "No start page, redirecting");
		ph.pageRequest("/login");
		return;
	}

	// loads page
	ph.pageRequest( ph.parseUrl(), false );

	var req = new APICaller("user", "check");
	req.send({}, function(result){
		if(result) {
			connectNotifications();
			initPoseProblem();
			initCreateSolution();
		}
	});

	$(document).foundation({
		topbar : {
    		mobile_show_parent_link: false,
    		is_hover: false
  		}
	});

	function connectNotifications(){
		log.debug("Notification Listener", "Starting!")
		var req = new APICaller("user", "loadConfidential");
		req.send({}, function(user){
			log.debug("Notification Listener", "Started!");
			var socket = io('http://mindcloud.loc:8000', {
		        transports: ['websocket'],
		        reconnection: false
		    });

		    socket.on(user.notification_hash, function (data) {
		    	var $notificationHTML = $("<a></a>");
		    	$notificationHTML.attr("href", data.url);
		    	$notificationHTML.html("<p>" + data.message + "</p>");

		        new alertHandler("info", $notificationHTML);

		        notificationTopbar.recount();
		    });

		    socket.on("connect_error", function(data){
		    	new alertHandler("alert", "Could not fetch realtime notifications. This is common if you are using an older browser, please update your browser.")
		    });
		});
	}

	function initPoseProblem(){

		// request current list of categories
		var req = new APICaller("problem", "getcategories");
		req.send({}, function (result) {

			// if the result is not false or null
			// should contain categories
			if (result) {
				// display categories
				$.each(result, function (i, value) {
					$("#form_problem_cat").append("<option value='" + value[0] + "'>" + value[1] + "</option");
				});

			} else {
				// report failure
				alertHandler("alert", "Failed to load categories for problem creation");
			}
		});


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
					$('#tag_container').setId(tag, result);
				});
			}
		});
		
		// Problem creation submission listener
		$('#submit_problem').on('valid', function() {
			$("#tag_container").getAllTags();
			var req = new APICaller('problem', 'create');
			var params = {
				uid: "SESSION",
				title: $("#form_problem_statement").val(), 
				description:$("#form_problem_desc").val(), 
				tags: $("#tag_container").getAllTags(),
				category: $("#form_problem_cat").val()
			};

			if($("#form_problem_shorthand").val()) {
				params["shorthand"] = $("#form_problem_shorthand").val();
			}
			
			req.send(params, function(result) {
					if (result) {
						$("#pose_problem_modal").foundation('reveal', 'close');
						$("#submit_problem").trigger("reset");
						ph.pageRequest("/problem/" + result);
						$("#tag_container").clearTags();
					}
				});
		}).on('invalid', function() {
			//problem_tags.getAllTags();
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

	function initCreateSolution(){

		// Problem creation submission listener
		$('#submit_solution').on('valid', function() {
			var req = new APICaller('solution', 'create');
			var params = {
				problem_id: problem_id,
				title: $("#form_solution_statement").val(), 
				description:$("#form_solution_desc").val(),
			};

			if($("#form_solution_shorthand").val()) {
				params["shorthand"] = $("#form_solution_shorthand").val();
			}
			req.send(params, function(result) {
					if (result) {
						$("#create_solution_modal").foundation('reveal', 'close');
						$("#submit_solution").trigger("reset");
						ph.pageRequest("/solution/" + result);
					}
				});
		}).on('invalid', function() {
			//problem_tags.getAllTags();
		});
		
		$(document).foundation('reflow');
	}
});

/* updateCreateSolution()
 * updates global problem id and title as well as updating the html in the modal
 * for creating a solutions
 * @param id - problem id, once set available globally at problem_id
 * @param id - problem title, once set available globally at problem_title
 */
function updateCreateSolution(id, title){
	problem_id = id;
	problem_title = title;

	$("#create_solution_for").html(title);
}

function page_handler_global(){
	$("#create_solution").css("display", "none", "!important");
	updateCreateSolution(undefined, "");
}