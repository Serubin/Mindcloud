/******************************************************************************
 * discussion plugin
 * @author Michael Shullick
 *****************************************************************************/

(function($) {

	var forms ='<form id="submit_thread">' +
						'<p>stir the pot.</p>' +
						'<input type="text" id="new_thread_title" class="thread-subject" placeholder="What would you like to say?" required/>' +
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

		// create initial forms instance

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

		// add the toggle button
		$(id).append($forms);
		$(id).append($thread_toggle);

		// Add the threads
		// TODO


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
			thread: id + "_thread_" // existing threads, append id of thread to end
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

	/*
	 * Adding a new thread
	 */
	$.fn.createThread = function(problem_id, title, body) {

	 	var ids = $.fn.getIds($(this).selector);

		var req = new APICaller("thread", "create");
		var params = {
			problem_id : problem_id,
			title : title,
			body : body
		};
		req.send(params, function(result) {
			if (result) {

				// create an array with just this new thread to append
				var thread = {id: result['thread_id'], subject:title, body:body};

				var new_threads = {thread};

				$.fn.Discussion.addPreviews(ids, new_threads);
			}
			else
				alertHandler("alert", "<p>Failed to submit thread</p>");
		});
	};

	$.fn.loadThread = function(thread_id) {

	 	var ids = $.fn.getIds($(this).selector);

	 	// add a thread container with a loading icon identified by the id
	 	$(this).append($.fn.Discussion.loadingFormatter(ids, thread_id));

	 	// request the content of this thread and replace the loading sign with its content when it loads
	 	var req = new APICaller('thread', 'load');
	 	req.send({ id : thread_id }, function (result) {
	 		if (result) {
	 			console.log("#" + ids.thread + thread_id);
	 			$("#" + ids.thread + thread_id).replaceWith($.fn.Discussion.threadPrevFormatter(ids, result.id, result.subject, result.body));
	 		} else {
	 			alertHandler("alert", "<p>Failed to load thread</p>");
	 		}
	 	});


	}

	/*
	 * Append a preview to the end of the previews list
	 * takes an array of thread objects containing at least an id, title, and body
	 */
	 $.fn.Discussion.addPreviews = function (ids,threads) {
	 	$.each(threads, function (i, value) {

			$("#" + ids.self).append($.fn.Discussion.threadPrevFormatter(value.id, value.subject, value.body));

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
	 	var result = '<div class="thread-preview" id="' + ids.thread + thread_id + '">' +
	 				 	'<img src="/assets/images/ajax-loader.gif">'+
	 				'</div>';
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
	   	return $("<div></div>", {id: ids.thread + id, class: "thread-preview"}).append($("<h4></h4>").text(subject)).append($("<p></p>").text(body));
	   }

})(jQuery);