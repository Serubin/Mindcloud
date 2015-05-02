<?php
/**
 * ADMIN DASHBOARD
 * HOW SKETCHY CAN YA GET
 * Page for quickly hiding problems
 */

// init
session_start();

require_once("../../api/include/db_config.php");

$valid_user = false;

if ($_SESSION['uid'] == RUBIN || $_SESSION['uid'] == SHULLICK) {
	$valid_user = true;
} else 
	error_log("invalid user trying to access admin");


?>

<html>
	<head>
		<title>MINDCLOUD ADMIN</title>
	</head>
	<script>

	<?php

		if (!$valid_user) {
			echo 'ph.pageRequest("/dashboard");';
		}


	?>

	// hide listener
	$(document).on("click", ".row-hide-btn", function (event) {
		
		var req = new APICaller("problem", "hide");
		console.log("submitting hide");
		req.send({id : $(this).attr('id')}, function (result) { 
			if (result) {
				alertHandler("success", "problem hidden");
			}
			else {
				alertHandler("alert", "failed to hide problem");
			}
		});
	});

	$(document).on("click", ".row-show-btn", function (event) {

		var req = new APICaller("problem", "show");
		console.log("submitting show");
		req.send({id : $(this).attr('id')}, function (result) { 
			if (result) {
				alertHandler("success", "problem show");
			}
			else {
				alertHandler("alert", "failed to show problem");
			}
		});
	});

	</script>
	<body>
		<h1>HOTFUCK WE'RE LIVE</h1>
		<h2>MAKE SURE TO HIDE ALL THE BULLSHIT!</H2>
		<table style="width:100%">
		<?php

			if ($valid_user) {
				if ($stmt = $mysqli->prepare("SELECT `id`, `title`, `shorthand`, `status` FROM `problems`")) {

					if ($stmt->execute() && $stmt->store_result()) {

						$stmt->bind_result($id, $title, $shorthand, $status);
						while ($stmt->fetch()) {
							
							echo '<tr>';
							echo '<td><button id="' . $id . '" class="row-hide-btn button">hide</button></td>';
							echo '<td><button id="' . $id . '" class="row-show-btn button">show</button></td>';
							echo '<td>' . (($status == 1) ? "shown" : "hidden") . '</td>';
							echo '<td>' . $id . "</td>";
							echo '<td>' . $title . "</td>";
							echo '<td>' . $shorthand . "</td>";
							echo '</tr>';
						}
					}
				}
			}
		?>
	</table>
	</body>
</html>



