<div id="splash">
	<!-- Background -->
	<div id="splash-background">
	</div>

	<!-- title -->
	<div id="title"> 
		<p class="subtitle">welcome to</p>
		<h1 class="title">mindcloud</h1>
	</div>
			
	<div class="row">
		<div id="login-pane" class="floating-pane small-10 medium-4 column small-offset-1 medium-offset-4">
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
						<small id="login_pass_err" class="error">Please enter a valid password</small>
				</div>

				<div class="captcha" style="display: none;">
					<label for="captcha"><span class="label">Human?</span></label>
					<div class="row">
						<div class="large-6 medium-6 small-6 columns">
							<img src="/assets/images/captcha.php" id="captcha-img" />
						</div>
						<div class="large-5 medium-5 small-5 columns" id="c_input">
							<!-- add input as needed -->
						</div>
						<div class="large-1 medium-1 small-1 columns">
							<a class="keep-native" id="reload-captcha">
								<i class="fi-loop"></i>
							</a>
						</div>
					</div>
				</div>

		<button type="submit" class="button btn-login">login</button>
		<button id="login_forgot" class="button btn-login">help</button>
	</form>



		</div> <!-- end login pane -->
	</div>
</div>