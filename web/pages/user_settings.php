<div class="row">
	<!-- coming soon overlay -->
	<div class="overlay" style="background-color: rgba(0, 0, 0, 0.6);" id="about-page">
		<div class="pre-login row" style="margin: 10rem 0;">
			<div class="small-12 medium-6 column small-centered">
				<div class="floating-pane">
					<br/>
					<h3>Account settings coming soon!</h3>
					<br/>
					<p>Account settings and many more exciting features are on their way to you!</p>
				</div>
			</div>
		</div>		
	</div>

	<div class="small-12 columns">
		<h1> Account Settings </h1>
	</div>
</div>
<div class="row">
	<div class="large-6 medium-6 small-12 columns">
		<h3>Email settings</h3>
		<form id="update_settings_form">
			<input type="checkbox" name="email_updates" id="email_updates" required>
			<label for="email_updates">Receive periodic email updates</label>
			<br/>
			<input type="checkbox" name="notification_updates" id="notification_updates" required>
			<label for="notification_updates">Receive email updates about your notifications</label>
		</form>
	</div>
	<div class="large-6 medium-6 small-12 columns">
		<h3>Active sessions</h3>
		<form id="active_sessions_form">
			<!-- to be filled -->
		</form>
	</div>
</div>
<div class="row">
	<div class="large-6 medium-6 small-12 columns">
		<form id="update_info_form" data-abide="ajax">
			<h3>Update information</h3>
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
			<!-- gender -->
			<div class="gender-field">
				<label for="register_gender"><span class="label">gender</span>		<small>required</small></label>
				<input type="radio" name="update_gender" value="M" id="update_gender-m" required><label for="update_gender-m">Male</label>
				<input type="radio" name="update_gender" value="F" id="update_gender-f" required><label for="update_gender-f">Female</label>
				<input type="radio" name="update_gender" value="O" id="update_gender-o" required><label for="update_gender-o">Other</label>
			</div>
			<div class="password-field">
				<label for="current_password"><span class="label">current password</span>		<small>required</small></label>
				<input type="password" required id="current_password" name="current_password" pattern="password" placeholder="password" />
				<small id="login_pass_err" class="error">Please enter a valid password</small>
			</div>
			<button type="submit">update</button>
		</form>
	</div>
	
	<div class="large-6 medium-6 small-12 columns">
		<h3>Update password</h3>
		<form id="update_password_form" data-abide="ajax">

			<!-- verify password -->
			<div class="password-field">
				<label for="current_password"><span class="label">current password</span>		<small>required</small></label>
				<input type="password" required id="current_password" name="current_password" pattern="password" placeholder="password" />
				<small id="login_pass_err" class="error">Please enter a valid password</small>
			</div>

			<!-- new password -->
			<div class="password-field">
				<label for="new_password"><span class="label">password</span>		<small>required</small></label>
				<input type="password" required id="new_password" name="new_password" pattern="password" placeholder="password" />
				<small id="login_pass_err" class="error">Please enter a valid password</small>
			</div>

			<!-- password confirm -->
			<div class="password-confirmation-field">
				<label for="confirm_password"><span class="label">confirm password</span>		<small>required</small></label>
				<input type="password" required data-equalto="new_password" pattern="password" placeholder="password (again...)">
				<small class="error">The password did not match</small>
			</div>
			<button type="submit">update</button>
		</form>
	</div>
</div>