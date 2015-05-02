<div id="help-page" class="pre-login row">
	<!-- Background -->
	<div id="splash-background">
	</div>

	<div class="floating-pane column small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3">

	<h4 class="text-center">Help and Contact</h4>
	<p>We understand that mindcloud is very new and still has some issues laying around. We are eager to help you get the best experience from mindcloud. If you have any issues please just send us an email using the form below!</p>
	<p>If you have any feed back, head to our <a href="/problem/feedback">feedback problem</a> and make a post or solution!</p> 
	
	<form id="help_form" data-abide="ajax">
		<!-- name -->
		<div class="name-field">
			<label><span class="label">name</span>
			<input type="text" required id="help_name" name="help_name" placeholder="John Smith" />
			</label>
			<small class="error">enter your name</small>
		</div>
		<!-- email -->
		<div class="email-field">
			<label><span class="label">your email</span>
			<input type="email" required id="help_email" name="help_email" placeholder="you@example.com" />
			</label>
			<small class="error">email invalid</small>
		</div>
		<!-- subject -->
		<div class="subject-field">
			<label><span class="label">subject</span>
			<input type="text" required id="help_subject" name="help_subject" placeholder="I need help with..." />
			</label>
			<small class="error">enter a subject</small>
		</div>
		<!-- subject -->
		<div class="body-field">
			<label><span class="label">body</span>
			<textarea required id="help_body" name="help_body" placeholder="I need help with... (more detail)" ></textarea>
			</label>
			<small class="error">enter a body</small>
		</div>
		<!-- captcha -->
		<div class="captcha">
			<label for="captcha"><span class="label">Human?</span> <small>answer this simple math problem to prove yourself</small></label>
			<div class="row">
				<div class="large-5 medium-5 small-5 columns">
					<img src="/assets/images/captcha.php" id="captcha-img" />
				</div>
				<div class="large-4 medium-4 small-4 columns" id="c_input">
					<input type="text" name="help_captcha" id="help_captcha" required />
				</div>
				<div class="large-2 medium-2 small-2 columns">
					<span data-tooltip aria-haspopup="true" class="has-tip" title="click to reload if you have trouble seeing the image">
						<a class="keep-native" id="reload-captcha">
							<i class="fi-loop"></i>
						</a>
					</span>
				</div>
			</div>
		</div>
		<br/>
		<button type="submit" class="button primary">send</button>
	</div>
</div>