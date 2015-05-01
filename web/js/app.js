/******************************************************************************
 * app.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/

// Configureation
//
var mindcloud_protocol = "http"
var mindcloud_url = "mindcloud";
var mindcloud_ext = "loc";
var mindcloud_full_url = mindcloud_protocol + "://" + mindcloud_url + "." + mindcloud_ext;

var ph; // page handler global variable
var tp;

var problem_id; // globals for create solutions
var problem_title;

var last_modal;


$(function() {
	log.info("Mindcloud", "Loading mindcloud");
	tp = new topBar();

	// Loads page handler
	ph = new pageHandler({
		"pageLoc": "/pages/",
		"animations": true
	});

	// if index page
	if (ph.parseUrl()[0] == "") {

		//log.debug("App", "No start page, redirecting");
		// redirect to landing intro page
		ph.pageRequest("/about");
		return;
	}

	// loads page
	ph.pageRequest(ph.parseUrl(), false);

	var req = new APICaller("user", "check");
	req.send({}, function(result) {
		if (result) {
			connectNotifications();
			initPoseProblem();
			initCreateSolution();
		}
	});

	$(document).foundation({
		topbar: {
			mobile_show_parent_link: false,
			is_hover: false
		}
	});

	function connectNotifications() {
		log.debug("Notification Listener", "Starting!")
		var req = new APICaller("user", "loadConfidential");
		req.send({}, function(user) {
			log.debug("Notification Listener", "Started!");
			var socket = io(mindcloud_full_url + ':8000', {
				transports: ['websocket'],
				reconnection: false
			});

			socket.on(user.notification_hash, function(data) {
				var $notificationHTML = $("<a></a>");
				$notificationHTML.attr("href", data.url);
				$notificationHTML.html("<p>" + data.message + "</p>");

				new alertHandler("info", $notificationHTML);

				notificationTopbar.recount();
			});

			socket.on("connect_error", function(data) {
				new alertHandler("alert", "Could not fetch realtime notifications. This is common if you are using an older browser, please update your browser.")
			});
		});
	}

	function initPoseProblem() {

		var error = false;

		$(document).on('opened.fndtn.reveal', '#pose_problem_modal', function () {
			last_modal = "pose_problem_modal";
		});

		fetchCategories(); // fetches categories

		// regex replacements for shorthand
		$("#form_problem_shorthand").on("keyup", function() {
			var val = $(this).val();
			val = val.replace(/ /g, "-"); // remove spaces
			val = val.replace(/[,!@#$%^&*()=\[\]{};:\'\"<>.,\/?\\~`]+/g, ""); // nasty characters
			$(this).val(val);

			// checks if shorthand is avalible
			var req = new APICaller("problem", "validateShorthand");
			var params = {
				shorthand: val
			};

			req.send(params, function(result) {
				if (!result) {
					$(".shorthand-field").addClass("error");
					error = true;
				} else {
					$(".shorthand-field").removeClass("error");
					error = false;
				}
			});
		});

		// preview listener
		$("#problem-preview-button").click(function() {
			$("#problem-text-preview").html(wiky.process($("#form_problem_desc").val(), {}));
		});


		// initalize tag handler
		$('#tag_container').tagsInput({

			// New tag callback
			'onAddTag': function(tag) {
				// request the tag id
				var tag_check_request = new APICaller("tag", "identify");
				tag_check_request.send({
					identifier: tag
				}, function(result) {
					// set the retrieved id as the element id of the tag
					$('#tag_container').setId(tag, result);
				});
			}
		});

		// Problem creation submission listener
		$('#submit_problem').on('valid', function() {
			if (error) // return if shorthand is taken
				return;
			$("#tag_container").getAllTags();
			var req = new APICaller('problem', 'create');
			var params = {
				uid: "SESSION",
				title: $("#form_problem_statement").val(),
				description: $("#form_problem_desc").val(),
				tags: $("#tag_container").getAllTags(),
				category: $("#form_problem_cat").val()
			};

			// checks for short hand
			if ($("#form_problem_shorthand").val()) {
				params["shorthand"] = $("#form_problem_shorthand").val();
			}

			// Submiting
			var loadingAlert = new alertHandler("info", "Submitting problem"); // alert for info
			req.send(params, function(result) {
				loadingAlert.close();
				if (result) {
					$("#submit_problem").trigger("reset");
					ph.pageRequest("/problem/" + result);
					$("#tag_container").clearTags();
				} else {
					new alertHandler("alert", "There was an error submitting your problem.");
				}
			});
			$("#pose_problem_modal").foundation('reveal', 'close');
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

	function initCreateSolution() {

		var error = false;

		$(document).on('opened.fndtn.reveal', '#create_solution_modal', function () {
			last_modal = "create_solution_modal";
		});

		// regex replacements for shorthand
		$("#form_solution_shorthand").on("keyup", function() {
			var val = $(this).val();
			val = val.replace(/ /g, "-"); // remove spaces
			val = val.replace(/[,!@#$%^&*()=\[\]{};:\'\"<>.,\/?\\~`]+/g, ""); // nasty characters
			$(this).val(val);

			var req = new APICaller("solution", "validateShorthand");
			var params = {
				shorthand: val
			};

			req.send(params, function(result) {
				if (!result) {
					$(".shorthand-field").addClass("error");
					error = true;
				} else {
					$(".shorthand-field").removeClass("error");
					error = false;
				}
			});
		});

		// preview listener
		$("#solution-preview-button").click(function() {
			$("#solution-text-preview").html(wiky.process($("#form_solution_desc").val(), {}));
		});


		// Problem creation submission listener
		$('#submit_solution').on('valid', function() {
			if (error) // return if shorthand is taken
				return;

			var req = new APICaller('solution', 'create');
			var params = {
				problem_id: problem_id,
				title: $("#form_solution_statement").val(),
				description: $("#form_solution_desc").val(),
			};

			if ($("#form_solution_shorthand").val()) {
				params["shorthand"] = $("#form_solution_shorthand").val();
			}
			var loadingAlert = new alertHandler("info", "Submitting solution");
			req.send(params, function(result) {
				loadingAlert.close();
				if (result) {
					$("#submit_solution").trigger("reset");
					ph.pageRequest("/solution/" + result);
				} else {
					new alertHandler("alert", "There was an error submitting your solution.");
				}
			});
			$("#create_solution_modal").foundation('reveal', 'close');
		}).on('invalid', function() {
			//problem_tags.getAllTags();
		});

		$(document).foundation('reflow');
	}

	// help modals
	$(document).on('closed.fndtn.reveal', '#wiki_mark_modal', function () {
		if(typeof last_modal != "undefined")
  			$('#' + last_modal).foundation('reveal', 'open');
	});
});

function fetchCategories(){

	log.debug("Problem", "Fetching categories");

	// request current list of categories
	var req = new APICaller("problem", "getcategories");
	req.send({}, function(result) {

		// if the result is not false or null
		// should contain categories
		if (result) {
			// display categories
			$.each(result, function(i, value) {
				$("#form_problem_cat").append("<option value='" + value[0] + "'>" + value[1] + "</option");
			});

		} else {
			// report failure
			new alertHandler("alert", "Failed to load categories for problem creation");
		}
	});
}

/* updateCreateSolution()
 * updates global problem id and title as well as updating the html in the modal
 * for creating a solutions
 * @param id - problem id, once set available globally at problem_id
 * @param id - problem title, once set available globally at problem_title
 */
function updateCreateSolution(id, title) {
	problem_id = id;
	problem_title = title;

	$("#create_solution_for").html(title);
}

function page_handler_global() {
	$("#create_solution").css("display", "none", "!important");
	updateCreateSolution(undefined, "");
}