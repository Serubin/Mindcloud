function edit(url){
	
	var id;
	var title;
	var description;
	var status;

	var project_type


	if(url.length < 2) // Handles no get input
		returnTo("/dashboard");

	if(url[1].toLowerCase() == "problem"){
		project_type = "problem";
	} else if(url[1].toLowerCase() == "solution") {
		project_type = "solution";
	}

	var req = new APICaller(project_type, "getId");
	var params = {shorthand: url[2]}
	req.send(params, function(result){
		if(!result) // return to dashboard if no problem is found
			returnTo("/dashboard");

		id = result;

		var req = new APICaller(project_type, "loadPreview");
		var params = {id: result}
		req.send(params, function(result){
			if(!result) // Returns back to problem if there is an issue loading
				returnTo("/" + project_type + "/" + url[2]);

			if(!result.can_edit) // returns to problem if cannot edit
				returnTo("/" + project_type + "/" + url[2]);

			title = result.title;
			description = result.description;
			status = result.status;

			$("#edit-title").html(title);
			$("#form_edit_statement").val(title);
			$("#form_edit_desc").val(description);
			$("#form_edit_hide").prop('checked', status == 3).val(status).change(function(){
				if($(this).prop("checked"))
					$(this).val(3);
				else
					$(this).val(1);
			});
		});
	})

	// Edit form listener
	$('#edit-form').on('valid', function() {
		var req = new APICaller(project_type, "update");
		var params = {
			id: id,
			title: $("#form_edit_statement").val(),
			description: $("#form_edit_desc").val(),
			status: $("#form_edit_hide").val()
		}

		req.send(params, function(result){
			if(!result) {
				new alertHandler("alert", "There was an error submiting your edit.");
				return;
			}
			returnTo("/" + project_type + "/" + url[2]);
		})
	});

	// preview listener
	$("#edit-preview-button").click(function(){
		$("#edit-text-preview").html(wiky.process($("#form_edit_desc").val(),{}));
	});

	function returnTo(location){
		ph.pageRequest(location);
		return;
	}		
}