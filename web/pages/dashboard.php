	<h1>dashboard, yo</h1>

<button class="success" data-reveal-id="create_problem_modal">pose a problem</a>
<button class="success">create a solution</a>
<button id="more" name="more">MORE</button>


<input type="text" id="thread_test_title" placeholder="create a thread: title">
<input type="text" id="thread_test_body" placeholder="create a thread: body">
<button id="create_thread">add thread</button>

<input type="text" id="post_test" placeholder="create a post">
<button id="create_post">add post</button>

<div id="discussion_container">

</div>

<!-- pose-a-problem modal -->
<div id="create_problem_modal" class="reveal-modal" data-reveal>
  <h2>Pose a problem</h2>
  <p class="lead">Your couch.  It is mine.</p>

	<form data-abide="ajax" id="submit_problem">

		<!-- title -->
	    <div class="statement-field">
	    	<label>problem statement
	        	<input id="form_problem_statement" type="text" placeholder="Why can't I type with my mind yet?" required/>
	     	</label>
	     	<small class="error">What's your problem?</small>
	    </div>

	    <!-- category -->
	    <div class="category">
	    	<label>category
	    		<select id="form_problem_cat" required aria-invalid="false">
					<option value="">Select a category</option>
				</select>
			</label>
		</div>

	    <!-- description -->
	    <div class="description-field">
	     	<label>description
	        	<textarea id="form_problem_desc" class="small-12 columns" placeholder="Keyboards have been around since like the 1930s..." required></textarea>
	     	</label>
	     	<small class="error">Please elaborate on your problem.</small>
	    </div>

		<!-- tags -->
		<div class="tag-field">
			<label>tags
				<input name="tag_container" id="tag_container" value="" required data-abide-validator="tagsValid"/>
			</label>
		 	<small class="error">Please include at least 5 tags</small>
		 </div>

		<button type="submit" class="button btn-login">create</button>

	   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
	</form>
  <a href="#" class="close-reveal-modal keep-native">&#215;</a>
</div> <!-- end pose-a-problem modal -->

<div id="content_container">

<div>