/******************************************************************************
 * discussion plugin
 * @author Michael Shullick
 *****************************************************************************/

(function($) {

	var forms = '<form id="submit_thread">' +
		'<p>stir the pot.</p>' +
		'<input type="text" id="new_thread_subject" class="thread-subject" maxlength="200" placeholder="What would you like to say?" required/>' +
		'<textarea type="text" id="new_thread_body" class="thread-desc" style="height:100px" placeholder="Elaborate on that?" required></textarea>' +
		'<button id="submit_thread_btn" class="button btn-login">create</button>' +
		'</form>';

	/*
	 * options must include:
	 * problem: problem_id
	 */
	$.fn.Discussion = function(options) {
		//console.log("running Discussion setup");
		var settings = $.extend({

		}, options);

		// display mode for toggle
		var mode = "button";

		// id of thread container
		var id = $(this).selector;

		var ids = $.fn.getIds(id);

		// create initial button instance
		var $button = $('<div></div>', {
			id: ids.toggle,
			class: 'text-center thread-btn'
		}).html('<i class="fi-plus icon">');

		// create forms
		var $forms = $('<div></div>', {
			id: ids.forms,
			class: 'thread-forms'
		}).html(forms);

		// create div for threads
		var $thread_container = $('<div></div>', {
			id: ids.thread_container,
			class: 'threads-container'
		});

		// create viewer for threads
		var $thread_viewer = $('<div></div>', {
			id: ids.thread_viewer,
			class: 'thread-viewer row'
		});

		// holds individual posts
		var $posts = $('<div></div', {
			class: "posts small-11 small-centered medium-8 columns"
		});

		// setup the create new thread prompt
		// thread_toggle is the entire div that grows to contain forms, or stays small to contain a button div
		var $thread_toggle = $button;
		$thread_toggle.click(function() {

			// show all threads
			if (mode === "button") {

				// remove all discussions
				// TODO

				$(this).parent().showForms();
				mode = "forms";

			} else {

				$(this).parent().hideForms();
				mode = "button";
			}

		});

		// initialize thread viewer
		$thread_viewer.append($posts);

		// add each element to the parent
		$(id).append($forms);
		$(id).append($thread_toggle);
		$(id).append($thread_container);
		$(id).append($thread_viewer);

		// append the loading gif
		$thread_viewer.append($.fn.Discussion.loadingFormatter());

		// create listener for showing threads' posts
		// TODO load posts
		$(id).on("click", ".thread-preview", function(event) {

			// handle on the thread id
			var thread_id = $(this).attr('data-title');

			// clear all past content
			$posts.empty();

			// initialize the list of posts
			$posts_list = $("<ul></ul>", {
				class: "posts-list small-block-grid-1",
				'data-title': thread_id
			});

			// append the initial block grid list
			$posts.append($posts_list);

			// request all problems of this thread
			var req = new APICaller("thread", "load");

			req.send({
				thread_id: thread_id
			}, function(result) {

				if (result) {

					$.each(result, function(i, value) {
						$posts_list.prepend($.fn.Discussion.postFormatter(value.user, value.body, value.id, value.date));
					});

				} else {
					alertHandler("alert", "Failed to load thread");
				}


			});

			// load all posts of this thread
			/*$("#" + ids.thread_viewer).animate({
			}, "fast");*/

			// append form for new post
			$posts_list.append($.fn.Discussion.postFormFormatter());

			// reflow for foundation DOM
			$(document).foundation("reflow");
		});

		// post submission listener
		$(id).on("valid", '.submit-post-form', function(event) {

			// handle on parent list of posts
			$posts_list = $(this).parents(".posts-list");

			// request parameter array
			var params = {
				'post_body': $(this).find("textarea").val(),
				'thread_id': $posts_list.attr('data-title')
			};

			var req = new APICaller("post", "create");
			req.send(params, function(result) {

				if (result) {

					// add the post to the list
					$(".post-form").before($.fn.Discussion.postFormatter(result.user_name, result.body, result.id, result.created));

					// clear the post field
					$posts_list.find("input").val("");

					// reflow for the new DOM object
					$(document).foundation('reflow');

				} else {
					alertHandler("alert", "Failed submit post");
				}

			});

		});

		// new thread listener
		$(id).on('submit', "#submit_thread", function(event) {

			//log.debug("submitting thread to", problem_id);

			// prevent default submission
			event.preventDefault();

			var subject = $("#new_thread_subject").val();
			var body = $("#new_thread_body").val();

			// hide forms
			$("#discussions_container_toggle").click();

			// add the thread
			$discussions.createThread(problem_id, subject, body);

		});
	};

	$.fn.loadThreads = function(problem_id) {

		// handle on discussion conatiner id
		var id = $(this).selector;

		// prepare request
		var req = new APICaller("thread", "load");
	}

	/**
	 * faster way to get handle on ids
	 */
	$.fn.getIds = function(id) {

		// take out the hash if it's there
		if (id.substring(0, 1) === "#") {
			id = id.substring(1, id.length);
		}

		var ids = {
			self: id,
			toggle: id + "_toggle", // button for showing forms or threads
			forms: id + "_forms", // threads for submitting a new form
			thread_container: id + "_thread_container",
			thread: id + "_thread_", // existing threads, append id of thread to end
			thread_viewer: id + "_thread_viewer"
		};

		return ids;
	}

	/*
	 * show the thread submission forms
	 */
	$.fn.showForms = function() {

		var ids = $.fn.getIds($(this).attr('id'));

		var forms_width = $(document).width() - $('#' + ids.toggle).width();

		$('#' + ids.forms).css("display", "block");

		$('#' + ids.forms).animate({
			//width: forms_width + "px"
			width: "95%"
		}, "fast");

		$('#' + ids.toggle).html('<i class="fi-minus icon">');
	}

	/*
	 * hide thread submission forms
	 */
	$.fn.hideForms = function() {

		var ids = $.fn.getIds($(this).attr('id'));

		$('#' + ids.forms).animate({
				width: '0%'
			}, "fast",
			function() {
				$('#' + ids.forms).css("display", "none");
			});

		$('#' + ids.toggle).html('<i class="fi-plus icon">');

	}

	$.fn.loadThreadPosts = function() {

	}

	/*
	 * Adding a new thread
	 */
	$.fn.createThread = function(problem_id, title, body) {

		var ids = $.fn.getIds($(this).selector);

		var req = new APICaller("thread", "create");
		var params = {
			problem_id: problem_id,
			subject: title,
			body: body
		};
		req.send(params, function(result) {
			if (result) {

				// create an array with just this new thread to append

				var new_threads = [result];

				$.fn.addThreadThumbnails(new_threads, ids);
			} else
				alertHandler("alert", "<p>Failed to submit thread</p>");
		});
	};

	/**
	 * empty
	 * called clearAll instead of empty() because the JQuery method will get called and fail
	 */
	$.fn.clearAll = function() {

		var ids = $.fn.getIds($(this).selector);
		var $thread_container = $("#" + ids.thread_container);
		$thread_container.children().remove();

	}

	/*
	 * Append a preview to the end of the previews list
	 * takes an array of thread objects containing at least an id, title, and body
	 */
	$.fn.addThreadThumbnails = function(threads, ids) {


		if (!ids) ids = $.fn.getIds($(this).selector);
		$(".placeholder").remove();

		if (threads.length == 0) {
			console.log("no threads to display");
			$.fn.Discussion.showEmpty(ids);
		} else {

			$.each(threads, function(i, value) {

				$("#" + ids.thread_container).prepend($.fn.Discussion.threadPrevFormatter(ids, value.id, value.subject, value.first_post.body));

			});
		}
	}

	/*
	 * Add a post to a thread
	 */
	$.fn.addPost = function(threadid, body) {

		var req = new APICaller("post", "create");
		var params = {
			'thread_id': threadid,
			'body': body
		};
		req.send(params, function(result) {
			if (result) {
				$("#thread_" + threadid).append($.fn.Discussion.postDivFormatter(body));
			} else
				alertHandler("alert", "<p>Failed to submit post</p>");
		})
	}

	/**
	 * Add a message say there are no threads to append
	 */
	$.fn.Discussion.showEmpty = function(ids) {

		var $thread_container = $("#" + ids.thread_container);

		$thread_container.append($("<div></div>", {
			class: "placeholder"
		}).html("<span>No threads to display</span>"));
	}

	/*
	 * format a thread element for loading
	 */
	$.fn.Discussion.loadingFormatter = function() {

		var result = $('<div></div>', {
			class: "placeholder loading"
		}).html('<img src="/assets/images/ajax-loader.gif">');
		return result;
	}

	/*
	 * format a post for appending
	 */
	$.fn.Discussion.postDivFormatter = function(body) {
		return "<div class='post'>" +
			"<p class='post-body'>" + body + "</p>" +
			"</div>";
	}

	/*
	 * returns a div for the preview of a thread
	 */
	$.fn.Discussion.threadPrevFormatter = function(ids, id, subject, body) {

		return $("<div></div>", {
			'data-title': id,
			class: "thread-preview"
		})
			.append($("<h4></h4>").html(subject))
			.append($("<p></p>").text(body));
	}

	/*
	 * returns a div containing the forms for an enw post
	 */
	$.fn.Discussion.postFormFormatter = function() {
		return $("<li></li>", {
			class: "post-form"
		}).append(
			$("<div></div>", {
				class: "discussion-child"
			})
			.append($("<form></form>", {
					class: "submit-post-form",
					'data-abide': 'ajax'
				})
				.append($('<div></div>', {
						class: "post-text-field"
					})
					.append($("<textarea></textarea>", {
						placeholder: "Write a post...",
						required: ""
					}))
				)
				.append($("<button></button>", {
					class: "button keep-native",
					type: "submit"
				}).text("submit"))
			)
		);
	}

	/**
	 * postFormatter - creates an element out of a post data
	 */
	$.fn.Discussion.postFormatter = function(user, body, id, date) {
		// list item
		var top = $("<li></li>", {
			class: ''
		});

		// column container
		var container = $("<div></div>", {
			class: "post"
		});

		// post body div
		var post_body = $("<div></div>", {
			class: ""
		}).html(body);
		var poster = $("<div></div>", {
			class: "poster-id"
		}).html("<span>posted by " + user + " on " + date);
		post_body.append(poster);

		// put it all together
		top.append(container.append(post_body));

		return top;
	}

})(jQuery);