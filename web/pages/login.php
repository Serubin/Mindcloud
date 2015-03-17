<!-- Background -->
<div id="splash-background">
</div>

<!-- title -->
<div id="title"> 
	<p class="subtitle">welcome to</p>
	<h1 class="title">mindcloud</h1>
</div>
		
<div class="row">
	<div class="small-10 medium-4 column small-offset-1 medium-offset-4" id="login-pane">
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

	<button type="submit" class="button btn-login">login</button>
	<button id="login_forgot" class="button btn-login">help</button>
</form>



	</div> <!-- end login pane -->
</div>