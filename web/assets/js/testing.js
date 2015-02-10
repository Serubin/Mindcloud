// initalize foundation
$(document).foundation();

// Login form submission, validation done by Foundation form-abide
$('#submit-problem').on('valid.fndtn.abide', function() {
	console.log("Trying");
	var req = new APICaller('problem', 'create');
	var params = {statement: $("#form_problem_statement").val(), description:$("#form_problem_description").val()};
	req.send(params, function(result) {
			if (result) {
				$("#register_modal").foundation('reveal', 'close');
				alert("good job");

			}
		});
});

