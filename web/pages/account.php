<!-- TODO must be updated to match pages -->
<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Foundation</title>
		<link rel="stylesheet" href="css/login.css" />
		<script src="bower_components/modernizr/modernizr.js"></script>
	</head>
	<body>

		<!-- top bar -->
		<div class="fixed">
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area">
					<li class="name">
							<!--<h1><a href="#"><img class="logo-mini" src="/web/content/shoptimize_logo_transp.png"></a></h1>-->
					</li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>

			<section class="top-bar-section">
					<!-- Right Nav Section -->
					<ul class="right">
						<li class="has-form">
			 	<div class="row collapse">
						<div class="large-8 small-9 columns">
						<input type="text" placeholder="problems, projects, people">
					</div>
					<div class="large-4 small-3 columns">
						<a href="#" class="button expand">search</a>
					</div>
				</div>
			</li>
							<li class="has-dropdown">
								<a href="#" id="menu_username">username</a>
								<ul class="dropdown">
									<li><a href="#">Account</a></li>
									<li><a href="#">Logout</a></li>
								</ul>
							<li class=""><a href="#">button example</a></li>
						</li>
					</ul>
				</section>
			</nav>
		</div><!-- end top bar -->

		<div id="main" class="text-center">

		<h1> Account Settings </h1>

		<ul class="accordion" data-accordion>

			<!-- general -->
			<li class="accordion-navigation">
				<a href="#panel_general">general</a>
				<div id="panel_general" class="content">
						<label>Your name
								<input type="text" id="#form_account_name" placeholder="name" />
							</label>
					 </div>
			</li>
			
			<li class="accordion-navigation">
				<a href="#panel_content">content</a>
				<div id="panel_content" class="content">
				Panel 2. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</div>
			</li>
			<li class="accordion-navigation">
				<a href="#panel_notifications">notifications</a>
				<div id="panel_notifications" class="content">
				Panel 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</div>
			</li>
			<li class="accordion-navigation">
				<a href="#panel_stats">stats</a>
				<div id="panel_stats" class="content">
				Panel 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</div>
			</li>
		</ul>


			<a href="#" class="button" id="btn_save">save changes</a>

	</div>
		<script src="bower_components/jquery/dist/jquery.min.js"></script>
		<script src="bower_components/foundation/js/foundation.min.js"></script>
		<script src="js/account.js"></script>
	</body>
</html>
