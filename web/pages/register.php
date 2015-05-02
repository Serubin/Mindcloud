<div class="pre-login">
	<!-- Background -->
	<div id="splash-background">
	</div>

	<!-- Register page -->
	<div class="row">
		<div id="registration-pane" class="floating-pane small-10 medium-8 large-6 small-centered columns">
			
			<div id="title">
				<div id="title"> 
					<h1 class="title">register</h1>
			</div>

			<p class="lead">Please fill all of these fields</p>


			<form id="registration_form" data-abide="ajax">

				<!-- first name -->
				<div class="name-fields">
					<label for="register_first"><span class="label">first name</span>		<small>required</small></label>
					<input type="text" required id="register_firstname" name="register_firstname" placeholder="Joshua" />
					<small class="error">Please provide your first name</small>
				</div>		 

				<!-- last name -->
				<div class="name-fields">
					<label for="register_lastname"><span class="label">last name</span>		<small>required</small></label>
					<input type="text" required id="register_lastname" name="register_lastname" placeholder="Schooner" />
					<small class="error">Please provide your last name</small>
				</div>	

				<!-- email -->
				<div class="email-field">
					<label for="register_email"><span class="label">email</span>		<small>required</small></label>
						<input type="email" required id="register_email" name="register_email" placeholder="you@example.com" />
					<small class="error">Email invalid</small>
				</div>

				<!-- password -->
				<div class="password-field">
					<label for="register_password"><span class="label">password</span>		<small><span data-tooltip aria-haspopup="true" class="has-tip" title="Passwords must be at least 8 charaters long and may only contain numbers, letters, and these charaters: @*#&.^!">required</span></small></label>
						<input type="password" required id="register_password" name="register_password" pattern="password" placeholder="password" />
						<small id="login_pass_err" class="error">Passwords must be at least 8 charaters long and may only contain numbers, letters, and these charaters: @*#&.^!"</small>
				</div>

				<!-- password confirm -->
				<div class="password-confirmation-field">
					<label for="register_password"><span class="label">confirm password</span>		<small>required</small></label>
						<input type="password" required data-equalto="register_password" pattern="password" placeholder="password (again...)">
					<small class="error">The password did not match</small>
				</div>

				<!-- year of birth -->
				<div class="birthdate-field">
					<label for="register_year"><span class="label">year of birth</span>		<small>required</small></label>
						<select id="register_year" required>
							<option value ="">Select</option>
						</select>
					<small class="error">please provide your birthday</small>
				</div>


				<!-- gender -->
				<div class="gender-field">
						<label for="register_gender"><span class="label">gender</span>		<small>required</small></label>
						<input type="radio" name="register_gender" value="M" id="register_gender-m" required><label for="register_gender-m">Male</label>
						<input type="radio" name="register_gender" value="F" id="register_gender-f" required><label for="register_gender-f">Female</label>
						<input type="radio" name="register_gender" value="O" id="register_gender-o" required><label for="register_gender-o">Other</label>
				</div>

				<!-- captcha -->
				<div class="captcha">
					<label for="captcha"><span class="label">Human?</span> <small>answer this simple math problem to prove yourself</small></label>
					<div class="row">
						<div class="large-5 medium-5 small-5 columns">
							<img src="/assets/images/captcha.php" id="captcha-img" />
						</div>
						<div class="large-4 medium-4 small-4 columns" id="c_input">
							<input type="text" name="register_captcha" id="register_captcha" required />
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

				<!-- accept terms -->
		    	<div class="accept-terms">
					<input type="checkbox" id="terms-check" required></input>
		    		<label for="terms-check">I have read and accept the <a href="/legal">Terms of Service</a></label>
				</div>

				<br/>
				<button type="submit" class="button primary">register</button>
				</hr>
				<button href="/login" class="button secondary">Already have an account?</button>
			</form>
		</div>
	</div> <!-- end regisetr modal -->
</div>