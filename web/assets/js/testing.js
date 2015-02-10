// initalize foundation
$(document).foundation();

// Login form submission, validation done by Foundation form-abide
$('#submit-problem').on('valid', function() {
	var req = new APICaller('problem', 'create');
	var params = {statement: $("#form_problem_statement").val(), description:$("#form_problem_desc").text()};
	req.send(params, function(result) {
			if (result) {
				$("#register_modal").foundation('reveal', 'close');
				alert("good job");

			}
		});
});

