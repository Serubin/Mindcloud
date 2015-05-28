<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Mindcloud</title>
		<link rel="icon" href="/favicon.png" sizes="128x128" type="image/png">
		<!--[if IE]><link rel="shortcut icon" href="/favicon.ico"><![endif]-->
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
				    	<!-- shorthand label -->
						<label><span data-tooltip aria-haspopup="true" class="has-tip" title="This will be the url used to access your problem. Note: this cannot be changed once selected">problem url</span>
							<small>
								&nbsp <span data-tooltip aria-haspopup="true" class="has-tip" title="one will automaticly be generated from your title if blank">optional</span>
							</small>
						</label>
						<!-- shorthand input -->
						<div class="small-4 large-3 columns">
							<span class="prefix show-for-medium-up">mindcloud.io/problem/</span>
							<span class="prefix show-for-small-only">problem/</span> <!-- fix for mobile -->
						</div>
						<div class="small-8 large-9 columns">
							<input id="form_problem_shorthand" type="text" maxlength="100" placeholder="cant-type-with-mind"/>
							<small class="error">shorthand taken</small>
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
				    <div class="description-field clearfix">
				     	<label>description</label>
			     		<!-- tabs for edit and preview-->
			     		<ul class="tabs right" data-tab role="tablist">
							<li class="tab-title active" role="presentational" >
								<a href="#problem-editing" role="tab" tabindex="0" aria-selected="true" controls="problem-editing">editing</a>
							</li>
							<li class="tab-title" role="presentational">
								<a href="#problem-preview" id="problem-preview-button" role="tab" tabindex="0" aria-selected="false" controls="problem-preview">preview</a>
							</li>
						</ul>
						<!-- editing and preview tab content -->
						<div class="tabs-content">
							<div class="content active" id="problem-editing">
							<textarea id="form_problem_desc" class="small-12 columns" placeholder="Keyboards have been around since the 1930s..." rows="8" required></textarea>
							</div>
							<div class="content" id="problem-preview">
								<div id="problem-text-preview">
								</div>
							</div>
							<a href="#"  data-reveal-id="wiki_mark_modal" >descriptions support our wiki mark-up</a>
						</div>
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
						<!-- shorthand label -->
						<label><span data-tooltip aria-haspopup="true" class="has-tip" title="This will be the url used to access your solution. Note: this cannot be changed once selected">solution url</span>
							<small>
								&nbsp <span data-tooltip aria-haspopup="true" class="has-tip" title="one will automaticly be generated from your title if blank">optional</span>
							</small>
						</label>
						<!-- shorthand input -->
						<div class="small-4 large-3 columns">
							<span class="prefix show-for-medium-up">mindcloud.io/solution/</span>
							<span class="prefix show-for-small-only">solution/</span> <!-- fix for mobile -->
						</div>
						<div class="small-8 large-9 columns">
							<input id="form_solution_shorthand" type="text" maxlength="100" placeholder="typing-without-typing"/>
							<small class="error">shorthand taken</small>
						</div>
					</div>

				    <!-- description -->
				    <div class="description-field clearfix">
				     	<label>description</label>
				     	<!-- tabs for edit and preview-->
			     		<ul class="tabs right" data-tab role="tablist">
							<li class="tab-title active" role="presentational" >
								<a href="#solution-editing" role="tab" tabindex="0" aria-selected="true" controls="solution-editing">editing</a>
							</li>
							<li class="tab-title" role="presentational">
								<a href="#solution-preview" id="solution-preview-button" role="tab" tabindex="0" aria-selected="false" controls="solution-preview">preview</a>
							</li>
						</ul>
						<!-- editing and preview tab content -->
						<div class="tabs-content">
							<div class="content active" id="solution-editing">
								<textarea id="form_solution_desc" class="small-12 columns" placeholder="All you have to do is think it and Typing without Typing will do it for you." rows="8" required></textarea>
							</div>
							<div class="content" id="solution-preview">
								<div id="solution-text-preview">
								</div>
							</div>
							<a href="#"  data-reveal-id="wiki_mark_modal" >descriptions support our wiki mark-up</a>
						</div>
				     	<small class="error">Please elaborate on your solution</small>
				    </div>

					<button type="submit" class="button btn-login">create</button>

				   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
				</form>
			  <a href="#" class="close-reveal-modal keep-native">&#215;</a>
			</div>
		</div>

		<div id="help-modals">
			<div id="wiki_mark_modal" class="reveal-modal help_modal" data-reveal>
				<h2>Wiki mark up, the basics</h2>
				<p>We understand that your communication often requires more than just basic text. 
				For just this reason we've developed a simple and easy to use version of standard wiki
				markup to help spice up your ideas.</p>

				<!-- Title -->
				<div class="row">
					<div class="small-12 columns">
						<h3>Title tags</h3>
					</div>
					<div class="small-12 medium-6 columns">
						<p> == Title == </p>
						<br/>
						<p> === Subtitle === </p>

					</div>
					<div class="small-12 medium-6 columns">
						<h2> Title </h2>
						<h3> Subtitle</h3>
					</div>
				</div>
				<!-- text Style -->
				<div class="row">
					<div class="small-12 columns">
						<hr/>
						<h3>Text Styles</h3>
					</div>
					<div class="small-12 medium-6 columns">
						<p> * Bold * </p>
						<p> _ Underline _ </p>
						<p> / Italics / </p>

					</div>
					<div class="small-12 medium-6 columns">
						<p class="bold"> Bold </p>
						<p class="underline"> Underline </p>
						<p class="italics"> Italics </p>
					</div>
				</div>
				<!-- Lists -->
				<div class="row">
					<div class="small-12 columns">
						<hr/>
						<h3>List Styles</h3>
					</div>
					<div class="small-12 medium-6 columns">
						<p> * Unordered lists </p>
						<p> * List Item </p>
						<p> * List Item </p>
						
						<br />

						<p> # Ordered lists </p>
						<p> # List item </p>
						<p> # List items </p>
					</div>
					<div class="small-12 medium-6 columns">
						<ul>
							<li> Unorder lists </li>
							<br />
							<li> List Item </li>
							<br />
							<li> List Item </li>
							<br />
						</ul>
						
						<ol>
							<li> Ordered list </li>
							<br />
							<li> List item </li>
							<br />
							<li> List Item </li>
							<br />
						</ol>
					</div>
				</div>
				<!-- text Style -->
				<div class="row">
					<div class="small-12 columns">
						<hr/>
						<h3>Links and Photos </h3>
					</div>
					<div class="small-12 medium-6 columns">
						<p> [https://mindcloud.io Link Title] </p>
						<p> [[File:https://mindcloud.io/assets<br/>/images/mindcloud.png Alternative Text]] </p>

					</div>
					<div class="small-12 medium-6 columns">
						<a href="https://mindcloud.io">Link Title</a>
						<br />
						<img src="https://mindcloud.io/assets/images/mindcloud.png" alt="Alternative Text" />
					</div>
				</div>
				<a href="#" class="close-reveal-modal keep-native">&#215;</a>
			</div>
		</div>

		<!-- social beta feedback -->
		<div id="social">
			<a href="https://www.facebook.com/mindcloudio" class="keep-native" target="_blanks" ><small><i class="fi-social-facebook"></i> like us</small></a>
			<a href="/help" target="_blanks" >	<small><i class="fi-alert"></i> help</small></a>
			<a href="/problem/feedback" target="_blanks" >	<small><i class="fi-results-demographics"></i> feedback</small></a>
			<small>&#169; mindcloud - beta</small>
		</div>
	</body>
</html>
