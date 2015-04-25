<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Mindcloud</title>
		<link rel="stylesheet" href="/assets/css/master.css" />
		<!--<link rel="stylesheet" href="/grunt/bower_components/jquery-tags-input/jquery.tagsinput.css" />-->

		
		<script type="text/javascript" src="/assets/js/frameworks.js"></script>
		<!-- JS app files -->
		<script type="text/javascript" src="/assets/js/apps.js"></script>
		<!-- JS includes -->
		<script type="text/javascript" src="/assets/js/includes.js"></script>
	</head>
	<body>
		<!-- top bar -->
		<div class="fixed">
			<nav class="top-bar" id="navigation" data-topbar role="navigation">
				<!-- Dynamicly filled -->
			</nav>
		</div><!-- end top bar -->
		
		<!-- utility classes -->
		<div id="alert-wrapper">
		</div> <!-- end utility classes-->

		<!-- content area -->
		<div id="content">

			<!-- Dynamicly Filled -->

		</div><!-- end content area -->

		<div id="pose_create">
			<!-- pose-a-problem modal -->
			<div id="pose_problem_modal" class="reveal-modal creation_modal" data-reveal>
			  <h2>Pose a problem</h2>

				<form data-abide="ajax" id="submit_problem">

					<!-- title -->
				    <div class="statement-field">
				    	<label>problem statement
				        	<input id="form_problem_statement" type="text" maxlength="200" placeholder="Why can't I type with my mind yet?" required/>
				     	</label>
				     	<small class="error">What's your problem?</small>
				    </div>

				    <!-- shorthand -->
				    <div class="shorthand-field row collapse">
						<label>problem url</label>
						<div class="small-4 large-3 columns">
							<span class="prefix">mindcloud.io/problem/</span>
						</div>
						<div class="small-8 large-9 columns">
							<input id="form_problem_shorthand" type="text" maxlength="200" placeholder="cant-type-with-mind"/>
						</div>
					</div>
				    <!-- category -->
				    <div class="category">
				    	<label>category</label>
				    		<select id="form_problem_cat" required aria-invalid="false">
								<option value="">Select a category</option>
							</select>
					</div>

				    <!-- description -->
				    <div class="description-field">
				     	<label>description</label>
				        	<textarea id="form_problem_desc" class="small-12 columns" placeholder="Keyboards have been around since like the 1930s..." required></textarea>
				     	<small class="error">Please elaborate on your problem.</small>
				    </div>

					<!-- tags -->
					<div class="tag-field">
						<label>tags <small>use commas to seperate</small></label>
						<input name="tag_container" id="tag_container" value="" required data-abide-validator="tagsValid"/>
					 	<small class="error">Please include at least 5 tags</small>
					 </div>

					<button type="submit" class="button btn-login">create</button>

				   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
				</form>
			  <a href="#" class="close-reveal-modal keep-native">&#215;</a>
			</div> <!-- end pose-a-problem modal -->


			<!-- create-a-solution modal -->
			<div id="create_solution_modal" class="reveal-modal creation_modal" data-reveal>
				<h2>Create a solution</h2>
				<h2 class="subtitle"><small class="subtitle_for">For </small><small id="create_solution_for"> </small></h2>

				<form data-abide="ajax" id="submit_solution">

					<!-- title -->
				    <div class="statement-field">
				    	<label>solution statement</label>
				        <input id="form_solution_statement" type="text" placeholder="Typing without typing." required/>
				     	<small class="error">What's your solution?</small>
				    </div>
				    <!-- shorthand -->
					<div class="shorthand-field row collapse">
						<label>solution url</label>
						<div class="small-4 large-3 columns">
							<span class="prefix">mindcloud.io/solution/</span>
						</div>
						<div class="small-8 large-9 columns">
							<input id="form_solution_shorthand" type="text" maxlength="200" placeholder="typing-without-typing"/>
						</div>
					</div>

				    <!-- description -->
				    <div class="description-field">
				     	<label>description</label>
				        <textarea id="form_solution_desc" class="small-12 columns" placeholder="All you have to do is think it and Typing without Typing will do it for you." required></textarea>
				     	<small class="error">Please elaborate on your solution</small>
				    </div>

					<button type="submit" class="button btn-login">create</button>

				   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
				</form>
			  <a href="#" class="close-reveal-modal keep-native">&#215;</a>
			</div>
		</div>
	</body>
</html>
