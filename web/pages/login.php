<div class="pre-login">
	<!-- Background -->
	<div id="splash-background">
	</div>
			
	<div class="row" id="body-row">
		<div id="login-pane" class="floating-pane small-10 medium-8 large-6 small-centered columns">

			<!-- title -->
			<img class="logo" src="/assets/images/logo/welcome_logo.png">

			<p>Please log in.</p>
			<form id="login_form" data-abide="ajax">
				<!-- email -->
				<div class="email-field">
					<span class="label">email</span>
						<input type="email" required id="login_email" name="login_email" placeholder="you@example.com" />
						<small class="error">email invalid</small>
				</div>
				
				<!-- password -->
				<div class="password-field">
					<label><span class="label">password</span>
						<input type="password" id="login_password" name="login_password" required pattern="password" placeholder="password" />
						</label>
						<small id="login_pass_err" class="error">Invalid password</small>
				</div>

				<div class="captcha" style="display: none;">
					<label for="captcha"><span class="label">Human?</span></label>
					<div class="row">
						<div class="large-6 medium-6 small-6 columns">
							<img src="/assets/images/captcha.php" id="captcha-img" />
						</div>
						<div class="large-4 medium-4 small-4 columns" id="c_input">
							<!-- add input as needed -->
						</div>
						<div class="large-1 medium-1 small-1 columns">
							<a class="keep-native" id="reload-captcha">
								<i class="fi-loop"></i>
							</a>
						</div>
					</div>
				</div>

		<button type="submit" class="button primary">login</button>
		<hr>
		<a href="/register" class="button secondary">still need to register?</a>
	</form>



		</div> <!-- end login pane -->
	</div>
</div>