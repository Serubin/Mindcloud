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
			<div id="pose_problem_modal" class="reveal-modal" data-reveal>
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
						<label>tags <small>use commas to seperate</small>
							<input name="tag_container" id="tag_container" value="" required data-abide-validator="tagsValid"/>
						</label>
					 	<small class="error">Please include at least 5 tags</small>
					 </div>

					<button type="submit" class="button btn-login">create</button>

				   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
				</form>
			  <a href="#" class="close-reveal-modal keep-native">&#215;</a>
			</div> <!-- end pose-a-problem modal -->
			<!-- create-a-solution modal -->
			<div id="create_solution_modal" class="reveal-modal" data-reveal>
			</div>
		</div>
	</body>
</html>
