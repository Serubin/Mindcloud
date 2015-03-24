<!-- Background -->
<div id="splash-background">
</div>

<!-- Register page -->
<div id="title">
	<div id="title"> 
		<p class="subtitle">welcome to</p>
		<h1 class="title">mindcloud</h1>
</div>
</div>
<div class="row">
	<div id="registration-pane" class="column small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3" data-reveal>
		
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
				<label for="register_password"><span class="label">password</span>		<small>required</small></label>
					<input type="password" required id="register_password" name="register_password" pattern="password" placeholder="password" />
					<small id="login_pass_err" class="error">Please enter a valid password</small>
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
					<input type="radio" name="register_gender" value="M" id="register_gender" required><label for="male">Male
					<input type="radio" name="register_gender" value="F" id="register_gender" required><label for="female">Female
					<input type="radio" name="register_gender" value="O" id="register_gender" required><label for="other">Other
			</div>
			<div class="captcha">
				<label for="captcha"><span class="label">Human?</span></label>
				<div class="row">
					<div class="large-2 small-3 columns">
						<img src="/assets/images/captcha.php" id="captcha-img" />
					</div>
					<div class="large-2 small-3 columns">
						<input type="text" name="captcha" required />
					</div>
					<div class="large-2 large-offset-6 small-3 small-offset-3 columns">
						<a class="keep-native" id="reload-captcha">reload captcha</a>
					</div>
				</div>
			</div>
			<br/>
			<button type="submit">register</button>
			<a href="login" class="button secondary" id="reg_to_login">Already have an account?</a>
		</form>
	</div>
</div> <!-- end regiser modal -->