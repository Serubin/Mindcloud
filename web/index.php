<!doctype html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Mindcloud</title>
		<link rel="stylesheet" href="assets/css/login.css" />
		<script src="bower_components/modernizr/modernizr.js"></script>
		<script src="bower_components/masonry/dist/masonry.js"></script>

		<script src="bower_components/jquery/dist/jquery.min.js"></script>
		<script src="bower_components/foundation/js/foundation.min.js"></script>
		<!-- JS app files -->
		<!-- TODO compile into one file -->
		<script src="assets/js/app.js"></script>
		<script src="assets/js/login.js"></script>
		<script src="assets/js/register.js"></script>
		<!-- JS includes -->
		<script src="assets/js/include/APICaller.js"></script>
		<script src="assets/js/include/pageHandler.js"></script>
		<script src="assets/js/include/hashbang.js"></script>
		<script src="assets/js/include/overrides.js"></script>
	</head>
	<body>

		<!-- top bar -->
		<div class="fixed">
			<nav class="top-bar" data-topbar role="navigation">
				<ul class="title-area">
					<li class="name">
							<h1><a href="#"><img class="logo-mini" src="/web/content/shoptimize_logo_transp.png"></a></h1>
					</li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>

			<section class="top-bar-section">
					<!-- Right Nav Section -->
					<ul class="right">
							<li class=""><a href="/web/login">log in</a></li>
							<li class=""><a href="/web/register" >sign up</a></li>
							<li class=""><a href="#">what is this?</a></li>
							<li class="has-dropdown">
								<a href="#">username</a>
								<ul class="dropdown">
									<li><a href="#">First link in dropdown</a></li>
									<li class="active"><a href="#">Active link in dropdown</a></li>
								</ul>
						</li>
					</ul>
				</section>
			</nav>
		</div><!-- end top bar -->
		
		<!-- Content area -->
		<div id="content">

			<!-- Dynamicly Filled -->

		</div><!-- end content area -->
	</body>
</html>
