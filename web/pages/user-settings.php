<div class="row">
	<div class="small-12 columns">
		<h1> Account Settings </h1>
	</div>
</div>
<div class="row">
	<div class="small-12 columns">

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
				<input type="radio" name="register_gender" value="M" id="register_gender" required><label for="male">Male
				<input type="radio" name="register_gender" value="F" id="register_gender" required><label for="female">Female
				<input type="radio" name="register_gender" value="O" id="register_gender" required><label for="other">Other
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
		<form id="update_info_form" data-abide="ajax">

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