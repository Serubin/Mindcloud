/******************************************************************************
 * discussion plugin
 * @author Michael Shullick
 *****************************************************************************/

(function($) {

	var forms ='<form id="submit_thread">' +
						'<p>stir the pot.</p>' +
						'<input type="text" id="new_thread_subject" class="thread-subject" placeholder="What would you like to say?" required/>' +
						'<input type="text" id="new_thread_body" class="thread-desc" style="height:100px" placeholder="Elaborate on that?" required/>' +
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
		var $button = $('<div></div>', {id: ids.toggle, class: 'text-center thread-btn'}).html('<i class="fi-plus icon">');

		// create forms
		var $forms = $('<div></div>', {id: ids.forms, class: 'thread-forms'}).html(forms);

		// create div for threads
		var $thread_container = $('<div></div>', {id: ids.thread_container, class: 'threads-container'});

		// create viewer for threads
		var $thread_viewer = $('<div></div>', {id: ids.thread_viewer, class: 'thread-viewer'});

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

		// add each element to the parent
		$(id).append($forms);
		$(id).append($thread_toggle);
		$(id).append($thread_container);
		$(id).append($thread_viewer);

		// create listener for showing threads
		$(id).on("click", ".thread-preview", function() {

			$("#" + ids.thread_viewer).animate({
				height: "200px"
			}, "fast");
		});

	};

	$.fn.loadThreads = function (problem_id) {

		// handle on discussion conatiner id
		var id = $(this).selector;

		// prepare request
		var req = new APICaller("thread", "load");
	}

	/**
	 * faster way to get handle on ids
	 */
	$.fn.getIds = function (id) {

		// take out the hash if it's there
		if (id.substring(0,1) === "#") {
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

    	$('#' + ids.forms).animate({
	        width: forms_width + "px"
	    }, "fast");

    	$('#' + ids.toggle).html('<i class="fi-minus icon">');
	 }

	/*
	 * hide thread submission forms 
	 */
	$.fn.hideForms = function () {

	 	var ids = $.fn.getIds($(this).attr('id'));

    	$('#' + ids.forms).animate({
	        width: '0%'
	    }, "fast");

    	$('#' + ids.toggle).html('<i class="fi-plus icon">');

	}

	$.fn.loadThreadPosts = function () {
		
	}

	/*
	 * Adding a new thread
	 */
	$.fn.createThread = function(problem_id, title, body) {

	 	var ids = $.fn.getIds($(this).selector);

		var req = new APICaller("thread", "create");
		var params = {
			problem_id : problem_id,
			subject : title,
			body : body
		};
		req.send(params, function(result) {
			if (result) {

				// create an array with just this new thread to append
				var thread = {id: result['thread_id'], subject:title, body:body};

				var new_threads = [thread];

				$.fn.Discussion.addPreviews(ids, new_threads);
			}
			else
				alertHandler("alert", "<p>Failed to submit thread</p>");
		});
	};

	/*
	 * Given a thread id, load that thread
	 */
	$.fn.loadThread = function(thread_id) {

	 	var ids = $.fn.getIds($(this).selector);

	 	// add a thread container with a loading icon identified by the id
	 	var $new_thread = $.fn.Discussion.loadingFormatter(ids, thread_id);
	 	var $thread_container = $("#" + ids.thread_container);
	 	$thread_container.append($.fn.Discussion.loadingFormatter(ids, thread_id));
	 	//$thread_container.width($thread_container.width() + $new_thread.css('width'));

	 	// request the content of this thread and replace the loading sign with its content when it loads
	 	var req = new APICaller('thread', 'load');
	 	req.send({ id : thread_id }, function (result) {
	 		if (result) {
	 			//console.log("#" + ids.thread + thread_id);
	 			$("#" + ids.thread + thread_id).replaceWith($.fn.Discussion.threadPrevFormatter(ids, result.id, result.subject, result.body));
	 		} else {
	 			alertHandler("alert", "<p>Failed to load thread</p>");
	 		}
	 	});
	}

	/**
	 * empty
	 */
	 $.fn.setEmpty = function() {

	 	var ids = $.fn.getIds($(this).selector);
	 	var $thread_container = $("#" + ids.thread_container);

	 	$thread_container.append($("<div></div>", {class: "placeholder"}).html("<p>No threads to display</p>"));
	 }

	/*
	 * Append a preview to the end of the previews list
	 * takes an array of thread objects containing at least an id, title, and body
	 */
	 $.fn.Discussion.addPreviews = function (ids,threads) {

	 	$("#" + ids.thread_container).children(".placeholder").remove();

	 	$.each(threads, function (i, value) {

			$("#" + ids.thread_container).prepend($.fn.Discussion.threadPrevFormatter(ids, value.id, value.subject, value.body));

	 	});
	 }

	/*
	 * Add a post to a thread
	 */
	 $.fn.addPost = function (threadid, body) {

	 	var req = new APICaller("post", "create");
	 	var params = {
	 		'thread_id' : threadid,
	 		'body' : body
	 	};
	 	req.send(params, function(result) {
	 		if (result) {
			 	$("#thread_" + threadid).append($.fn.Discussion.postDivFormatter(body));
	 		}
	 		else
				alertHandler("alert", "<p>Failed to submit post</p>");
	 	})
	 }

	 /*
	  * format a thread element for loading
	  */
	 $.fn.Discussion.loadingFormatter = function(ids, thread_id) {

	 	var result = $('<div></div>', {id:ids.thread + thread_id, class: "thread-preview"}).html('<img src="/assets/images/ajax-loader.gif">');
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
	   	return $("<div></div>", {id: ids.thread + id, class: "thread-preview"})
	   	.append($("<h4></h4>").html(subject))
	   	.append($("<p></p>").text(body));
	   }

})(jQuery);